<?php

namespace Contal\Beautifier\Batch\Output;

class PHP_Beautifier_Batch_Output_Files extends PHP_Beautifier_Batch_Output {
	public function get() {
		$aInputFiles = $this->oBatch->getInputFiles();

		if (count($aInputFiles) == 1) {
			$this->beautifierSetInputFile(reset($aInputFiles));
			$this->beautifierProcess();
			return $this->beautifierGet();
		} else {
			$sText = '';
			foreach ($aInputFiles as $sFile) {
				$this->beautifierSetInputFile($sFile);
				$this->beautifierProcess();
				$sText .= $this->getWithHeader($sFile);
			}
			return $sText;
		}
	}
	private function getWithHeader($sFile) {
		$sNewLine = $this->oBatch->callBeautifier($this, 'getNewLine');
		$sHeader = '- BEGIN OF ' . $sFile . ' -' . $sNewLine;
		$sLine = str_repeat('-', strlen($sHeader) - 1) . $sNewLine;
		$sEnd = '- END OF ' . $sFile . str_repeat(' ', strlen($sHeader) - strlen($sFile) - 12) . ' -' . $sNewLine;
		$sText = $sLine . $sHeader . $sLine . $sNewLine;
		$sText .= $this->beautifierGet();
		$sText .= $sNewLine . $sLine . $sEnd . $sLine . $sNewLine;
		return $sText;
	}
	public function save() {
		$bCli = php_sapi_name() == 'cli';
		$sFile = $this->oBatch->getOutputPath();

		if ($bCli and $sFile == STDOUT) {
			$fp = STDOUT;
		} else {
			$fp = fopen($this->oBatch->getOutputPath(), "w");
		}

		if (!$fp) {
			throw (new Exception("Can't save file $sFile"));
		}
		$sText = $this->get();
		fputs($fp, $sText, strlen($sText));

		if (!($bCli and $fp == STDOUT)) {
			fclose($fp);
		}
		return true;
	}
}
