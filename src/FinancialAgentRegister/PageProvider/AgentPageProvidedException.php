<?php


namespace SkGovernmentParser\FinancialAgentRegister\PageProvider;


class AgentPageProvidedException extends \RuntimeException
{
    public string $AgentPageHtml;

    public function __construct(string $message, $AgentPageHtml)
    {
        parent::__construct($message);
        $this->AgentPageHtml = $AgentPageHtml;
    }
}
