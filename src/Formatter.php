<?php

namespace Contal;

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
		return Fmt::run(['smart_linebreak_after_curly' => true, 'visibility_order' => false], $b->get());
	}
}