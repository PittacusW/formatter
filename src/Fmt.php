<?php

class Fmt {

        public static function run($opts, $file)
        {
            {

                $fmt = new CodeFormatter();
                if (isset($opts['setters_and_getters'])) {
                    $fmt->enablePass('SettersAndGettersPass', $opts['setters_and_getters']);
                }

                if (isset($opts['constructor'])) {
                    $fmt->enablePass('ConstructorPass', $opts['constructor']);
                }

                if (isset($opts['oracleDB'])) {
                    if ('scan' == $opts['oracleDB']) {
                        $oracle = getcwd() . DIRECTORY_SEPARATOR . 'oracle.sqlite';
                        $lastoracle = '';
                        while (!is_file($oracle) && $lastoracle != $oracle) {
                            $lastoracle = $oracle;
                            $oracle = dirname(dirname($oracle)) . DIRECTORY_SEPARATOR . 'oracle.sqlite';
                        }
                        $opts['oracleDB'] = $oracle;
                        fwrite(STDERR, PHP_EOL);
                    }

                    if (file_exists($opts['oracleDB']) && is_file($opts['oracleDB'])) {
                        $fmt->enablePass('AutoImportPass', $opts['oracleDB']);
                    }
                }

                if (isset($opts['smart_linebreak_after_curly'])) {
                    $fmt->enablePass('SmartLnAfterCurlyOpen');
                }

                if (isset($opts['remove_comments'])) {
                    $fmt->enablePass('RemoveComments');
                }

                if (isset($opts['yoda'])) {
                    $fmt->enablePass('YodaComparisons');
                }

                if (isset($opts['enable_auto_align'])) {
                    $fmt->enablePass('AlignEquals');
                    $fmt->enablePass('AlignDoubleArrow');
                }

                if (isset($opts['psr'])) {
                    PsrDecorator::decorate($fmt);
                }

                if (isset($opts['psr1'])) {
                    PsrDecorator::PSR1($fmt);
                }

                if (isset($opts['psr1-naming'])) {
                    PsrDecorator::PSR1Naming($fmt);
                }

                if (isset($opts['psr2'])) {
                    PsrDecorator::PSR2($fmt);
                }

                if (isset($opts['indent_with_space'])) {
                    $fmt->enablePass('PSR2IndentWithSpace', $opts['indent_with_space']);
                }

                if ((isset($opts['psr1']) || isset($opts['psr2']) || isset($opts['psr'])) && isset($opts['enable_auto_align'])) {
                    $fmt->enablePass('PSR2AlignObjOp');
                }

                if (isset($opts['visibility_order'])) {
                    $fmt->enablePass('PSR2ModifierVisibilityStaticOrder');
                }

                if (isset($opts['passes'])) {
                    $optPasses = array_map(function ($v) {
                        return trim($v);
                    }, explode(',', $opts['passes']));
                    foreach ($optPasses as $optPass) {
                        $fmt->enablePass($optPass);
                    }
                }

                if (isset($opts['cakephp'])) {
                    $fmt->enablePass('CakePHPStyle');
                }

                if (isset($opts['php2go'])) {
                    Php2GoDecorator::decorate($fmt);
                }

                if (isset($opts['exclude'])) {
                    $passesNames = explode(',', $opts['exclude']);
                    foreach ($passesNames as $passName) {
                        $fmt->disablePass(trim($passName));
                    }
                }

                if (isset($opts['v'])) {
                    fwrite(STDERR, 'Used passes: ' . implode(', ', $fmt->getPassesNames()) . PHP_EOL);
                }

                return $fmt->formatCode($file);


            }
        }
    

