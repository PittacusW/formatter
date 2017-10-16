<?php

namespace Contal\Beautifier\Batch\Output;

require_once 'DirectoryTar.php';
class PHP_Beautifier_Batch_Output_DirectoryBz2 extends PHP_Beautifier_Batch_DirectoryTar {
	protected function getTar($sFileName) {
		return new Archive_Tar($sFileName . '.tar.bz2', 'bz2');
	}
}
