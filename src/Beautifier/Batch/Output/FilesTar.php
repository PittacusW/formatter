<?php

namespace Contal\Beautifier\Batch\Output;

include_once 'Archive/Tar.php';
class PHP_Beautifier_Batch_Output_FilesTar extends PHP_Beautifier_Batch_Output {
	protected $oTar;
	protected $sCompress = false;
	protected $sExt = "tar";
	public function __construct(PHP_Beautifier_Batch $oBatch) {
		parent::__construct($oBatch);
		$sOutput = $this->oBatch->getOutputPath();
		$sOutput = preg_replace("/(\.tar|\.tar\.gz|\.tgz|\.gz|\.tar\.bz2)$/", '', $sOutput) . "." . $this->sExt;
		Common::createDir($sOutput);
		$this->oTar = new Archive_Tar($sOutput, $this->sCompress);
	}
	public function get() {
		throw (new Exception("TODO"));
	}
	public function save() {
		$aInputFiles = $this->oBatch->getInputFiles();
		$aOutputFiles = Common::getSavePath($aInputFiles);
		for ($x = 0; $x < count($aInputFiles); $x++) {
			$this->beautifierSetInputFile($aInputFiles[$x]);
			$this->beautifierProcess();
			$this->oTar->addString($aOutputFiles[$x], $this->beautifierGet());
		}
	}
}
