<?php

namespace Contal\Fmt\Additionals;

final class OnlyOrderUseClauses extends OrderAndRemoveUseClauses {
	public function getDescription() {
		return 'Order use block - do not remove unused imports.';
	}
	public function getExample() {
		return <<<'EOT'
// From
use C;
use B;

class D {
	function f() {
		new B();
	}
}


// To
use B;
use C;

class D {
	function f() {
		new B();
	}
}
EOT;

	}
	protected function sortUseClauses($source, $splitComma, $removeUnused, $stripBlankLines, $blanklineAfterUseBlock) {
		$removeUnused = false;
		return parent::sortUseClauses($source, $splitComma, $removeUnused, $stripBlankLines, $blanklineAfterUseBlock);
	}
}
