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
        $b->IndentStyles(['style' => 'K&R']);
        $b->ArrayNested();
		$b->NewLines(['before' => ['T_IF', 'class', 'namespace', 'public', 'private', 'protected'], 'after' => ['namespace']]);
        $b->setInputString($file);
        $b->setOutputFile($file);
        $b->process();
        return $b->get();
	}
}