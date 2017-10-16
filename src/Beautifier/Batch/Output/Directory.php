<?php

namespace Contal\Beautifier\Batch\Output;

class PHP_Beautifier_Batch_Output_Directory extends PHP_Beautifier_Batch_Output {
	public function save() {
		$aInputFiles = $this->oBatch->getInputFiles();
		$sOutputPath = $this->oBatch->getOutputPath();
		$aOutputFiles = Common::getSavePath($aInputFiles, $sOutputPath);
		$oLog = Common::getLog();
		for ($x = 0; $x < count($aInputFiles); $x++) {
			try {
				$this->beautifierSetInputFile($aInputFiles[$x]);
				$this->beautifierProcess();
				Common::createDir($aOutputFiles[$x]);
				$this->beautifierSave($aOutputFiles[$x]);
			} catch (Exception $oExp) {
				$oLog->log($oExp->getMessage(), PEAR_LOG_ERR);
			}
		}
		return true;
	}
	public function get() {
		$aInputFiles = $this->oBatch->getInputFiles();
		$sText = '';
		foreach ($aInputFiles as $sFile) {
			$this->beautifierSetInputFile($sFile);
			$this->beautifierProcess();
			$sText .= $this->beautifierGet() . "\n";
		}
		return $sText;
	}
}
