<?php
namespace Contal;

use Contal\Beautifier;
use Contal\Filter;

class NewLinesFilter extends Filter {

	
	protected $aSettings      = array(
		'before'                => false,
		'after'                => false
	);
	
	protected $sDescription   = 'Add new lines after or before specific contents';
	
	private $aBeforeToken   = array();
	
	private $aBeforeContent = array();
	
	private $aAfterToken    = array();
	
	private $aAfterContent  = array();
	
	public function __construct(Beautifier $oBeaut, $aSettings      = array()) {
		parent::__construct($oBeaut, $aSettings);
		$this->addSettingDefinition('before', 'text', 'List of contents to put new lines before, separated by colons');
		$this->addSettingDefinition('after', 'text', 'List of contents to put new lines after, separated by colons');
		
		if (!empty($this->aSettings['before'])) {
			
			foreach ($this->aSettings['before'] as $sBefore) {
				
				if (defined($sBefore)) {
					$this->aBeforeToken[] = constant($sBefore);
				}

				else {
					$this->aBeforeContent[] = $sBefore;
				}
			}
		}
		
		if (!empty($this->aSettings['after'])) {
			
			foreach ($this->aSettings['after'] as $sAfter) {
				
				if (defined($sAfter)) {
					$this->aAfterToken[] = constant($sAfter);
				}

				else {
					$this->aAfterContent[] = $sAfter;
				}
			}
		}
		$this->oBeaut->setNoDeletePreviousSpaceHack();
	}
	
	public function __call($sMethod, $aArgs) {
		$iToken   = $this->aToken[0];
		$sContent = $this->aToken[1];
		
		if (in_array($sContent, $this->aBeforeContent) or in_array($iToken, $this->aBeforeToken)) {
			$this->oBeaut->addNewLineIndent();
		}
		
		if (in_array($sContent, $this->aAfterContent) or in_array($iToken, $this->aAfterToken)) {
			$this->oBeaut->setBeforeNewLine($this->oBeaut->sNewLine . '');
		}
		return Filter::BYPASS;
	}
}
