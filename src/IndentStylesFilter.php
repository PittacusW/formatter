<?php
namespace Contal;

use Contal\Beautifier;
use Contal\Filter;

class IndentStylesFilter extends Filter {

	
	protected $aSettings      = array(
		'style'                => 'K&R'
	);
	
	public $aAllowedStyles = array(
		"k&r"                => "kr",
		"allman"                => "bsd",
		"bsd"                => "bsd",
		"gnu"                => "gnu",
		"whitesmiths"                => "ws",
		"ws"                => "ws"
	);
	
	protected $sDescription   = 'Filter the code in 4 different indent styles: K&R, Allman, Whitesmiths and GNU';
	
	public function __construct(Beautifier $oBeaut, $aSettings      = array()) {
		parent::__construct($oBeaut, $aSettings);
		$this->addSettingDefinition('style', 'text', 'Style for indent: K&R, Allman, Whitesmiths, GNU');
	}
	
	public function __call($sMethod, $aArgs) {
		
		if (strtolower($this->getSetting('style')) == 'k&r') {
			return Filter::BYPASS;
		}
		$sNewMethod = $this->_getFunctionForStyle($sMethod);
		
		if (method_exists($this, $sNewMethod)) {
			call_user_func_array(array(
				$this,
				$sNewMethod
			) , $aArgs);
		}

		else {
			return Filter::BYPASS;
		}
	}
	function t_open_brace_bsd($sTag) {
		$this->oBeaut->addNewLineIndent();
		$this->oBeaut->add($sTag);
		$this->oBeaut->incIndent();
		$this->oBeaut->addNewLineIndent();
	}
	function t_close_brace_bsd($sTag) {
		
		if ($this->oBeaut->getMode('string_index') or $this->oBeaut->getMode('double_quote')) {
			$this->oBeaut->add($sTag);
		}

		else {
			$this->oBeaut->removeWhitespace();
			$this->oBeaut->decIndent();
			$this->oBeaut->addNewLineIndent();
			$this->oBeaut->add($sTag);
			$this->oBeaut->addNewLineIndent();
		}
	}
	function t_open_brace_ws($sTag) {
		$this->oBeaut->addNewLine();
		$this->oBeaut->incIndent();
		$this->oBeaut->addIndent();
		$this->oBeaut->add($sTag);
		$this->oBeaut->addNewLineIndent();
	}
	function t_close_brace_ws($sTag) {
		
		if ($this->oBeaut->getMode('string_index') or $this->oBeaut->getMode('double_quote')) {
			$this->oBeaut->add($sTag);
		}

		else {
			$this->oBeaut->removeWhitespace();
			$this->oBeaut->addNewLineIndent();
			$this->oBeaut->add($sTag);
			$this->oBeaut->decIndent();
			$this->oBeaut->addNewLineIndent();
		}
	}
	function t_close_brace_gnu($sTag) {
		
		if ($this->oBeaut->getMode('string_index') or $this->oBeaut->getMode('double_quote')) {
			$this->oBeaut->add($sTag);
		}

		else {
			$iHalfSpace = floor($this->oBeaut->iIndentNumber / 2);
			$this->oBeaut->removeWhitespace();
			$this->oBeaut->decIndent();
			$this->oBeaut->addNewLineIndent();
			$this->oBeaut->add(str_repeat($this->oBeaut->sIndentChar, $iHalfSpace));
			$this->oBeaut->add($sTag);
			$this->oBeaut->addNewLineIndent();
		}
	}
	function t_open_brace_gnu($sTag) {
		$iHalfSpace = floor($this->oBeaut->iIndentNumber / 2);
		$this->oBeaut->addNewLineIndent();
		$this->oBeaut->add(str_repeat($this->oBeaut->sIndentChar, $iHalfSpace));
		$this->oBeaut->add($sTag);
		$this->oBeaut->incIndent();
		$this->oBeaut->addNewLineIndent();
	}
	function t_else($sTag) {
		
		if ($this->oBeaut->getPreviousTokenContent() == '}') {
			$this->oBeaut->removeWhitespace();
			$this->oBeaut->addNewLineIndent();
			$this->oBeaut->add(trim($sTag));
			
			if (!$this->oBeaut->isNextTokenContent('{')) {
				$this->oBeaut->add(' ');
			}
		}

		else {
			return Filter::BYPASS;
		}
	}
	
	private function _getFunctionForStyle($sMethod) {
		$sStyle = strtolower($this->getSetting('style'));
		
		if (!array_key_exists($sStyle, $this->aAllowedStyles)) {
			throw (new Exception("Style " . $sStyle . "doesn't exists"));
		}
		return $sMethod . "_" . $this->aAllowedStyles[$sStyle];
	}
}
