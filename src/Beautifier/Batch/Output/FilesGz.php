<?php

namespace Contal\Beautifier\Batch\Output;

require_once 'FilesTar.php';
class PHP_Beautifier_Batch_Output_FilesGz extends PHP_Beautifier_Batch_Output_FilesTar {
	protected $sCompress = 'gz';
	protected $sExt = 'tgz';
}
