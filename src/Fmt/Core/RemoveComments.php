<?php

namespace Contal\Fmt\Core;

final class RemoveComments extends FormatterPass {
	public $commentStack = [];
	public function candidate($source, $foundTokens) {

		if (isset($foundTokens[T_COMMENT])) {
			return true;
		}
		return false;
	}
	public function format($source) {
		$newStr = '';
		$commentTokens = array(
			T_COMMENT,
		);

		if (defined('T_DOC_COMMENT')) {
			$commentTokens[] = T_DOC_COMMENT;
		}

		if (defined('T_ML_COMMENT')) {
			$commentTokens[] = T_ML_COMMENT;
		}
		$tokens = token_get_all($source);
		foreach ($tokens as $token) {

			if (is_array($token)) {

				if (in_array($token[0], $commentTokens)) {
					continue;
				}
				$token = $token[1];
			}
			$newStr .= $token;
		}
		return $newStr;
	}
}
