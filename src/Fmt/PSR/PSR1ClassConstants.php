<?php

namespace Contal\Fmt\PSR;

final class PSR1ClassConstants extends FormatterPass {
	public function candidate($source, $foundTokens) {

		if (isset($foundTokens[T_CONST]) || isset($foundTokens[T_STRING])) {
			return true;
		}
		return false;
	}
	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$ucConst = false;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_CONST:
				$ucConst = true;
				$this->appendCode($text);
				break;
			case T_STRING:

				if ($ucConst) {
					$text = strtoupper($text);
					$ucConst = false;
				}
				$this->appendCode($text);
				break;
			default:
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}
}
