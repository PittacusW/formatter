<?php
namespace Contal;

use Contal\Beautifier;
abstract 
class Filter {

	
	protected $oBeaut;
	
	protected $aFilterTokenFunctions = array();
	
	protected $aSettings             = array();
	
	protected $aSettingsDefinition   = array();
	
	protected $sDescription          = 'Filter for Beautifier';
	const BYPASS                 = 'BYPASS';
	
	protected $bOn                   = true;
	
	protected $aToken                = false;
	
	public function __construct(Beautifier $oBeaut, $aSettings             = array()) {
		$this->oBeaut          = $oBeaut;
		
		if ($aSettings) {
			$this->aSettings       = $aSettings;
		}
	}
	
	protected function addSettingDefinition($sSetting, $sType, $sDescription) {
		$this->aSettingsDefinition[$sSetting]                       = array(
			'type' => $sType,
			'description' => $sDescription
		);
	}
	
	public function getName() {
		return str_ireplace('Filter_', '', get_class($this));
	}
	final 
	public function on() {
		$this->bOn = true;
	}
	
	public function off() {
		$this->bOn = false;
	}
	final 
	public function getSetting($sSetting) {
		return (array_key_exists($sSetting, $this->aSettings)) ? $this->aSettings[$sSetting] : false;
	}
	final 
	public function setSetting($sSetting, $sValue) {
		
		if (array_key_exists($sSetting, $this->aSettings)) {
			$this->aSettings[$sSetting]              = $sValue;
		}
	}
	
	public function handleToken($token) {
		$this->aToken = $token;
		
		if (!$this->bOn) {
			return false;
		}
		$sMethod = $sValue  = false;
		
		if (array_key_exists($token[0], $this->aFilterTokenFunctions)) {
			$sMethod = $this->aFilterTokenFunctions[$token[0]];
		}
		elseif ($this->oBeaut->getTokenFunction($token[0])) {
			$sMethod = $this->oBeaut->getTokenFunction($token[0]);
		}
		$sValue  = $token[1];
		
		if ($sMethod) {
			
			if ($this->oBeaut->iVerbose > 5) {
				echo $sMethod . ":" . trim($sValue) . PHP_EOL;
			}
			return ($this->$sMethod($sValue) !== Filter::BYPASS);
		}

		else {
			$this->oBeaut->add($token[1]);
			return true;
		}
		return false;
	}
	
	public function __call($sMethod, $aArgs) {
		return Filter::BYPASS;
	}
	
	public function preProcess() {
	}
	
	public function postProcess() {
	}
	
	public function __sleep() {
		return array(
			'aSettings'
		);
	}
	
	public function getDescription() {
		return $this->sDescription;
	}
	
	public function __toString() {
		$sOut = 'Filter:      ' . $this->getName() . "\n" . "Description: " . $this->getDescription() . "\n";
		
		if (!$this->aSettingsDefinition) {
			$sOut.= "Settings:    No declared settings";
		}

		else {
			$sOut.= "Settings:\n";
			
			foreach ($this->aSettingsDefinition as $sSetting => $aSettings) {
				$sOut.= sprintf("- %s : %s (type %s)\n", $sSetting, $aSettings['description'], $aSettings['type']);
			}
		}
		return $sOut;
	}
}
