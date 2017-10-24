<?php

namespace Contal;
class Formatter {
	
	public static function format($file) {
		try {
			$b = new Beautifier;
			$b->setIndentChar("\t");
			$b->setIndentNumber(1);
			$b->addFilter('Default');
			$b->addFilter('EqualsAlign');
			$b->addFilter('IndentStyles', ['style' => 'K&R']);
			$b->addFilter('NewLines', ['before' => ['T_CLASS', 'T_FOREACH', 'T_SWITCH', 'T_TRY', 'T_IF', 'T_PRIVATE', 'T_PUBLIC', 'T_PROTECTED', 'T_ELSEIF'], 'after' => ['T_ELSE', 'T_NAMESPACE', 'T_CLASS', 'T_CATCH']]);
			$b->addFilter('ArrayNested');
			$b->setInputString($file);
			$b->setOutputFile($file);
			$b->process();
			return $b->get();
		} catch(Error $e) {
			
		}
	}
}
