<?php

namespace Contal\Fmt\Additionals;

final class RemoveSemicolonAfterCurly extends AdditionalPass {
	const LAMBDA_CURLY_OPEN = 'LAMBDA_CURLY_OPEN';
	public function candidate($source, $foundTokens) {

		if (isset($foundTokens[ST_CURLY_CLOSE], $foundTokens[ST_SEMI_COLON])) {
			return true;
		}
		return false;
	}
	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$curlyStack = [];
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_NAMESPACE:
			case T_CLASS:
			case T_TRAIT:
			case T_INTERFACE:
			case T_WHILE:
			case T_IF:
			case T_SWITCH:
			case T_FOR:
			case T_FOREACH:
				$touchedFunction = true;
				$this->appendCode($text);
				break;
			case T_FUNCTION:
				$touchedFunction = true;

				if (!$this->rightUsefulTokenIs(T_STRING)) {
					$touchedFunction = false;
				}
				$this->appendCode($text);
				break;
			case ST_CURLY_OPEN:
				$curlyType = ST_CURLY_OPEN;

				if (!$touchedFunction) {
					$curlyType = self::LAMBDA_CURLY_OPEN;
				}
				$curlyStack[] = $curlyType;
				$this->appendCode($text);
				break;
			case ST_CURLY_CLOSE:
				$curlyType = array_pop($curlyStack);
				$this->appendCode($text);

				if (self::LAMBDA_CURLY_OPEN != $curlyType && $this->rightUsefulTokenIs(ST_SEMI_COLON)) {
					$this->walkUntil(ST_SEMI_COLON);
				}
				break;
			default:
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}
	public function getDescription() {
		return 'Remove semicolon after closing curly brace.';
	}
	public function getExample() {
		return <<<'EOT'
<?php
// From:
function xxx() {
    // code
};

// To:
function xxx() {
    // code
}
?>
EOT;

	}
}
