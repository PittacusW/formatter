<?php

namespace Contal;

class Formatter {
	
	public static function format($file) {
		try {
			$b = new Beautifier;
			$b->setIndentChar("\t");
			$b->setIndentNumber(1);
			$b->EqualsAlign();
			$b->Default();
			$b->IndentStyles(['style' => 'K&R']);
			$b->ArrayNested();
			$b->NewLines(['before' => ['T_IF', 'namespace', 'T_PUBLIC', 'T_PRIVATE', 'T_PROTECTED'], 'after' => ['namespace', 'class']]);
			$b->setInputString($file);
			$b->setOutputFile($file);
			$b->process();
			return Fmt::run(['smart_linebreak_after_curly' => true, 'visibility_order' => true, 'yoda' => true, 'enable_auto_align' => true, "psr2" => true], $b->get());
		} catch(Error $e) {
			echo $e->getMessage();
		}
	}
}
