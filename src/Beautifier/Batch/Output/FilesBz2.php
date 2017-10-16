<?php

namespace Contal\Beautifier\Batch\Output;

require_once 'FilesTar.php';
class PHP_Beautifier_Batch_Output_FilesBz2 extends PHP_Beautifier_Batch_Output_FilesTar {
	protected $sCompress = 'bz2';
	protected $sExt = 'tar.bz2';
}
