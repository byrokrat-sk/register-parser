<?php


namespace ByrokratSk;


class Configuration
{
    /** @var int Maximal request timeout in seconds */
    public int $RequestTimeoutSeconds;

    /** @var string Root URL address of business register */
    public string $BusinessRegisterUrlRoot;

    /** @var bool here **are** relevant cases with multiple subjects when searching by "unique" identifier. For example when you
     * move your company to different district court (changing headquarters city), your id stay the same but there will
     * be created new entity in business register and old one will be marked as inactive but displayed duplicit.
     */
    public bool $BusinessRegisterAllowMultipleIdsResult;

    /** @var bool allow multiple results in trade register */
    public bool $TradeRegisterAllowMultipleIdsResult;

    /** @var string Root URL address of trade register */
    public string $TradeRegisterUrlRoot;

    /** @var string Root URL address of financial agent register */
    public string $FinancialAgentRegisterUrlRoot;

    /** @var string Root URL of financial statements register API */
    public string $FinancialStatementsUrlRoot;

    /**
     * Configuration constructor.
     * @param int $requestTimeoutSeconds
     * @param string $businessRegisterUrlRoot
     * @param bool $businessRegisterAllowMultipleIdsResult
     * @param bool $tradeRegisterAllowMultipleIdsResult
     * @param string $tradeRegisterUrlRoot
     * @param string $financialAgentRegisterUrlRoot
     * @param string $financialStatementsUrlRoot
     */
    public function __construct(
        int $requestTimeoutSeconds,
        string $businessRegisterUrlRoot,
        bool $businessRegisterAllowMultipleIdsResult,
        bool $tradeRegisterAllowMultipleIdsResult,
        string $tradeRegisterUrlRoot,
        string $financialAgentRegisterUrlRoot,
        string $financialStatementsUrlRoot
    )
    {
        $this->RequestTimeoutSeconds = $requestTimeoutSeconds;
        $this->BusinessRegisterUrlRoot = $businessRegisterUrlRoot;
        $this->BusinessRegisterAllowMultipleIdsResult = $businessRegisterAllowMultipleIdsResult;
        $this->TradeRegisterAllowMultipleIdsResult = $tradeRegisterAllowMultipleIdsResult;
        $this->TradeRegisterUrlRoot = $tradeRegisterUrlRoot;
        $this->FinancialAgentRegisterUrlRoot = $financialAgentRegisterUrlRoot;
        $this->FinancialStatementsUrlRoot = $financialStatementsUrlRoot;
    }

    /** Returns default configuration */
    public static function getDefault(): self
    {
        return new self(
            10,
            'http://orsr.sk',
            true,
            true,
            'https://www.zrsr.sk',
            'https://regfap.nbs.sk',
            'http://www.registeruz.sk/cruz-public/api'
        );
    }
}
