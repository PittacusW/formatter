<?php

namespace Contal\Fmt\Additionals;

use Contal\Fmt\Core\FormatterPass;

abstract class AdditionalPass extends FormatterPass {
	abstract public function getDescription();
	abstract public function getExample();
}
