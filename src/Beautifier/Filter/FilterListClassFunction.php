<?php

namespace Contal\Beautifier\Filter;

use Contal\Beautifier\Filter;

class FilterListClassFunction extends Filter {
	protected $aFilterTokenFunctions = array(
		T_CLASS => 't_class',
		T_FUNCTION => 't_function',
		T_COMMENT => 't_comment',
		T_OPEN_TAG => 't_open_tag',
	);
	private $aFunctions = array();
	private $aClasses = array();
	private $iComment;
	private $iOpenTag = null;
	protected $aSettings = array(
		'list_functions' => true,
		'list_classes' => true,
	);
	protected $sDescription = 'Create a list of functions and classes in the script';
	private $aInclude = array(
		'functions' => true,
		'classes' => true,
	);
	public function __construct(PHP_Beautifier $oBeaut, $aSettings = array()) {
		parent::__construct($oBeaut, $aSettings);
		$this->addSettingDefinition('list_functions', 'bool', 'List Functions inside the file');
		$this->addSettingDefinition('list_classes', 'bool', 'List Classes inside the file');
	}
	function t_function($sTag) {

		if ($this->aInclude['functions']) {
			$sNext = $this->oBeaut->getNextTokenContent(1);

			if ($sNext == '&') {
				$sNext .= $this->oBeaut->getNextTokenContent(2);
			}
			array_push($this->aFunctions, $sNext);
		}
		return Filter::BYPASS;
	}
	function includeInList($sTag, $sValue) {
		$this->aInclude[$sTag] = $sValue;
	}
	function t_class($sTag) {

		if ($this->aInclude['classes']) {
			$sClassName = $this->oBeaut->getNextTokenContent(1);

			if ($this->oBeaut->isNextTokenConstant(T_EXTENDS, 2)) {
				$sClassName .= ' extends ' . $this->oBeaut->getNextTokenContent(3);
			}
			array_push($this->aClasses, $sClassName);
		}
		return Filter::BYPASS;
	}
	function t_doc_comment($sTag) {

		if (strpos($sTag, 'Class and Function List') !== FALSE) {
			$this->iComment = $this->oBeaut->iCount;
		}
		return Filter::BYPASS;
	}
	function t_open_tag($sTag) {

		if (is_null($this->iOpenTag)) {
			$this->iOpenTag = $this->oBeaut->iCount;
		}
		return Filter::BYPASS;
	}
	function postProcess() {
		$sNL = $this->oBeaut->sNewLine;
		$aOut = array(
			"/**",
			"* Class and Function List:",
		);

		if ($this->getSetting('list_functions')) {
			$aOut[] = "* Function list:";
			foreach ($this->aFunctions as $sFunction) {
				$aOut[] = "* - " . $sFunction . "()";
			}
		}

		if ($this->getSetting('list_classes')) {
			$aOut[] = "* Classes list:";
			foreach ($this->aClasses as $sClass) {
				$aOut[] = "* - " . $sClass;
			}
		}
		$aOut[] = "*/";

		if ($this->iComment) {
			$sComment = $this->oBeaut->getTokenAssocText($this->iComment);

			if (preg_match("/" . addcslashes($sNL, "\r\n") . "([ \t]+)/ms", $sComment, $aMatch)) {
				$sPrevio = $sNL . $aMatch[1];
			} else {
				$sPrevio = $sNL;
			}
			$sText = implode($sPrevio, $aOut) . $sNL;
			$this->oBeaut->replaceTokenAssoc($this->iComment, $sText);
		} else {
			$sPrevio = $sNL;
			$sTag = trim($this->oBeaut->getTokenAssocText($this->iOpenTag)) . "\n";
			$sText = $sPrevio . implode($sPrevio, $aOut);
			$this->oBeaut->replaceTokenAssoc($this->iOpenTag, rtrim($sTag) . $sText . $sPrevio);
		}
	}
}
