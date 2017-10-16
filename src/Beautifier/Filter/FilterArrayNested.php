<?php

namespace Contal\Beautifier\Filter;

use Contal\Beautifier\Filter;

class FilterArrayNested extends Filter {
	public function t_parenthesis_open($sTag) {
		$this->oBeaut->add($sTag);

		if ($this->oBeaut->getControlParenthesis() == T_ARRAY) {
			$this->oBeaut->addNewLine();
			$this->oBeaut->incIndent();
			$this->oBeaut->addIndent();
		}
	}
	public function t_parenthesis_close($sTag) {
		$this->oBeaut->removeWhitespace();

		if ($this->oBeaut->getControlParenthesis() == T_ARRAY) {
			$this->oBeaut->decIndent();

			if ($this->oBeaut->getPreviousTokenContent() != '(') {
				$this->oBeaut->addNewLine();
				$this->oBeaut->addIndent();
			}
			$this->oBeaut->add($sTag . ' ');
		} else {
			$this->oBeaut->add($sTag . ' ');
		}
	}
	public function t_comma($sTag) {

		if ($this->oBeaut->getControlParenthesis() != T_ARRAY) {
			$this->oBeaut->add($sTag . ' ');
		} else {
			$this->oBeaut->add($sTag);
			$this->oBeaut->addNewLine();
			$this->oBeaut->addIndent();
		}
	}
}
