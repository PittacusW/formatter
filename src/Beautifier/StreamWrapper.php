<?php

namespace Contal\Beautifier;

interface PHP_Beautifier_StreamWrapper_Interface {
	function stream_open($sPath, $sMode, $iOptions, &$sOpenedPath);
	function stream_close();
	function stream_read($iCount);
	function stream_write($sData);
	function stream_eof();
	function stream_tell();
	function stream_seek($iOffset, $iWhence);
	function stream_flush();
	function stream_stat();
	function unlink($sPath);
	function dir_opendir($sPath, $iOptions);
	function dir_readdir();
	function dir_rewinddir();
	function dir_closedir();
}
require_once __DIR__ . '/StreamWrapper/Tarz.php';
