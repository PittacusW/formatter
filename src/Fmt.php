<?php

namespace Contal;

//use Contal\Fmt\Csp;
//use Contal\Fmt\Helpers;
//use Contal\Fmt\Core\FormatterPass;
//use Contal\Fmt\Core\BaseCodeFormatter;
use Contal\Fmt\Core\CodeFormatter;
//use Contal\Fmt\Core\AddMissingCurlyBraces;
//use Contal\Fmt\Core\AutoImport;
//use Contal\Fmt\Core\ConstructorPass;
//use Contal\Fmt\Core\EliminateDuplicatedEmptyLines;
//use Contal\Fmt\Core\ExtraCommaInArray;
//use Contal\Fmt\Core\LeftAlignComment;
//use Contal\Fmt\Core\MergeCurlyCloseAndDoWhile;
//use Contal\Fmt\Core\MergeDoubleArrowAndArray;
//use Contal\Fmt\Core\MergeParenCloseWithCurlyOpen;
//use Contal\Fmt\Core\NormalizeIsNotEquals;
//use Contal\Fmt\Core\NormalizeLnAndLtrimLines;
//use Contal\Fmt\Core\Reindent;
//use Contal\Fmt\Core\ReindentColonBlocks;
//use Contal\Fmt\Core\ReindentComments;
//use Contal\Fmt\Core\RemoveComments;
//use Contal\Fmt\Core\ReindentEqual;
//use Contal\Fmt\Core\ReindentObjOps;
//use Contal\Fmt\Core\ResizeSpaces;
//use Contal\Fmt\Core\RTrim;
//use Contal\Fmt\Core\SettersAndGettersPass;
//use Contal\Fmt\Core\SplitCurlyCloseAndTokens;
//use Contal\Fmt\Core\StripExtraCommaInList;
//use Contal\Fmt\Core\SurrogateToken;
//use Contal\Fmt\Core\TwoCommandsInSameLine;
//use Contal\Fmt\PSR\PSR1BOMMark;
//use Contal\Fmt\PSR\PSR1ClassConstants;
//use Contal\Fmt\PSR\PSR1ClassNames;
//use Contal\Fmt\PSR\PSR1MethodNames;
//use Contal\Fmt\PSR\PSR1OpenTags;
//use Contal\Fmt\PSR\PSR2AlignObjOp;
//use Contal\Fmt\PSR\PSR2CurlyOpenNextLine;
//use Contal\Fmt\PSR\PSR2IndentWithSpace;
//use Contal\Fmt\PSR\PSR2KeywordsLowerCase;
//use Contal\Fmt\PSR\PSR2LnAfterNamespace;
//use Contal\Fmt\PSR\PSR2ModifierVisibilityStaticOrder;
//use Contal\Fmt\PSR\PSR2SingleEmptyLineAndStripClosingTag;
//use Contal\Fmt\PSR\PsrDecorator;
//use Contal\Fmt\Additionals\AdditionalPass;
//use Contal\Fmt\Additionals\AddMissingParentheses;
//use Contal\Fmt\Additionals\AliasToMaster;
//use Contal\Fmt\Additionals\AlignConstVisibilityEquals;
//use Contal\Fmt\Additionals\AlignDoubleArrow;
//use Contal\Fmt\Additionals\AlignDoubleSlashComments;
//use Contal\Fmt\Additionals\AlignEquals;
//use Contal\Fmt\Additionals\AlignGroupDoubleArrow;
//use Contal\Fmt\Additionals\AlignPHPCode;
//use Contal\Fmt\Additionals\AlignTypehint;
//use Contal\Fmt\Additionals\AllmanStyleBraces;
//use Contal\Fmt\Additionals\AutoPreincrement;
//use Contal\Fmt\Additionals\AutoSemicolon;
//use Contal\Fmt\Additionals\CakePHPStyle;
//use Contal\Fmt\Additionals\ClassToSelf;
//use Contal\Fmt\Additionals\ClassToStatic;
//use Contal\Fmt\Additionals\ConvertOpenTagWithEcho;
//use Contal\Fmt\Additionals\DocBlockToComment;
//use Contal\Fmt\Additionals\DoubleToSingleQuote;
//use Contal\Fmt\Additionals\EchoToPrint;
//use Contal\Fmt\Additionals\EncapsulateNamespaces;
//use Contal\Fmt\Additionals\GeneratePHPDoc;
//use Contal\Fmt\Additionals\IndentTernaryConditions;
//use Contal\Fmt\Additionals\JoinToImplode;
//use Contal\Fmt\Additionals\LeftWordWrap;
//use Contal\Fmt\Additionals\LongArray;
//use Contal\Fmt\Additionals\MergeElseIf;
//use Contal\Fmt\Additionals\SplitElseIf;
//use Contal\Fmt\Additionals\MergeNamespaceWithOpenTag;
//use Contal\Fmt\Additionals\MildAutoPreincrement;
//use Contal\Fmt\Additionals\NewLineBeforeReturn;
//use Contal\Fmt\Additionals\NoSpaceAfterPHPDocBlocks;
//use Contal\Fmt\Additionals\OrganizeClass;
//use Contal\Fmt\Additionals\OrderAndRemoveUseClauses;
//use Contal\Fmt\Additionals\OnlyOrderUseClauses;
//use Contal\Fmt\Additionals\OrderMethod;
//use Contal\Fmt\Additionals\OrderMethodAndVisibility;
//use Contal\Fmt\Additionals\PHPDocTypesToFunctionTypehint;
//use Contal\Fmt\Additionals\PrettyPrintDocBlocks;
//use Contal\Fmt\Additionals\PSR2EmptyFunction;
//use Contal\Fmt\Additionals\PSR2MultilineFunctionParams;
//use Contal\Fmt\Additionals\ReindentAndAlignObjOps;
//use Contal\Fmt\Additionals\ReindentSwitchBlocks;
//use Contal\Fmt\Additionals\RemoveIncludeParentheses;
//use Contal\Fmt\Additionals\RemoveSemicolonAfterCurly;
//use Contal\Fmt\Additionals\RemoveUseLeadingSlash;
//use Contal\Fmt\Additionals\ReplaceBooleanAndOr;
//use Contal\Fmt\Additionals\ReplaceIsNull;
//use Contal\Fmt\Additionals\RestoreComments;
//use Contal\Fmt\Additionals\ReturnNull;
//use Contal\Fmt\Additionals\ShortArray;
//use Contal\Fmt\Additionals\SmartLnAfterCurlyOpen;
//use Contal\Fmt\Additionals\SortUseNameSpace;
//use Contal\Fmt\Additionals\SpaceAroundControlStructures;
//use Contal\Fmt\Additionals\SpaceAroundExclamationMark;
//use Contal\Fmt\Additionals\SpaceBetweenMethods;
//use Contal\Fmt\Additionals\StrictBehavior;
//use Contal\Fmt\Additionals\StrictComparison;
//use Contal\Fmt\Additionals\StripExtraCommaInArray;
//use Contal\Fmt\Additionals\StripNewlineAfterClassOpen;
//use Contal\Fmt\Additionals\StripNewlineAfterCurlyOpen;
//use Contal\Fmt\Additionals\StripNewlineWithinClassBody;
//use Contal\Fmt\Additionals\StripSpaces;
//use Contal\Fmt\Additionals\StripSpaceWithinControlStructures;
//use Contal\Fmt\Additionals\TightConcat;
//use Contal\Fmt\Additionals\TrimSpaceBeforeSemicolon;
//use Contal\Fmt\Additionals\UpgradeToPreg;
//use Contal\Fmt\Additionals\WordWrap;
//use Contal\Fmt\Additionals\WrongConstructorName;
//use Contal\Fmt\Additionals\YodaComparisons;
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
