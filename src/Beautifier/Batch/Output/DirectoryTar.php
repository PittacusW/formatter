<?php

namespace Contal\Beautifier\Batch\Output;

include_once 'Archive/Tar.php';
abstract class PHP_Beautifier_Batch_Output_DirectoryTar extends PHP_Beautifier_Batch_Output {
	public function save() {
		$aInputFiles = $this->oBatch->getInputFiles();
		$sOutputPath = $this->oBatch->getOutputPath();
		$aOutputFiles = Common::getSavePath($aInputFiles, $sOutputPath);
		for ($x = 0; $x < count($aInputFiles); $x++) {
			unset($oTar);
			$oTar = $this->getTar($aOutputFiles[$x]);
			$this->beautifierSetInputFile($aInputFiles[$x]);
			$this->beautifierProcess();
			Common::createDir($aOutputFiles[$x]);
			$oTar->addString(basename($aOutputFiles[$x]), $this->beautifierGet());
		}
		return true;
	}
	protected function getTar($sFileName) {
	}
}
