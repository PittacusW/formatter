<?php

namespace Contal\Beautifier;

interface PHP_Beautifier_Tokenizer_Interface {
	public function __construct($sText);
	public function getTokens();
}
