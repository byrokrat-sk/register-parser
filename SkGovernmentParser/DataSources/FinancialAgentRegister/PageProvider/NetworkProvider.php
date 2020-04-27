<?php


namespace SkGovernmentParser\DataSources\FinancialAgentRegister\PageProvider;


use SkGovernmentParser\DataSources\FinancialAgentRegister\FinanfialAgentRegisterPageProvider;
use SkGovernmentParser\DataSources\FinancialAgentRegister\Parser\FinancialAgentPageParser;
use SkGovernmentParser\DataSources\FinancialAgentRegister\Parser\SearchPageResultParser;
use SkGovernmentParser\Exceptions\BadHttpRequestException;
use SkGovernmentParser\Exceptions\EmptySearchResultException;
use SkGovernmentParser\Helper\CurlHelper;
use SkGovernmentParser\Helper\StringHelper;

class NetworkProvider implements FinanfialAgentRegisterPageProvider
{
    public const SEARCH_PAGE_URL = '/search.php';

    private string $RootUrl;

    private static ?string $PhpSessionId = null;
    private static ?string $SearchToken = null;

    public function __construct(string $rootUrl)
    {
        $this->RootUrl = $rootUrl;
    }

    public function getSearchPageHtml(string $query): string
    {
        $accessToken = $this->getAccessToken();
        $searchResponse = CurlHelper::post($this->RootUrl.self::SEARCH_PAGE_URL, [
            'search_val' => $query,
            'token' => $accessToken,
            'search_set' => 'Hľadaj',
        ]);

        if (!$searchResponse->isOk()) {
            throw new BadHttpRequestException("Search request for financial agent was not succesfull. Returned HTTP code [$searchResponse->HttpCode]!");
        }

        self::$PhpSessionId = StringHelper::stringBetween($searchResponse->HttpHeaders['set-cookie'][0], 'PHPSESSID=', ';');

        /*
         * There is case when register return page of financial agent immediately without search results page mid-step
         * In this case I will throw up exception because I am too lazy to implement this some other way.
         * TODO: Fix this code "architecture" mistake
         */
        if (!StringHelper::str_contains($searchResponse->Response, '<div align="right">Spolu')) {
            /*
             * "These Are Confusing Times"
             *     ~ Hulk, Avengers: Endgame
             */
            throw new AgentPageProvidedException('Financial Agent register provided html code of agent page instead of search result.', $searchResponse->Response);
        }

        return $searchResponse->Response;
    }

    public function getAgentPageHtml(string $number): string
    {
        try {
            $searchPageHtml = $this->getSearchPageHtml($number);
            $parsedResult = SearchPageResultParser::parseHtml($searchPageHtml);

            $matchedAgent = $parsedResult->withNumber($number);

            if (is_null($matchedAgent)) {
                return new EmptySearchResultException("Financial agent with number [$number] was not found");
            }

            $agentPageResponse = CurlHelper::get($this->RootUrl.self::SEARCH_PAGE_URL, [
                'row' => $matchedAgent->Row
            ], [
                'Cookie: PHPSESSID='.self::$PhpSessionId
            ]);

            if (!$agentPageResponse->isOk()) {
                throw new BadHttpRequestException("Requesting agent page was not succesfull. HTTP code [$agentPageResponse->HttpCode] was returned!");
            }

            return $agentPageResponse->Response;
        } catch (AgentPageProvidedException $agentPageProvided) {
            return $agentPageProvided->AgentPageHtml;
        }
    }

    # ~

    private function getAccessToken(): string
    {
        if (is_null(self::$SearchToken)) {
            $registerIndexResponse = CurlHelper::get($this->RootUrl.self::SEARCH_PAGE_URL);

            if (!$registerIndexResponse->isOk()) {
                throw new BadHttpRequestException("Request for getting access token was not succesfull. HTTP code [$registerIndexResponse->HttpCode] returned!");
            }

            self::$SearchToken = StringHelper::stringBetween($registerIndexResponse->Response, '<input class="formular" name="token" type="hidden" value="', '"');
        }

        return self::$SearchToken;
    }
}
