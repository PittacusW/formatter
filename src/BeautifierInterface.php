<?php
namespace Contal;

interface BeautifierInterface {
	
	public function process();
	
	public function show();
	
	public function get();
	
	public function save($sFile = null);
}
