<?php

namespace Contal\Beautifier\Filter;

use Contal\Beautifier\Filter;
use Contal\Beautifier\Filter\FilterDefault;

require_once 'PEAR/Config.php';
class FilterPear extends Filter {
	protected $aSettings = array(
		'add_header' => false,
		'newline_class' => true,
		'newline_function' => true,
		'switch_without_indent' => true,
	);
	protected $sDescription = 'Filter the code to make it compatible with PEAR Coding Specs';
	private $bOpenTag = false;
	function t_open_tag_with_echo($sTag) {
		$this->oBeaut->add("<?php echo ");
	}
	function t_close_brace($sTag) {

		if ($this->oBeaut->getMode('string_index') or $this->oBeaut->getMode('double_quote')) {
			$this->oBeaut->add($sTag);
		} elseif ($this->oBeaut->getControlSeq() == T_SWITCH and $this->getSetting('switch_without_indent')) {
			$this->oBeaut->removeWhitespace();
			$this->oBeaut->decIndent();
			$this->oBeaut->addNewLineIndent();
			$this->oBeaut->add($sTag);
			$this->oBeaut->addNewLineIndent();
		} else {
			return Filter::BYPASS;
		}
	}
	function t_semi_colon($sTag) {

		if ($this->oBeaut->isPreviousTokenConstant(T_BREAK)) {
			$this->oBeaut->removeWhitespace();
			$this->oBeaut->add($sTag);
			$this->oBeaut->addNewLine();
			$this->oBeaut->addNewLineIndent();
		} elseif ($this->oBeaut->getControlParenthesis() == T_FOR) {
			$this->oBeaut->removeWhitespace();
			$this->oBeaut->add($sTag . " ");
		} else {
			return Filter::BYPASS;
		}
	}
	function t_case($sTag) {
		$this->oBeaut->removeWhitespace();
		$this->oBeaut->decIndent();

		if ($this->oBeaut->isPreviousTokenConstant(T_BREAK, 2)) {
			$this->oBeaut->addNewLine();
		}
		$this->oBeaut->addNewLineIndent();
		$this->oBeaut->add($sTag . ' ');
	}
	function t_default($sTag) {
		$this->t_case($sTag);
	}
	function t_break($sTag) {
		$this->oBeaut->add($sTag);

		if ($this->oBeaut->isNextTokenConstant(T_LNUMBER)) {
			$this->oBeaut->add(" ");
		}
	}
	function t_open_brace($sTag) {

		if ($this->oBeaut->openBraceDontProcess()) {
			$this->oBeaut->add($sTag);
		} elseif ($this->oBeaut->getControlSeq() == T_SWITCH and $this->getSetting('switch_without_indent')) {
			$this->oBeaut->add($sTag);
			$this->oBeaut->incIndent();
		} else {
			$bypass = true;

			if ($this->oBeaut->getControlSeq() == T_CLASS and $this->getSetting('newline_class')) {
				$bypass = false;
			}

			if ($this->oBeaut->getControlSeq() == T_FUNCTION and $this->getSetting('newline_function')) {
				$bypass = false;
			}

			if ($bypass) {
				return Filter::BYPASS;
			}
			$this->oBeaut->removeWhitespace();
			$this->oBeaut->addNewLineIndent();
			$this->oBeaut->add($sTag);
			$this->oBeaut->incIndent();
			$this->oBeaut->addNewLineIndent();
		}
	}
	function t_comment($sTag) {

		if ($sTag{0} != '#') {
			return Filter::BYPASS;
		}
		$oFilterDefault = new FilterDefault($this->oBeaut);
		$sTag = '//' . substr($sTag, 1);
		return $oFilterDefault->t_comment($sTag);
	}
	function t_open_tag($sTag) {
		$this->oBeaut->add("<?php");
		$this->oBeaut->addNewLineIndent();

		if (!$this->bOpenTag) {
			$this->bOpenTag = true;
			$sComment = '';
			$x = 1;
			while ($this->oBeaut->isNextTokenConstant(T_COMMENT, $x)) {
				$sComment .= $this->oBeaut->getNextTokenContent($x);
				$x++;
			}

			if (stripos($sComment, 'license') === FALSE) {
				$this->addHeaderComment();
			}
		}
	}
	function preProcess() {
		$this->bOpenTag = false;
	}
	function addHeaderComment() {

		if (!($sLicense = $this->getSetting('add_header'))) {
			return;
		}

		if (file_exists($sLicense)) {
			$sDataPath = $sLicense;
		} else {
			$oConfig = PEAR_Config::singleton();
			$sDataPath = Common::normalizeDir($oConfig->get('data_dir')) . 'PHP_Beautifier/licenses/' . $sLicense . '.txt';
		}

		if (file_exists($sDataPath)) {
			$sLicenseText = file_get_contents($sDataPath);
		} else {
			throw (new Exception("Can't load license '" . $sLicense . "'"));
		}
		$this->oBeaut->removeWhitespace();
		$this->oBeaut->addNewLine();
		$this->oBeaut->add($sLicenseText);
		$this->oBeaut->addNewLineIndent();
	}
}
