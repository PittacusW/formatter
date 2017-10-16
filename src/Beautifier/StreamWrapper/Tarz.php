<?php

namespace Contal\Beautifier\StreamWrapper;

require_once 'Archive/Tar.php';
class PHP_Beautifier_StreamWrapper_Tarz implements PHP_Beautifier_StreamWrapper_Interface {
	public $oTar;
	public $sTar;
	public $sPath;
	public $sText;
	public $iSeek = 0;
	public $iSeekDir = 0;
	public $aContents = array();
	function stream_open($sPath, $sMode, $iOptions, &$sOpenedPath) {

		if ($this->getTar($sPath)) {
			$aContents = $this->oTar->listContent();

			if (array_filter($aContents, array(
				$this,
				'tarFileExists',
			))) {
				$this->sText = $this->oTar->extractInString($this->sPath);
				return true;
			}
		}
		return false;
	}
	function getTar($sPath) {

		if (preg_match("/tarz:\/\/(.*?(\.tgz|\.tar\.gz|\.tar\.bz2|\.tar)+)(?:#\/?(.*))*/", $sPath, $aMatch)) {
			$this->sTar = $aMatch[1];

			if (strpos($aMatch[2], 'gz') !== FALSE) {
				$sCompress = 'gz';
			} elseif (strpos($aMatch[2], 'bz2') !== FALSE) {
				$sCompress = 'bz2';
			} elseif (strpos($aMatch[2], 'tar') !== FALSE) {
				$sCompress = false;
			} else {
				return false;
			}

			if (isset($aMatch[3])) {
				$this->sPath = $aMatch[3];
			}

			if (file_exists($this->sTar)) {
				$this->oTar = new Archive_Tar($this->sTar, $sCompress);
				return true;
			}
		} else {
			return false;
		}
	}
	function stream_close() {
		unset($this->oTar, $this->sText, $this->sPath, $this->iSeek);
	}
	function stream_read($iCount) {
		$sRet = substr($this->sText, $this->iSeek, $iCount);
		$this->iSeek += strlen($sRet);
		return $sRet;
	}
	function stream_write($sData) {
	}
	function stream_eof() {

		if (version_compare(PHP_VERSION, '5.0.0.RC.1', ">=") and version_compare(PHP_VERSION, '5.0.0.RC.4', "<")) {
			return !($this->iSeek >= strlen($this->sText));
		} else {
			return $this->iSeek >= strlen($this->sText);
		}
	}
	function stream_tell() {
		return $this->iSeek;
	}
	function stream_seek($iOffset, $iWhence) {
		switch ($iWhence) {
		case SEEK_SET:

			if ($iOffset < strlen($this->sText) and $iOffset >= 0) {
				$this->iSeek = $iOffset;
				return true;
			} else {
				return false;
			}
			break;
		case SEEK_CUR:

			if ($iOffset >= 0) {
				$this->iSeek += $iOffset;
				return true;
			} else {
				return false;
			}
			break;
		case SEEK_END:

			if (strlen($this->sText) + $iOffset >= 0) {
				$this->iSeek = strlen($this->sText) + $iOffset;
				return true;
			} else {
				return false;
			}
			break;
		default:
			return false;
		}
	}
	function stream_flush() {
	}
	function stream_stat() {
	}
	function unlink($sPath) {
	}
	function dir_opendir($sPath, $iOptions) {

		if ($this->getTar($sPath)) {
			array_walk($this->oTar->listContent(), array(
				$this,
				'getFileList',
			));
			return true;
		} else {
			return false;
		}
	}
	function dir_readdir() {

		if ($this->iSeekDir >= count($this->aContents)) {
			return false;
		} else {
			return $this->aContents[$this->iSeekDir++];
		}
	}
	function dir_rewinddir() {
		$this->iSeekDir = 0;
	}
	function dir_closedir() {
	}
	function getFileList($aInput) {
		$this->aContents[] = $aInput['filename'];
	}
	function tarFileExists($aInput) {
		return ($aInput['filename'] == $this->sPath and empty($aInput['typeflag']));
	}
}
stream_wrapper_register("tarz", "PHP_Beautifier_StreamWrapper_Tarz");
