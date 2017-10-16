<?php

namespace Contal\Beautifier\Filter;

use Contal\Beautifier\Filter;

class FilterLowercase extends Filter {
	protected $sDescription = 'Lowercase all control structures. Parse the output with another Filters';
	private $aControlSeq = array(
		T_IF,
		T_ELSE,
		T_ELSEIF,
		T_WHILE,
		T_DO,
		T_FOR,
		T_FOREACH,
		T_SWITCH,
		T_DECLARE,
		T_CASE,
		T_DEFAULT,
		T_TRY,
		T_CATCH,
		T_ENDWHILE,
		T_ENDFOREACH,
		T_ENDFOR,
		T_ENDDECLARE,
		T_ENDSWITCH,
		T_ENDIF,
		T_INCLUDE,
		T_INCLUDE_ONCE,
		T_REQUIRE,
		T_REQUIRE_ONCE,
		T_FUNCTION,
		T_PRINT,
		T_RETURN,
		T_ECHO,
		T_NEW,
		T_CLASS,
		T_VAR,
		T_GLOBAL,
		T_THROW,
		T_IF,
		T_DO,
		T_WHILE,
		T_SWITCH,
		T_CASE,
		T_ELSEIF,
		T_ELSE,
		T_BREAK,
		T_INTERFACE,
		T_FINAL,
		T_ABSTRACT,
		T_PRIVATE,
		T_PUBLIC,
		T_PROTECTED,
		T_CONST,
		T_STATIC,
		T_LOGICAL_OR,
		T_LOGICAL_XOR,
		T_LOGICAL_AND,
		T_BOOLEAN_OR,
		T_BOOLEAN_AND,
	);
	private $oLog;
	public function __construct(PHP_Beautifier $oBeaut, $aSettings = array()) {
		parent::__construct($oBeaut, $aSettings);
		$this->oLog = Common::getLog();
	}
	public function t_string($sTag) {

		if ($sTag == 'TRUE' or $sTag == 'FALSE') {
			$this->oBeaut->aCurrentToken[1] = strtolower($sTag);
		}
		return Filter::BYPASS;
	}
	public function __call($sMethod, $aArgs) {
		$iToken = $this->aToken[0];
		$sContent = $this->aToken[1];

		if (in_array($iToken, $this->aControlSeq)) {
			$this->oLog->log("Lowercase:" . $sContent, PEAR_LOG_DEBUG);
			$this->oBeaut->aCurrentToken[1] = strtolower($sContent);
		}
		return Filter::BYPASS;
	}
}
