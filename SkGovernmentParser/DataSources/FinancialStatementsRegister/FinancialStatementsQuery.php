<?php


namespace SkGovernmentParser\DataSources\FinancialStatementsRegister;


use SkGovernmentParser\DataSources\BusinessRegister\CompanyIdValidator;
use SkGovernmentParser\DataSources\FinancialStatementsRegister\Model\AccountingEntity;
use SkGovernmentParser\DataSources\FinancialStatementsRegister\Parser\AccountingEntityParser;
use SkGovernmentParser\DataSources\FinancialStatementsRegister\Provider\NetworkProvider;
use SkGovernmentParser\Exceptions\InvalidQueryException;
use SkGovernmentParser\Helper\StringHelper;
use SkGovernmentParser\ParserConfiguration;

class FinancialStatementsQuery
{
    private FinancialStatementsDataProvider $Provider;

    public function __construct(FinancialStatementsDataProvider $provider)
    {
        $this->Provider = $provider;
    }

    # ~

    public static function network(): FinancialStatementsQuery
    {
        return new FinancialStatementsQuery(new NetworkProvider(ParserConfiguration::$FinancialStatementsUrlRoot));
    }

    # ~

    public function byIdentificator(string $identificator): AccountingEntity
    {
        $sanetisedIdentificator = StringHelper::removeWhitespaces($identificator);

        if (!CompanyIdValidator::isValid($sanetisedIdentificator)) {
            throw new InvalidQueryException("Provided identificator [$identificator] is not valid company identificator!");
        }

        $companyObject = $this->Provider->getSubjectJsonByIdentificator($sanetisedIdentificator);
        $parsedSubject = AccountingEntityParser::parseObject($companyObject);

        return $parsedSubject;
    }

    public function byName()
    {

    }
}
