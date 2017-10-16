<?php

namespace Contal\Beautifier\Filter;

use Contal\Beautifier\Filter;

class FilterEqualsAlign extends Filter {
	var $maxVarSize = 0;
	var $equalsToModify = array();
	public function t_assigment($sTag) {
		$var_size = 0;
		$counter = 1;
		$next = $this->oBeaut->getToken($this->oBeaut->iCount + $counter);
		$ends = 0;
		while ($next != "=" && $ends < 2 && $next != null) {

			if ($next == ";") {
				$ends++;
			}
			$counter++;
			$next = $this->oBeaut->getToken($this->oBeaut->iCount + $counter);
		}
		$counter = 1;
		$prev = $this->oBeaut->getToken($this->oBeaut->iCount - $counter);
		while ($prev[0] == T_WHITESPACE) {
			$counter++;
			$prev = $this->oBeaut->getToken($this->oBeaut->iCount - $counter);
		}
		while (($prev[0] == T_VARIABLE || $prev[0] == T_OBJECT_OPERATOR || $prev[0] == T_STRING) && $prev != null) {
			$var_size += strlen($prev[1]);
			$counter++;
			$prev = $this->oBeaut->getToken($this->oBeaut->iCount - $counter);
		}

		if ($this->maxVarSize < $var_size) {
			$this->maxVarSize = $var_size;
		}
		$this->equalsToModify[] = array(
			'position' => count($this->oBeaut->aOut) + 1,
			'size' => $var_size,
		);

		if ($next != "=") {
			foreach ($this->equalsToModify as $equal) {
				$this->oBeaut->aOut[$equal['position'] - 2] = $this->oBeaut->aOut[$equal['position'] - 2] . str_repeat(" ", $this->maxVarSize - $equal['size']);
			}
			$this->maxVarSize = 0;
			$this->equalsToModify = array();
		}
		$this->oBeaut->add(" " . $sTag . " ");
	}
}
