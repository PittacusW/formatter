<?php

namespace Contal\Beautifier\Filter;

use Contal\Beautifier\Filter;

require_once 'PEAR/Config.php';
class FilterphpBB extends Filter {
	protected $sDescription = 'Filter the code to make it compatible with phpBB Coding Standards';
	private $iNestedIfs = 0;
	public function __construct(PHP_Beautifier $oBeaut, $aSettings = array()) {
		parent::__construct($oBeaut, $aSettings);
		$oBeaut->setIndentChar("\t");
		$oBeaut->setIndentNumber(1);
		$oBeaut->setNewLine(PHP_EOL);
	}
	function t_open_brace($sTag) {
		$this->oBeaut->addNewLineIndent();
		$this->oBeaut->add($sTag);
		$this->oBeaut->incIndent();
		$this->oBeaut->addNewLineIndent();
	}
	function t_close_brace($sTag) {

		if ($this->oBeaut->getMode('string_index') or $this->oBeaut->getMode('double_quote')) {
			$this->oBeaut->add($sTag);
		} else {
			$this->oBeaut->removeWhitespace();
			$this->oBeaut->decIndent();
			$this->oBeaut->addNewLineIndent();
			$this->oBeaut->add($sTag);
			$this->oBeaut->addNewLineIndent();
		}
	}
	function t_else($sTag) {
		$this->oBeaut->add($sTag);

		if (!$this->oBeaut->isNextTokenContent('{')) {
			$this->oBeaut->addNewLineIndent();
			$this->oBeaut->add('{');
			$this->oBeaut->incIndent();
			$this->oBeaut->addNewLineIndent();
			$this->iNestedIfs++;
		}
	}
	function t_semi_colon($sTag) {
		$this->oBeaut->removeWhitespace();
		$this->oBeaut->add($sTag);

		if ($this->oBeaut->getControlParenthesis() != T_FOR) {

			if ($this->iNestedIfs > 0) {
				$this->oBeaut->decIndent();
				$this->oBeaut->addNewLineIndent();
				$this->oBeaut->add('}');
				$this->iNestedIfs--;
			}
			$this->oBeaut->addNewLineIndent();
		}
	}
	function preProcess() {
		$this->iNestedIfs = 0;
	}
}
