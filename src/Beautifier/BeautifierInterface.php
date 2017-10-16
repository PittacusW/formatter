<?php

namespace Contal\Beautifier;

interface BeautifierInterface {
	public function process();
	public function show();
	public function get();
	public function save($sFile = null);
}
