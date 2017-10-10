<?php

namespace Contal;

include_once '../vendor/autoload.php';

class Formatter {
	
	public static function format($file) {
		$b = new Beautifier;
        $b->setIndentChar("\t");
        $b->setIndentNumber(1);
		$b->EqualsAlign();
        $b->Default();
        $b->IndentStyles(array('style' => 'K&R'));
        $b->ArrayNested();
		$b->NewLines(array('before' => 'T_IF:class:namespace', 'after' => 'class:namespace'));
        $b->setInputString($file);
        $b->setOutputFile($file);
        $b->process();
        return $b->get();
	}
}