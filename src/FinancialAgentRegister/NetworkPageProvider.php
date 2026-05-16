<?php

declare(strict_types=1);

namespace ByrokratSk\FinancialAgentRegister;

use ByrokratSk\Exception\BadHttpRequestException;
use ByrokratSk\Exception\EmptySearchResultException;
use ByrokratSk\FinancialAgentRegister\Model\Search\Item;
use ByrokratSk\FinancialAgentRegister\Model\Search\Result;
use ByrokratSk\FinancialAgentRegister\Parser\SearchPageResultParser;
use ByrokratSk\Helper\StringHelper;
use GuzzleHttp\Client;

class NetworkPageProvider implements PageProvider
{
    public const SEARCH_PAGE_URL = '/search.php';

    private static ?string $PhpSessionId = null;
    private static ?string $SearchToken = null;

    public function __construct(
        private readonly Client $HttpClient,
        private readonly string $RootUrl,
    ) {}

    public function getSearchPageHtml(string $query, int $pageNumber = 1): string
    {
        $accessToken = $this->getAccessToken();
        $searchResponse = $this->HttpClient->post($this->RootUrl . self::SEARCH_PAGE_URL, [
            'query' => [
                'pg' => $pageNumber,
            ],
            'form_params' => [
                'search_val' => $query,
                'token' => $accessToken,
                'search_set' => 'Hľadaj',
            ],
        ]);

        if (200 !== $searchResponse->getStatusCode()) {
            throw new BadHttpRequestException(
                "Search request for financial agent [{$query}] was not succesfull. Register returned HTTP code [{$searchResponse->getStatusCode()}]!",
            );
        }

        self::$PhpSessionId = StringHelper::stringBetween(
            $searchResponse->getHeader('set-cookie')[0],
            'PHPSESSID=',
            ';',
        );

        $responseContent = $searchResponse->getBody()->getContents();

        /*
         * There is case when register return page of financial agent immediately without search results page mid-step
         * In this case I will throw up exception because I am too lazy to implement this some other way.
         * TODO: Fix this code "architecture" mistake
         */
        if (StringHelper::str_contains($responseContent, 'Identifikačné údaje')) {
            /*
             * "These Are Confusing Times"
             *     ~ Hulk, Avengers: Endgame
             */
            throw new AgentPageProvidedException(
                'Financial Agent register provided html code of agent page instead of search result.',
                $responseContent,
            );
        }

        return $responseContent;
    }

    public function getAgentPageHtmlByNumber(string $registrationNumber): string
    {
        try {
            $matchedAgent = null;
            foreach (self::querySearchItems($registrationNumber) as $searchItem) {
                if ($searchItem->Number !== $registrationNumber) { continue; }

$matchedAgent = $searchItem;
                    break;
            }

            if (null === $matchedAgent) {
                throw new EmptySearchResultException(
                    "Financial agent with registration number [{$registrationNumber}] was not found",
                );
            }

            $agentPageResponse = $this->HttpClient->request('GET', $this->RootUrl . self::SEARCH_PAGE_URL, [
                'query' => [
                    'row' => $matchedAgent->Row,
                ],
                'headers' => [
                    'Cookie' => 'PHPSESSID=' . self::$PhpSessionId,
                ],
            ]);

            if (200 !== $agentPageResponse->getStatusCode()) {
                throw new BadHttpRequestException(
                    "Requesting agent page was not succesfull. HTTP code [{$agentPageResponse->getStatusCode()}] was returned!",
                );
            }

            return $agentPageResponse->getBody()->getContents();
        } catch (AgentPageProvidedException $agentPageProvided) {
            return $agentPageProvided->AgentPageHtml;
        }
    }

    public function getAgentPageHtmlByCin(string $cin): string
    {
        try {
            $matchedAgent = null;
            foreach (self::querySearchItems($cin) as $searchItem) {
                // TODO: Rewrite to agent page parsing and check if CIN is equal to desired CIN number
                $matchedAgent = $searchItem;
                break;
            }

            if (null === $matchedAgent) {
                throw new EmptySearchResultException("Financial agent with CIN [{$cin}] was not found");
            }

            $agentPageResponse = $this->HttpClient->request('GET', $this->RootUrl . self::SEARCH_PAGE_URL, [
                'query' => [
                    'row' => $matchedAgent->Row,
                ],
                'headers' => [
                    'Cookie' => 'PHPSESSID=' . self::$PhpSessionId,
                ],
            ]);

            if (200 !== $agentPageResponse->getStatusCode()) {
                throw new BadHttpRequestException(
                    "Requesting agent page was not succesfull. HTTP code [{$agentPageResponse->getStatusCode()}] was returned!",
                );
            }

            return $agentPageResponse->getBody()->getContents();
        } catch (AgentPageProvidedException $agentPageProvided) {
            return $agentPageProvided->AgentPageHtml;
        }
    }

    // ~

    /** @return \Generator|Item[] */
    private function querySearchItems(string $searchQuery): \Generator
    {
        /** @var Result $pageResult */
        foreach (self::querySearchPages($searchQuery) as $pageResult) {
            foreach ($pageResult->getItems() as $searchItem) {
                yield $searchItem;
            }
        }
    }

    /** @return \Generator|Result[] */
    private function querySearchPages(string $searchQuery): \Generator
    {
        $parsedResult = null;
        $pageNumber = 1;

        /*
         * If register return multiple page result then parse will requests all pages until there is not match with
         * registration number.
         */
        while (!$parsedResult instanceof Result || $parsedResult->hasNextPage()) {
            $searchPageHtml = $this->getSearchPageHtml($searchQuery, $pageNumber);
            $parsedResult = SearchPageResultParser::parseHtml($searchPageHtml);

            yield $parsedResult;

            ++$pageNumber;
        }
    }

    private function getAccessToken(): string
    {
        if (null === self::$SearchToken) {
            $registerIndexResponse = $this->HttpClient->get($this->RootUrl . self::SEARCH_PAGE_URL);

            if (200 !== $registerIndexResponse->getStatusCode()) {
                throw new BadHttpRequestException(
                    "Request for getting access token was not succesfull. HTTP code [{$registerIndexResponse->getStatusCode()}] returned!",
                );
            }

            self::$SearchToken = StringHelper::stringBetween(
                $registerIndexResponse->getBody()->getContents(),
                '<input class="formular" name="token" type="hidden" value="',
                '"',
            );
        }

        return self::$SearchToken;
    }
}
