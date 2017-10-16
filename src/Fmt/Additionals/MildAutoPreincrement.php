<?php

namespace Contal\Fmt\Additionals;

final class MildAutoPreincrement extends AutoPreincrement {
	public function getDescription() {
		return 'Automatically convert postincrement to preincrement. (Deprecated pass. Use AutoPreincrement instead).';
	}
}
