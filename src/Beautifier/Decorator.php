<?php

namespace Contal\Beautifier;

abstract class PHP_Beautifier_Decorator implements BeautifierInterface {
	protected $oBeaut;
	function __construct(BeautifierInterface $oBeaut) {
		$this->oBeaut = $oBeaut;
	}
	function __call($sMethod, $aArgs) {

		if (!method_exists($this->oBeaut, $sMethod)) {
			throw (new Exception("Method '$sMethod' doesn't exists"));
		} else {
			return call_user_func_array(array(
				$this->oBeaut,
				$sMethod,
			), $aArgs);
		}
	}
}
