<?php

namespace SkGovernmentParser;

class ParserConfiguration
{
    /*
     * Maximal request timeout in seconds
     */
    public static $RequestTimeoutSeconds = 10;

    /*
     * Some URL addresses parsed from HTML are not prefixed. This string is placed on beggining of the relative URL
     * addresses.
     */
    public static $BusinessRegisterUrlRoot = 'http://orsr.sk';

    /*
     * There **are** relevant cases with multiple subjects when searching by "unique" identifier. For example when you
     * move your company to different district court (changing headquarters city), your id stay the same but there will
     * be created new entity in business register and old one will be marked as inactive but displayed duplicit.
     */
    public static $BusinessRegisterAllowMultipleIdsResult = true;

    # ~

    public static $TradeRegisterUrlRoot = 'https://www.zrsr.sk';
}
