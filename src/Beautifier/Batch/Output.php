<?php

abstract class Batch_Output {
	protected $oBatch;
	public function __construct(Batch $oBatch) {
		$this->oBatch = $oBatch;
	}
	protected function beautifierSetInputFile($sFile) {
		return $this->oBatch->callBeautifier($this, 'setInputFile', array(
			$sFile,
		));
	}
	protected function beautifierProcess() {
		return $this->oBatch->callBeautifier($this, 'process');
	}
	protected function beautifierGet() {
		return $this->oBatch->callBeautifier($this, 'get');
	}
	protected function beautifierSave($sFile) {
		return $this->oBatch->callBeautifier($this, 'save', array(
			$sFile,
		));
	}
	public function get() {
	}
	public function save() {
	}
}
