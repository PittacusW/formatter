<?php
namespace Contal;

use Contal\BeautifierInterface;
use Contal\Common;
use Contal\Decorator;
use Contal\ExceptionFilter;
use Contal\Beautifier;
use Contal\Filter;
use Contal\Tokenizer;

if (!extension_loaded('tokenizer')) {
	throw new Exception("Compile php with tokenizer extension. Use --enable-tokenizer or don't use --disable-all on configure.");
}

class Beautifier implements BeautifierInterface {

	
	public $aTokens           = array();
	
	public $aTokenFunctions   = array();
	
	public $aTokenNames       = Array();
	
	public $aOut              = array();
	
	public $aModes            = array();
	
	public $aModesAvailable   = array(
		'ternary_operator',
		'double_quote'
	);
	
	public $aSettings         = array();
	
	public $iCount            = 0;
	
	public $iIndentNumber     = 4;
	
	public $iArray            = 0;
	
	public $iTernary          = 0;
	
	public $iParenthesis      = 0;
	
	public $iVerbose          = false;
	
	public $sInputFile        = '';
	
	public $sOutputFile       = '';
	
	public $sNewLine          = PHP_EOL;
	
	public $sIndentChar       = ' ';
	
	public $currentWhitespace = '';
	
	public $aAssocs           = array();
	
	public $aCurrentToken     = array();
	
	private $sFileType         = 'php';
	
	private $iIndent           = 0;
	
	private $aIndentStack      = array();
	
	private $sText             = '';
	
	private $iControlLast;
	
	private $aFilters              = array();
	
	private $aControlSeq           = array();
	
	private $aControlStructures    = array();
	
	private $aControlParenthesis   = array();
	
	private $aControlStructuresEnd = array();
	
	private $aFilterDirs           = array();
	
	private $bBeautify             = true;
	
	private $sBeforeNewLine        = null;
	
	private $bNdps                 = false;
	
	private $doWhileBeginEnd;
	
	public function __construct() {
		$this->aControlStructures    = array(
			T_CLASS,
			T_FUNCTION,
			T_IF,
			T_ELSE,
			T_ELSEIF,
			T_WHILE,
			T_DO,
			T_FOR,
			T_FOREACH,
			T_SWITCH,
			T_DECLARE,
			T_TRY,
			T_CATCH
		);
		$this->aControlStructuresEnd = array(
			T_ENDWHILE,
			T_ENDFOREACH,
			T_ENDFOR,
			T_ENDDECLARE,
			T_ENDSWITCH,
			T_ENDIF
		);
		$aPreTokens                  = preg_grep('/^T_/', array_keys(get_defined_constants()));
		
		foreach ($aPreTokens as $sToken) {
			$this->aTokenNames[constant($sToken) ]                             = $sToken;
			$this->aTokenFunctions[constant($sToken) ]                             = $sToken;
		}
		$aTokensToChange             = array(
			'"'                             => "T_DOUBLE_QUOTE",
			"'"                             => "T_SINGLE_QUOTE",
			'('                             => 'T_PARENTHESIS_OPEN',
			')'                             => 'T_PARENTHESIS_CLOSE',
			';'                             => 'T_SEMI_COLON',
			'{'                             => 'T_OPEN_BRACE',
			'}'                             => 'T_CLOSE_BRACE',
			','                             => 'T_COMMA',
			'?'                             => 'T_QUESTION',
			':'                             => 'T_COLON',
			'='                             => 'T_ASSIGMENT',
			'<'                             => 'T_EQUAL',
			'>'                             => 'T_EQUAL',
			'.'                             => 'T_DOT',
			'['                             => 'T_OPEN_SQUARE_BRACE',
			']'                             => 'T_CLOSE_SQUARE_BRACE',
			'+'                             => 'T_OPERATOR',
			'-'                             => 'T_OPERATOR',
			'*'                             => 'T_OPERATOR',
			'/'                             => 'T_OPERATOR',
			'%'                             => 'T_OPERATOR',
			'&'                             => 'T_OPERATOR',
			'|'                             => 'T_OPERATOR',
			'^'                             => 'T_OPERATOR',
			'~'                             => 'T_OPERATOR',
			'!'                             => 'T_OPERATOR_NEGATION',
			T_SL                         => 'T_OPERATOR',
			T_SR                         => 'T_OPERATOR',
			T_OBJECT_OPERATOR            => 'T_OBJECT_OPERATOR',
			T_INCLUDE                    => 'T_INCLUDE',
			T_INCLUDE_ONCE               => 'T_INCLUDE',
			T_REQUIRE                    => 'T_INCLUDE',
			T_REQUIRE_ONCE               => 'T_INCLUDE',
			T_FUNCTION                   => 'T_LANGUAGE_CONSTRUCT',
			T_PRINT                      => 'T_LANGUAGE_CONSTRUCT',
			T_RETURN                     => 'T_LANGUAGE_CONSTRUCT',
			T_ECHO                       => 'T_LANGUAGE_CONSTRUCT',
			T_NEW                        => 'T_LANGUAGE_CONSTRUCT',
			T_CLASS                      => 'T_LANGUAGE_CONSTRUCT',
			T_VAR                        => 'T_LANGUAGE_CONSTRUCT',
			T_GLOBAL                     => 'T_LANGUAGE_CONSTRUCT',
			T_THROW                      => 'T_LANGUAGE_CONSTRUCT',
			T_IF                         => 'T_CONTROL',
			T_DO                         => 'T_CONTROL',
			T_WHILE                      => 'T_CONTROL',
			T_SWITCH                     => 'T_CONTROL',
			T_ELSEIF                     => 'T_ELSE',
			T_ELSE                       => 'T_ELSE',
			T_INTERFACE                  => 'T_ACCESS',
			T_FINAL                      => 'T_ACCESS',
			T_ABSTRACT                   => 'T_ACCESS',
			T_PRIVATE                    => 'T_ACCESS',
			T_PUBLIC                     => 'T_ACCESS',
			T_PROTECTED                  => 'T_ACCESS',
			T_CONST                      => 'T_ACCESS',
			T_STATIC                     => 'T_ACCESS',
			T_PLUS_EQUAL                 => 'T_ASSIGMENT_PRE',
			T_MINUS_EQUAL                => 'T_ASSIGMENT_PRE',
			T_MUL_EQUAL                  => 'T_ASSIGMENT_PRE',
			T_DIV_EQUAL                  => 'T_ASSIGMENT_PRE',
			T_CONCAT_EQUAL               => 'T_ASSIGMENT_PRE',
			T_MOD_EQUAL                  => 'T_ASSIGMENT_PRE',
			T_AND_EQUAL                  => 'T_ASSIGMENT_PRE',
			T_OR_EQUAL                   => 'T_ASSIGMENT_PRE',
			T_XOR_EQUAL                  => 'T_ASSIGMENT_PRE',
			T_DOUBLE_ARROW               => 'T_ASSIGMENT',
			T_SL_EQUAL                   => 'T_EQUAL',
			T_SR_EQUAL                   => 'T_EQUAL',
			T_IS_EQUAL                   => 'T_EQUAL',
			T_IS_NOT_EQUAL               => 'T_EQUAL',
			T_IS_IDENTICAL               => 'T_EQUAL',
			T_IS_NOT_IDENTICAL           => 'T_EQUAL',
			T_IS_SMALLER_OR_EQUAL        => 'T_EQUAL',
			T_IS_GREATER_OR_EQUAL        => 'T_EQUAL',
			T_LOGICAL_OR                 => 'T_LOGICAL',
			T_LOGICAL_XOR                => 'T_LOGICAL',
			T_LOGICAL_AND                => 'T_LOGICAL',
			T_BOOLEAN_OR                 => 'T_LOGICAL',
			T_BOOLEAN_AND                => 'T_LOGICAL',
			T_ENDWHILE                   => 'T_END_SUFFIX',
			T_ENDFOREACH                 => 'T_END_SUFFIX',
			T_ENDFOR                     => 'T_END_SUFFIX',
			T_ENDDECLARE                 => 'T_END_SUFFIX',
			T_ENDSWITCH                  => 'T_END_SUFFIX',
			T_ENDIF                      => 'T_END_SUFFIX',
			T_NAMESPACE                  => 'T_INCLUDE',
			T_USE                        => 'T_INCLUDE',
		);
		
		foreach ($aTokensToChange as $iToken                      => $sFunction) {
			$this->aTokenFunctions[$iToken]                             = $sFunction;
		}
		$this->addFilterDirectory(__DIR__);
		$this->addFilter('Default');
	}
	
	public function getTokenName($iToken) {
		
		if (!$iToken) {
			throw new Exception("Token $iToken doesn't exists");
		}
		return $this->aTokenNames[$iToken];
	}
	
	public function addFilterDirectory($sDir) {
		$sDir = Common::normalizeDir($sDir);
		
		if (file_exists($sDir)) {
			array_push($this->aFilterDirs, $sDir);
		}

		else {
			throw new ExceptionFilter("Path '$sDir' doesn't exists");
		}
	}
	
	public function getFilterDirectories() {
		return $this->aFilterDirs;
	}
	
	public function addFilterObject(Filter $oFilter) {
		array_unshift($this->aFilters, $oFilter);
		return true;
	}
	
	public function addFilter($mFilter, $aSettings    = array()) {
		
		if ($mFilter instanceOf Filter) {
			return $this->addFilterObject($mFilter);
		}
		$sFilterClass = 'Contal\\' . $mFilter . 'Filter';
		
		if (!class_exists($sFilterClass)) {
			$this->addFilterFile($mFilter);
		}
		$oTemp = new $sFilterClass($this, $aSettings);
		
		if (in_array($oTemp, $this->aFilters, TRUE)) {
			return false;
		}
		elseif ($oTemp instanceof Filter) {
			$this->addFilterObject($oTemp);
		}

		else {
			throw new ExceptionFilter("'$sFilterClass' isn't a subclass of 'Filter'");
		}
	}
	
	public function removeFilter($sFilter) {
		$sFilterName = 'Contal\\' . $sFilter . 'Filter';
		
		foreach ($this->aFilters as $sId => $oFilter) {
			
			if (strtolower(get_class($oFilter)) == $sFilterName) {
				unset($this->aFilters[$sId]);
				return true;
			}
		}
		return false;
	}
	
	public function getFilterDescription($sFilter) {
		$aFilters = $this->getFilterListTotal();
		
		if (in_array($sFilter, $aFilters)) {
			$this->addFilterFile($sFilter);
			$sFilterClass = 'Contal\\' . $sFilter . 'Filter';
			$oTemp        = new $sFilterClass($this, array());
			return $oTemp;
		}

		else {
			return false;
		}
	}
	
	private function addFilterFile($sFilter) {
		$sFilterClass = 'Contal\\' . $sFilter . 'Filter';
		
		if (class_exists($sFilterClass)) {
			return true;
		}
		
		foreach ($this->aFilterDirs as $sDir) {
			$sFile = $sDir . $sFilter . 'Filter.php';
			
			if (file_exists($sFile)) {
				
				if (class_exists($sFilterClass)) {
					return true;
				}

				else {
					throw new ExceptionFilter("File '$sFile' exists,but doesn't exists filter '$sFilterClass'");
				}
			}
		}
		throw new ExceptionFilter("Doesn't exists filter '$sFilter'");
	}
	
	public function getFilterList() {
		
		foreach ($this->aFilters as $oFilter) {
			$aOut[] = $oFilter->getName();
		}
		return $aOut;
	}
	
	public function getFilterListTotal() {
		$aFilterFiles = array();
		
		foreach ($this->aFilterDirs as $sDir) {
			$aFiles       = Common::getFilesByPattern($sDir, ".*?\.filter\.php");
			array_walk($aFiles, array(
				$this,
				'getFilterList_FilterName'
			));
			$aFilterFiles = array_merge($aFilterFiles, $aFiles);
		}
		sort($aFilterFiles);
		return $aFilterFiles;
	}
	
	private function getFilterList_FilterName(&$sFile) {
		$aMatch = array();
		preg_match("/\/([^\/]*?)\.filter\.php/", $sFile, $aMatch);
		$sFile = $aMatch[1];
	}
	
	public function getIndentChar() {
		return $this->sIndentChar;
	}
	
	public function getIndentNumber() {
		return $this->iIndentNumber;
	}
	
	public function getIndent() {
		return $this->iIndent;
	}
	
	public function getNewLine() {
		return $this->sNewLine;
	}
	
	public function setIndentChar($sChar) {
		$this->sIndentChar   = $sChar;
	}
	
	public function setIndentNumber($iIndentNumber) {
		$this->iIndentNumber = $iIndentNumber;
	}
	
	public function setNewLine($sNewLine) {
		$this->sNewLine      = $sNewLine;
	}
	
	public function setInputFile($sFile) {
		$bCli                = (php_sapi_name() == 'cli');
		
		if (strpos($sFile, '://') === FALSE and !file_exists($sFile) and !($bCli and $sFile == STDIN)) {
			throw new Exception("File '$sFile' doesn't exists");
		}
		$this->sText      = '';
		$this->sInputFile = $sFile;
		$fp               = ($bCli and $sFile == STDIN) ? STDIN : fopen($sFile, 'r');
		do {
			$data             = fread($fp, 8192);
			
			if (strlen($data) == 0) {
				break;
			}
			$this->sText.= $data;
		} while (true);
		
		if (!($bCli and $fp == STDIN)) {
			fclose($fp);
		}
		return true;
	}
	
	public function setOutputFile($sFile) {
		$this->sOutputFile = $sFile;
	}
	
	public function save($sFile             = null) {
		$bCli              = (php_sapi_name() == 'cli');
		
		if (!$sFile) {
			
			if (!$this->sOutputFile) {
				throw new Exception("Can't save without a output file");
			}

			else {
				$sFile = $this->sOutputFile;
			}
		}
		$sText = $this->get();
		$fp    = ($bCli and $sFile == STDOUT) ? STDOUT : @fopen($sFile, "w");
		
		if (!$fp) {
			throw new Exception("Can't save file $sFile");
		}
		fputs($fp, $sText, strlen($sText));
		
		if (!($bCli and $sFile == STDOUT)) {
			fclose($fp);
		}
		return true;
	}
	
	public function setInputString($sText) {
		$this->sText      = $sText;
	}
	
	private function resetProperties() {
		$aProperties      = array(
			'aTokens'                  => array() ,
			'aOut'                  => array() ,
			'aModes'                  => array() ,
			'iCount'                  => 0,
			'iIndent'                  => 0,
			'aIndentStack'                  => array() ,
			'iArray'                  => 0,
			'iParenthesis'                  => 0,
			'currentWhitespace'                  => '',
			'aAssocs'                  => array() ,
			'iControlLast'                  => null,
			'aControlSeq'                  => array() ,
			'bBeautify'                  => true
		);
		
		foreach ($aProperties as $sProperty        => $sValue) {
			$this->$sProperty = $sValue;
		}
	}
	
	public function process() {
		$this->resetProperties();
		
		if ($this->sFileType == 'php') {
			$this->aTokens = token_get_all($this->sText);
		}

		else {
			$sClass        = 'Tokenizer_' . ucfirst($this->sFileType);
			
			if (class_exists($sClass)) {
				$oTokenizer    = new $sClass($this->sText);
				$this->aTokens = $oTokenizer->getTokens();
			}

			else {
				throw new Exception("File type " . $this->sFileType . " not implemented");
			}
		}
		$this->aOut = array();
		$iTotal     = count($this->aTokens);
		$iPrevAssoc = false;
		
		foreach ($this->aFilters as $oFilter) {
			$oFilter->preProcess();
		}
		for ($this->iCount = 0;$this->iCount < $iTotal;$this->iCount++) {
			$aCurrentToken = $this->aTokens[$this->iCount];
			
			if (is_string($aCurrentToken)) {
				$aCurrentToken = array(
					0               => $aCurrentToken,
					1               => $aCurrentToken
				);
			}
			$sTextLog      = Common::wsToString($aCurrentToken[1]);
			$sTokenName    = (is_numeric($aCurrentToken[0])) ? token_name($aCurrentToken[0]) : '';
			$this->controlToken($aCurrentToken);
			$iFirstOut           = count($this->aOut);
			$bError              = false;
			$this->aCurrentToken = $aCurrentToken;
			
			if ($this->bBeautify) {
				
				foreach ($this->aFilters as $oFilter) {
					$bError              = true;
					
					if ($oFilter->handleToken($this->aCurrentToken) !== FALSE) {
						$bError              = false;
						break;
					}
				}
			}

			else {
				$this->add($aCurrentToken[1]);
			}
			$this->controlTokenPost($aCurrentToken);
			$iLastOut   = count($this->aOut);
			
			if (($iLastOut - $iFirstOut) > 0) {
				$this->aAssocs[$this->iCount]            = array(
					'offset'            => $iFirstOut
				);
				
				if ($iPrevAssoc !== FALSE) {
					$this->aAssocs[$iPrevAssoc]['length']            = $iFirstOut - $this->aAssocs[$iPrevAssoc]['offset'];
				}
				$iPrevAssoc = $this->iCount;
			}
			
			if ($bError) {
				throw new Exception("Can'process token: " . var_dump($aCurrentToken));
			}
		}
		
		if (count($this->aOut) == 0) {
			
			if ($this->sFile) {
				throw new Exception("Nothing on output for " . $this->sFile . "!");
			}

			else {
				throw new Exception("Nothing on output!");
			}
		}
		$this->aAssocs[$iPrevAssoc]['length'] = (count($this->aOut) - 1) - $this->aAssocs[$iPrevAssoc]['offset'];
		
		foreach ($this->aFilters as $oFilter) {
			$oFilter->postProcess();
		}
		return true;
	}
	
	public function getTokenAssoc($iIndex) {
		return (array_key_exists($iIndex, $this->aAssocs)) ? $this->aAssocs[$iIndex] : false;
	}
	
	public function getTokenAssocText($iIndex) {
		
		if (!($aAssoc = $this->getTokenAssoc($iIndex))) {
			return false;
		}
		return (implode('', array_slice($this->aOut, $aAssoc['offset'], $aAssoc['length'])));
	}
	
	public function replaceTokenAssoc($iIndex, $sText) {
		
		if (!($aAssoc = $this->getTokenAssoc($iIndex))) {
			return false;
		}
		$this->aOut[$aAssoc['offset']]        = $sText;
		for ($x      = 0;$x < $aAssoc['length'] - 1;$x++) {
			$this->aOut[$aAssoc['offset'] + $x + 1] = '';
		}
		return true;
	}
	
	public function getTokenFunction($sTokenType) {
		return (array_key_exists($sTokenType, $this->aTokenFunctions)) ? strtolower($this->aTokenFunctions[$sTokenType]) : false;
	}
	
	private function processCallback($aMatch) {
		
		if (stristr('php_beautifier', $aMatch[1]) and method_exists($this, $aMatch[3])) {
			
			if (preg_match("/^(set|add)/i", $aMatch[3]) and !stristr('file', $aMatch[3])) {
				eval('$this->' . $aMatch[2] . ";");
				return true;
			}
		}

		else {
			
			foreach ($this->aFilters as $iIndex => $oFilter) {
				
				if (strtolower(get_class($oFilter)) == 'php_beautifier_filter_' . strtolower($aMatch[1]) and method_exists($oFilter, $aMatch[3])) {
					eval('$this->aFilters[' . $iIndex . ']->' . $aMatch[2] . ";");
					return true;
				}
			}
		}
		return false;
	}
	
	private function controlToken($aCurrentToken) {
		
		if (in_array($aCurrentToken[0], $this->aControlStructures)) {
			$this->pushControlSeq($aCurrentToken);
			$this->iControlLast = $aCurrentToken[0];
		}
		
		if (in_array($aCurrentToken[0], $this->aControlStructuresEnd)) {
			$this->popControlSeq();
		}
		
		switch ($aCurrentToken[0]) {
			case T_COMMENT:
				$aMatch = array();
				
				if (preg_match("/\/\/\s*(.*?)->((.*)\((.*)\))/", $aCurrentToken[1], $aMatch)) {
					
					try {
						$this->processCallback($aMatch);
					}
					catch(Exception $oExp) {

					}
				}
			break;
			case T_FUNCTION:
				$this->setMode('function');
			break;
			case T_CLASS:
				$this->setMode('class');
			break;
			case T_ARRAY:
				$this->iArray++;
			break;
			case T_WHITESPACE:
				$this->currentWhitespace = $aCurrentToken[1];
			break;
			case '{':
				
				if ($this->isPreviousTokenConstant(T_VARIABLE) or ($this->isPreviousTokenConstant(T_STRING) and $this->getPreviousTokenConstant(2) == T_OBJECT_OPERATOR) or $this->isPreviousTokenConstant(T_OBJECT_OPERATOR)) {
					$this->setMode('string_index');
				}
			break;
			case '(':
				$this->iParenthesis++;
				$this->pushControlParenthesis();
			break;
			case ')':
				$this->iParenthesis--;
			break;
			case '?':
				$this->setMode('ternary_operator');
				$this->iTernary++;
			break;
			case '"':
				($this->getMode('double_quote')) ? $this->unsetMode('double_quote') : $this->setMode('double_quote');
			break;
			case T_START_HEREDOC:
				$this->setMode('double_quote');
			break;
			case T_END_HEREDOC:
				$this->unsetMode('double_quote');
			break;
		}
		
		if ($this->getTokenFunction($aCurrentToken[0]) == 't_include') {
			$this->setMode('include');
		}
	}
	
	private function controlTokenPost($aCurrentToken) {
		
		switch ($aCurrentToken[0]) {
			case ')':
				
				if ($this->iArray) {
					$this->iArray--;
				}
				$this->popControlParenthesis();
			break;
			case '}':
				
				if ($this->getMode('string_index')) {
					$this->unsetMode('string_index');
				}

				else {
					$prevIndex = 1;
					while ($this->isPreviousTokenConstant(array(
						T_COMMENT,
						T_DOC_COMMENT
					) , $prevIndex)) {
						$prevIndex++;
					}
					
					if ($this->isPreviousTokenContent(array(
						';',
						'}',
						'{'
					) , $prevIndex)) {
						
						if (end($this->aControlSeq) != T_DO) {
							$this->popControlSeq();
						}

						else {
							$this->DoWhileBeginEnd = true;
						}
					}
				}
			break;
			case ';':
				
				if (isset($this->aControlSeq) && (end($this->aControlSeq) == T_WHILE)) {
					$counter         = 0;
					$openParenthesis = 0;
					do {
						$counter++;
						$prevToken = $this->getPreviousTokenContent($counter);
						
						if ($prevToken == "(") {
							$openParenthesis++;
						}
					} while ($prevToken != "{" && $prevToken != "while");
					
					if ($prevToken == "while" && $openParenthesis == 1) {
						
						if ($this->DoWhileBeginEnd) {
							$this->popControlSeq();
							$this->DoWhileBeginEnd = false;
						}
						$this->popControlSeq();
					}
				}
			break;
			case '{':
				$this->unsetMode('function');
			break;
		}
		
		if ($this->getTokenFunction($aCurrentToken[0]) == 't_colon') {
			
			if ($this->iTernary) {
				$this->iTernary--;
			}
			
			if (!$this->iTernary) {
				$this->unsetMode('ternary_operator');
			}
		}
	}
	
	private function pushControlSeq($aToken) {
		array_push($this->aControlSeq, $aToken[0]);
	}
	
	private function popControlSeq() {
		$aEl = array_pop($this->aControlSeq);
		return $aEl;
	}
	
	private function pushControlParenthesis() {
		$iPrevious = $this->getPreviousTokenConstant();
		array_push($this->aControlParenthesis, $iPrevious);
	}
	
	private function popControlParenthesis() {
		$iPop = array_pop($this->aControlParenthesis);
		return $iPop;
	}
	
	public function setFileType($sType) {
		$this->sFileType = $sType;
	}
	
	public function setBeautify($sFlag) {
		$this->bBeautify = (bool)$sFlag;
	}
	
	public function show() {
		echo $this->get();
	}
	
	public function setNoDeletePreviousSpaceHack($bFlag       = true) {
		$this->bNdps = $bFlag;
	}
	
	public function get() {
		
		if (!$this->bNdps) {
			return implode('', $this->aOut);
		}

		else {
			return str_replace('', '', implode('', $this->aOut));
		}
	}
	
	public function getSetting($sKey) {
		return (array_key_exists($sKey, $this->aSettings)) ? $this->aSettings[$sKey] : false;
	}
	
	public function getControlSeq($iRet   = 0) {
		$iIndex = count($this->aControlSeq) - $iRet - 1;
		return ($iIndex >= 0) ? $this->aControlSeq[$iIndex] : false;
	}
	
	public function getControlParenthesis($iRet   = 0) {
		$iIndex = count($this->aControlParenthesis) - $iRet - 1;
		return ($iIndex >= 0) ? $this->aControlParenthesis[$iIndex] : false;
	}
	
	public function setMode($sKey) {
		$this->aModes[$sKey] = true;
	}
	
	public function unsetMode($sKey) {
		$this->aModes[$sKey] = false;
	}
	
	public function getMode($sKey) {
		return array_key_exists($sKey, $this->aModes) ? $this->aModes[$sKey] : false;
	}
	
	public function add($token) {
		$this->aOut[]       = $token;
	}
	
	public function pop($iReps = 1) {
		for ($x     = 0;$x < $iReps;$x++) {
			$sLast = array_pop($this->aOut);
		}
		return $sLast;
	}
	
	public function addIndent() {
		$this->aOut[]                      = str_repeat($this->sIndentChar, $this->iIndent);
	}
	
	public function setBeforeNewLine($sText) {
		$this->sBeforeNewLine = $sText;
	}
	
	public function addNewLine() {
		
		if (!is_null($this->sBeforeNewLine)) {
			$this->aOut[]                      = $this->sBeforeNewLine;
			$this->sBeforeNewLine = null;
		}
		$this->aOut[]                      = $this->sNewLine;
	}
	
	public function addNewLineIndent() {
		
		if (!is_null($this->sBeforeNewLine)) {
			$this->aOut[]                      = $this->sBeforeNewLine;
			$this->sBeforeNewLine = null;
		}
		$this->aOut[]                      = $this->sNewLine;
		$this->aOut[]                      = str_repeat($this->sIndentChar, $this->iIndent);
	}
	
	public function incIndent($iIncr                = false) {
		
		if (!$iIncr) {
			$iIncr                = $this->iIndentNumber;
		}
		array_push($this->aIndentStack, $iIncr);
		$this->iIndent+= $iIncr;
	}
	
	public function decIndent() {
		
		if (count($this->aIndentStack > 1)) {
			$iLastIndent = array_pop($this->aIndentStack);
			$this->iIndent-= $iLastIndent;
		}
	}
	
	private function getPreviousToken($iPrev = 1) {
		for ($x     = $this->iCount - 1;$x >= 0;$x--) {
			$aToken = & $this->getToken($x);
			
			if ($aToken[0] != T_WHITESPACE) {
				$iPrev--;
				
				if (!$iPrev) {
					return $aToken;
				}
			}
		}
	}
	
	private function getNextToken($iNext = 1) {
		for ($x     = $this->iCount + 1;$x < (count($this->aTokens) - 1);$x++) {
			$aToken = & $this->getToken($x);
			
			if ($aToken[0] != T_WHITESPACE) {
				$iNext--;
				
				if (!$iNext) {
					return $aToken;
				}
			}
		}
	}
	
	public function isPreviousTokenConstant($mValue, $iPrev     = 1) {
		
		if (!is_array($mValue)) {
			$mValue    = array(
				$mValue
			);
		}
		$iPrevious = $this->getPreviousTokenConstant($iPrev);
		return in_array($iPrevious, $mValue);
	}
	
	public function isPreviousTokenContent($mValue, $iPrev     = 1) {
		
		if (!is_array($mValue)) {
			$mValue    = array(
				$mValue
			);
		}
		$iPrevious = $this->getPreviousTokenContent($iPrev);
		return in_array($iPrevious, $mValue);
	}
	
	public function isNextTokenConstant($mValue, $iPrev  = 1) {
		
		if (!is_array($mValue)) {
			$mValue = array(
				$mValue
			);
		}
		$iNext  = $this->getNextTokenConstant($iPrev);
		return in_array($iNext, $mValue);
	}
	
	public function isNextTokenContent($mValue, $iPrev  = 1) {
		
		if (!is_array($mValue)) {
			$mValue = array(
				$mValue
			);
		}
		$iNext  = $this->getNextTokenContent($iPrev);
		return in_array($iNext, $mValue);
	}
	
	public function getPreviousTokenConstant($iPrev  = 1) {
		$sToken = $this->getPreviousToken($iPrev);
		return $sToken[0];
	}
	
	public function getPreviousTokenContent($iPrev  = 1) {
		$mToken = $this->getPreviousToken($iPrev);
		return (is_string($mToken)) ? $mToken : $mToken[1];
	}
	
	public function getNextTokenNonCommentConstant($iPrev  = 1) {
		do {
			$aToken = $this->getNextToken($iPrev);
			$iPrev++;
		} while ($aToken[0] == T_COMMENT);
		return $aToken[0];
	}
	
	public function getNextTokenConstant($iPrev  = 1) {
		$sToken = $this->getNextToken($iPrev);
		return $sToken[0];
	}
	
	public function getNextTokenContent($iNext  = 1) {
		$mToken = $this->getNextToken($iNext);
		return (is_string($mToken)) ? $mToken : $mToken[1];
	}
	
	public function getPreviousWhitespace() {
		$sWhiteSpace = '';
		$aMatch      = array();
		for ($x           = $this->iCount - 1;$x >= 0;$x--) {
			$aToken = $this->getToken($x);
			
			if (is_array($aToken)) {
				
				if ($aToken[0] == T_WHITESPACE) {
					$sWhiteSpace.= $aToken[1];
				}
				elseif (preg_match("/([\s\r\n]+)$/", $aToken[1], $aMatch)) {
					$sWhiteSpace.= $aMatch[0];
					return $sWhiteSpace;
				}
			}

			else {
				return $sWhiteSpace;
			}
		}
		return $sWhiteSpace;
	}
	
	public function removeWhitespace() {
		
		if ($this->isPreviousTokenConstant(T_COMMENT) and preg_match("/^(\/\/|#)/", $this->getPreviousTokenContent())) {
			return false;
		}
		elseif ($this->getPreviousTokenConstant(2) == T_END_HEREDOC) {
			return false;
		}
		$pop = 0;
		for ($i   = count($this->aOut) - 1;$i >= 0;$i--) {
			$cNow = & $this->aOut[$i];
			
			if (strlen(trim($cNow)) == 0) {
				array_pop($this->aOut);
				$pop++;
			}

			else {
				$cNow = rtrim($cNow);
				break;
			}
		}
		return true;
	}
	
	public function &getToken($iIndex) {
		
		if ($iIndex < 0 or $iIndex > count($this->aTokens)) {
			return false;
		}

		else {
			return $this->aTokens[$iIndex];
		}
	}
	
	public function openBraceDontProcess() {
		return $this->isPreviousTokenConstant(T_VARIABLE) or $this->isPreviousTokenConstant(T_OBJECT_OPERATOR) or ($this->isPreviousTokenConstant(T_STRING) and $this->getPreviousTokenConstant(2) == T_OBJECT_OPERATOR) or $this->getMode('double_quote');
	}
}
