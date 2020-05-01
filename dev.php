<?php

// Global debug function
function dd($val, $json = false) {
    if ($json) {
        die(json_encode($val, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    } else {
        print_r($val); die("\n");
    }
}

# ~

require_once __DIR__.'/vendor/autoload.php';

use \SkGovernmentParser\DataSources\FinancialAgentRegister\FinancialAgentRegisterQuery;
use SkGovernmentParser\Exceptions\EmptySearchResultException;

# ~

const CIKES_NUMBER = '235741';
const FINGO_NUMBER = '215683';
const BITTARA_NUMBER = '235784';

# ~

/*function readNumbers(): Iterator
{
    $inputFile = fopen(__DIR__.'/numbers.txt', 'r');

    while (($line = fgets($inputFile)) !== false) {
        yield trim($line);
    }

    fclose($inputFile);
}

$register = FinancialAgentRegisterQuery::network();

$successes = 0;
$logFile = fopen('log.txt', 'a');
foreach (readNumbers() as $id) {
    try {
        $financialAgent = $register->byNumber($id);
        $fileName = str_pad($id, 6, '0', STR_PAD_LEFT);
        $level_1 = $fileName[5];
        $level_2 = $fileName[4];
        $level_3 = $fileName[3];

        $directory = __DIR__.'/agents/'.$level_1.'/'.$level_2.'/'.$level_3;
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        file_put_contents($directory.'/'.$id.'.json', json_encode($financialAgent, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        echo ("Success: $id\n");
        $successes += 1;
    } catch (EmptySearchResultException $exception) {
        echo ("Not found: $id\n");
    } catch(\SkGovernmentParser\Exceptions\InvalidQueryException $exception) {
        echo ("Invalid query: $id\n");
    } catch (\Throwable $throwable) {
        echo ("Failed: $id\n");
        fwrite($logFile, "$id\n");
    }
}

fclose($logFile);
echo ("Download done! Downloaded: $successes agents!\n");*/


$queryResult = FinancialAgentRegisterQuery::network()->byNumber('43552');
echo(json_encode($queryResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
