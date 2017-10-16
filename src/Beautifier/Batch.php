<?php

namespace Contal\Beautifier;

require_once __DIR__ . '/Decorator.php';
require_once __DIR__ . '/Batch/Output.php';
class PHP_Beautifier_Batch extends PHP_Beautifier_Decorator {
	private $sCompress = false;
	private $mPreInputFiles = array();
	private $sPreOutputFile = './';
	private $oBatchOutput;
	private $aBatchInputs;
	public $mInputFiles;
	private $sOutputMode;
	const FILES = 'Files';
	const DIRECTORY = 'Directory';
	public $bRecursive = false;
	public function setRecursive($bRecursive = true) {
		$this->bRecursive = $bRecursive;
	}
	public function setCompress($mCompress = true) {

		if ($mCompress === true) {
			$mCompress = 'gz';
		} elseif (!$mCompress) {
			$mCompress = false;
		} elseif (!is_string($mCompress)) {
			throw (new Exception('You have to define a mode for compress'));
		}
		$this->sCompress = $mCompress;
	}
	public function setInputFile($mFiles) {
		$bCli = (php_sapi_name() == 'cli');

		if ($bCli and $this->mPreInputFiles == STDIN and $mFiles != STDIN) {
			throw (new Exception("Hey, you already defined STDIN,dude"));
		} elseif ($bCli and $mFiles == STDIN) {
			$this->mPreInputFiles = STDIN;
		} else {

			if (is_string($mFiles)) {
				$mFiles = array(
					$mFiles,
				);
			}
			$this->mPreInputFiles = array_merge($this->mPreInputFiles, $mFiles);
		}
		return true;
	}
	public function setOutputFile($sFile) {

		if (!is_string($sFile) and !(php_sapi_name() == 'cli' and $sFile == STDOUT)) {
			throw (new Exception("Accept only string or STDOUT"));
		}
		$this->sPreOutputFile = $sFile;
		return true;
	}
	private function setInputFilePost() {
		$bCli = php_sapi_name() == 'cli';

		if ($bCli and $this->mPreInputFiles == STDIN) {
			$mInputFiles = array(
				STDIN,
			);
		} else {
			$mInputFiles = array();
			foreach ($this->mPreInputFiles as $sPath) {
				$mInputFiles = array_merge($mInputFiles, Common::getFilesByGlob($sPath, $this->bRecursive));
			}
		}
		foreach ($mInputFiles as $sFile) {

			if (!($bCli and $sFile == STDIN) and preg_match("/(.tgz|\.tar\.gz|\.tar\.bz2|\.tar)$/", $sFile, $aMatch)) {

				if (strpos($aMatch[1], 'gz') !== FALSE) {
					$sCompress = 'gz';
				} elseif (strpos($aMatch[1], 'bz2') !== FALSE) {
					$sCompress = 'bz2';
				} elseif (strpos($aMatch[1], 'tar') !== FALSE) {
					$sCompress = false;
				}
				$oTar = new Archive_Tar($sFile, $sCompress);
				foreach ($oTar->listContent() as $aInput) {

					if (empty($aInput['typeflag'])) {
						$this->mInputFiles[] = 'tarz://' . $sFile . '#' . $aInput['filename'];
					}
				}
			} else {
				$this->mInputFiles[] = $sFile;
			}
		}

		if (!$this->mInputFiles) {
			throw (new Exception("Can't match any file"));
		}
		return true;
	}
	private function setOutputFilePost() {

		if (php_sapi_name() == 'cli' and $this->sPreOutputFile == STDOUT) {
			$this->sOutputMode = PHP_Beautifier_Batch::FILES;
		} else {
			$sPath = str_replace(DIRECTORY_SEPARATOR, '/', $this->sPreOutputFile);

			if (!$sPath) {
				$sPath = "./";
			}

			if (substr($sPath, -1) != '/' and !is_dir($sPath)) {
				$this->sOutputMode = PHP_Beautifier_Batch::FILES;

				if (preg_match("/\.(gz|bz2|tar)$/", $sPath, $aMatch)) {
					$this->sCompress = $aMatch[1];
				}
			} else {
				$this->sOutputMode = PHP_Beautifier_Batch::DIRECTORY;
			}
		}
		return true;
	}
	public function process() {

		if (!$this->mPreInputFiles) {
			throw (new Exception('Input file not defined'));
		} else {
			$this->setInputFilePost();
			$this->setOutputFilePost();
		}

		if (!$this->mInputFiles) {
			throw (new Exception(implode(',', $this->mPreInputFiles) . " doesn't match any files"));
		} else {
			return true;
		}
	}
	private function getBatchEngine() {
		$sCompress = ($this->sCompress) ? ucfirst($this->sCompress) : '';
		$sClass = $this->sOutputMode . $sCompress;
		$sClassEngine = 'PHP_Beautifier_Batch_Output_' . $sClass;
		$sClassFile = Common::normalizeDir(dirname(__FILE__)) . 'Batch/Output/' . $sClass . '.php';

		if (!file_exists($sClassFile)) {
			throw (new Exception("Doesn't exists file definition for $sClass ($sClassFile)"));
		} else {
			include_once $sClassFile;

			if (!class_exists($sClassEngine)) {
				throw (new Exception("$sClassFile exists, but $sClassEngine isn't defined"));
			} else {
				return new $sClassEngine($this);
			}
		}
	}
	public function save($sFile = null) {
		$oBatchEngine = $this->getBatchEngine();
		return $oBatchEngine->save();
	}
	public function get() {
		$oBatchEngine = $this->getBatchEngine();
		return $oBatchEngine->get();
	}
	public function show() {
		echo $this->get();
	}
	public function callBeautifier(PHP_Beautifier_Batch_Output $oEngine, $sMethod, $aArgs = array()) {
		return @call_user_func_array(array(
			$this->oBeaut,
			$sMethod,
		), $aArgs);
	}
	public function getInputFiles() {
		return $this->mInputFiles;
	}
	public function getOutputPath() {
		return $this->sPreOutputFile;
	}
}
