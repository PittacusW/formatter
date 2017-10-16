<?php

namespace Contal\Fmt\Core;

final class RTrim extends FormatterPass {
	public function candidate($source, $foundTokens) {
		return true;
	}
	public function format($source) {
		return preg_replace('/\h+$/mu', '', $source);
	}
}
