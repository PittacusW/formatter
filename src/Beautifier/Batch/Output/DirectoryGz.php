<?php

namespace Contal\Beautifier\Batch\Output;

require_once 'DirectoryTar.php';
class PHP_Beautifier_Batch_Output_DirectoryGz extends PHP_Beautifier_Batch_Output_DirectoryTar {
	protected function getTar($sFileName) {
		return new Archive_Tar($sFileName . '.tgz', 'gz');
	}
}
