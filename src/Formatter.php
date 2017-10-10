<?php

namespace Contal;

class Formatter {
	
	public static function format($file) {
		$b = new Beautifier;
        $b->setIndentChar("\t");
        $b->setIndentNumber(1);
		$b->addFilter('EqualsAlign');
        $b->addFilter('Default');
        $b->addFilter('IndentStyles',array('style' => 'K&R'));
        $b->addFilter('ArrayNested');
		$b->addFilter('NewLines', array('before' => 'class:namespace:T_FUNCTION', 'after' => 'class:namespace'));
        $b->setInputString($clean);
        $b->setOutputFile($file);
        $b->process();
        $b->save();
		return $b;
	}
}