<?php

namespace Contal;

use Contal\Fmt\Core\CodeFormatter;
use Contal\Fmt\PSR\PsrDecorator;

define('ST_AT', '@');
define('ST_BRACKET_CLOSE', ']');
define('ST_BRACKET_OPEN', '[');
define('ST_COLON', ':');
define('ST_COMMA', ',');
define('ST_CONCAT', '.');
define('ST_CURLY_CLOSE', '}');
define('ST_CURLY_OPEN', '{');
define('ST_DIVIDE', '/');
define('ST_DOLLAR', '$');
define('ST_EQUAL', '=');
define('ST_EXCLAMATION', '!');
define('ST_IS_GREATER', '>');
define('ST_IS_SMALLER', '<');
define('ST_MINUS', '-');
define('ST_MODULUS', '%');
define('ST_PARENTHESES_CLOSE', ')');
define('ST_PARENTHESES_OPEN', '(');
define('ST_PLUS', '+');
define('ST_QUESTION', '?');
define('ST_QUOTE', '"');
define('ST_REFERENCE', '&');
define('ST_SEMI_COLON', ';');
define('ST_TIMES', '*');
define('ST_BITWISE_OR', '|');
define('ST_BITWISE_XOR', '^');

if (!defined('T_POW')) {
	define('T_POW', '**');
}

if (!defined('T_POW_EQUAL')) {
	define('T_POW_EQUAL', '**=');
}

if (!defined('T_YIELD')) {
	define('T_YIELD', 'yield');
}

if (!defined('T_FINALLY')) {
	define('T_FINALLY', 'finally');
}

if (!defined('T_SPACESHIP')) {
	define('T_SPACESHIP', '<=>');
}

if (!defined('T_COALESCE')) {
	define('T_COALESCE', '??');
}
define('ST_PARENTHESES_BLOCK', 'ST_PARENTHESES_BLOCK');
define('ST_BRACKET_BLOCK', 'ST_BRACKET_BLOCK');
define('ST_CURLY_BLOCK', 'ST_CURLY_BLOCK');
class Fmt {
	public static function run($opts, $file) {
		
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
			}
				, explode(',', $opts['passes']));
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
