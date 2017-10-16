<?php

namespace Contal\Beautifier\Filter;

use Contal\Beautifier\Filter;

class FilterDocBlock extends Filter {
	public function t_doc_comment($sTag) {
		include_once "PHP/DocBlockGenerator/Align.php";
		$aligner = new PHP_DocBlockGenerator_Align();
		$this->oBeaut->removeWhiteSpace();
		$this->oBeaut->addNewLineIndent();
		$this->oBeaut->add($aligner->alignTags($sTag));
		$this->oBeaut->addNewLineIndent();
	}
}
