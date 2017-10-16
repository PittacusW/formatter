<?php

namespace Contal\Fmt\Core;

final class Tree extends FormatterPass {
	public function candidate($source, $tokens) {
		return true;
	}
	public function consumeBlock(&$tkns, $start, $end) {
		$count = 1;
		$block = [];
		while (list(, $token) = each($tkns)) {
			list($id, $text) = $this->getToken($token);

			if ($start == $id) {
				++$count;
			}

			if ($end == $id) {
				--$count;
			}

			if (0 == $count) {
				break;
			}
			$block[] = [$id, $text];
		}
		return $block;
	}
	public function consumeCurlyBlock(&$tkns) {
		$count = 1;
		$block = [];
		while (list(, $token) = each($tkns)) {
			list($id, $text) = $this->getToken($token);

			if (ST_CURLY_OPEN == $id) {
				++$count;
			}

			if (T_CURLY_OPEN == $id) {
				++$count;
			}

			if (T_DOLLAR_OPEN_CURLY_BRACES == $id) {
				++$count;
			}

			if (ST_CURLY_CLOSE == $id) {
				--$count;
			}

			if (0 == $count) {
				break;
			}
			$block[] = [$id, $text];
		}
		return $block;
	}
	public function format($code) {
		$tokens = token_get_all($code);
		$tree = $this->parseTree($tokens);
		return $this->visit($tree);
	}
	public function parseTree($tokens) {
		$tree = [];
		while (list(, $token) = each($tokens)) {
			list($id, $text) = $this->getToken($token);

			if (T_WHITESPACE == $id) {
				$lnCount = substr_count($text, "\n");
				$text = str_repeat("\n", $lnCount);

				if (0 == $lnCount) {
					$text = ' ';
				} elseif ($lnCount > 2) {
					$lnCount = 2;
					$text = str_repeat("\n", $lnCount);
				}
			}

			if (ST_PARENTHESES_OPEN == $id) {
				$block = $this->consumeBlock($tokens, ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
				$block = $this->parseTree($block);
				array_unshift($block, ST_PARENTHESES_BLOCK, ST_PARENTHESES_OPEN);
				array_push($block, ST_PARENTHESES_CLOSE);
				$tree[] = $block;
				continue;
			}

			if (ST_BRACKET_OPEN == $id) {
				$block = $this->consumeBlock($tokens, ST_BRACKET_OPEN, ST_BRACKET_CLOSE);
				$block = $this->parseTree($block);
				array_unshift($block, ST_BRACKET_BLOCK, ST_BRACKET_OPEN);
				array_push($block, ST_BRACKET_CLOSE);
				$tree[] = $block;
				continue;
			}

			if (ST_CURLY_OPEN == $id) {
				$block = $this->consumeCurlyBlock($tokens);
				$block = $this->parseTree($block);
				array_unshift($block, ST_CURLY_BLOCK, ST_CURLY_OPEN);
				array_push($block, ST_CURLY_CLOSE);
				$tree[] = $block;
				continue;
			}
			$tree[] = [$id, $text];
		}
		return $tree;
	}
	public function visit($tree) {
		$str = '';
		foreach ($tree as $token) {
			list($id, $text) = $this->getToken($token);

			if (ST_PARENTHESES_BLOCK == $id || ST_BRACKET_BLOCK == $id || ST_CURLY_BLOCK == $id) {
				array_shift($token);
				$open = array_shift($token);
				$close = array_pop($token);
				$block = $this->visit($token);
				$block = str_replace("\n", "\n\t", $block);
				$block = ($open . rtrim($block) . (("\t" == substr($block, -1) || ' ' == substr($block, -1)) ? "\n" : '') . $close);
				$str .= $block;
				continue;
			}
			$str .= $text;
		}
		return $str;
	}
}
