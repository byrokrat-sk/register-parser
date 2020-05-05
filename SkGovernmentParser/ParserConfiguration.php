<?php

namespace SkGovernmentParser;

class ParserConfiguration
{

    /* Maximal request timeout in seconds */
    public static $RequestTimeoutSeconds = 10;


    #
    # Business Register Configuration
    #


    /* Root URL address of business register */
    public static $BusinessRegisterUrlRoot = 'http://orsr.sk';

    /*
     * There **are** relevant cases with multiple subjects when searching by "unique" identifier. For example when you
     * move your company to different district court (changing headquarters city), your id stay the same but there will
     * be created new entity in business register and old one will be marked as inactive but displayed duplicit.
     */
    public static $BusinessRegisterAllowMultipleIdsResult = true;


    #
    # Trade Register Configuration
    #


    /* Root URL address of trade register */
    public static $TradeRegisterUrlRoot = 'https://www.zrsr.sk';


    #
    # Financial Agent Register Configuration
    #


    public static $FinancialAgentRegisterUrlRoot = 'https://regfap.nbs.sk';


    #
    # $Financial Statements Register
    #

    public static $FinancialStatementsUrlRoot = 'http://www.registeruz.sk/cruz-public/api';

}
