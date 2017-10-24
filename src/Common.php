<?php
namespace Contal;


class Common {

	
	public static function normalizeDir($sDir) {
		$sDir = str_replace(DIRECTORY_SEPARATOR, '/', $sDir);
		
		if (substr($sDir, -1) != '/') {
			$sDir.= '/';
		}
		return $sDir;
	}
	
	public static function getFilesByPattern($sDir, $sFilePattern, $bRecursive = false) {
		
		if (substr($sDir, -1) == '/') {
			$sDir       = substr($sDir, 0, -1);
		}
		$dh         = @opendir($sDir);
		
		if (!$dh) {
			throw (new Exception("Cannot open directory '$sDir'"));
		}
		$matches = array();
		while ($entry   = @readdir($dh)) {
			
			if ($entry == '.' or $entry == '..') {
				continue;
			}
			elseif (is_dir($sDir . '/' . $entry) and $bRecursive) {
				$matches = array_merge($matches, Common::getFilesByPattern($sDir . '/' . $entry, $sFilePattern, $bRecursive));
			}
			elseif (preg_match("/" . $sFilePattern . "$/", $entry)) {
				$matches[]         = $sDir . "/" . $entry;
			}
		}
		return $matches;
	}
	
	public static function createDir($sFile) {
		$sDir = dirname($sFile);
		
		if (file_exists($sDir)) {
			return true;
		}

		else {
			$aPaths       = explode('/', $sDir);
			$sCurrentPath = '';
			
			foreach ($aPaths as $sPartialPath) {
				$sCurrentPath.= $sPartialPath . '/';
				
				if (file_exists($sCurrentPath)) {
					continue;
				}

				else {
					
					if (!@mkdir($sCurrentPath)) {
						throw (new Exception("Can't create directory '$sCurrentPath'"));
					}
				}
			}
		}
		return true;
	}
	
	public static function getSavePath($aFiles, $sPath     = './') {
		$sPath     = Common::normalizeDir($sPath);
		$sPrevious = '';
		$iCut      = 0;
		
		foreach ($aFiles as $i         => $sFile) {
			$sFile     = preg_replace("/^.*?#/", '', $sFile);
			$aFiles[$i]           = $sFile;
			
			if (!$sPrevious) {
				$sPrevious = dirname($sFile);
				continue;
			}
			$aPreviousParts = explode("/", $sPrevious);
			$aCurrentParts  = explode("/", dirname($sFile));
			for ($x              = 0;$x < count($aPreviousParts);$x++) {
				
				if ($aPreviousParts[$x] != $aCurrentParts[$x]) {
					$sPrevious = implode("/", array_slice($aPreviousParts, 0, $x));
				}
			}
		}
		$iCut      = strlen($sPrevious);
		$aPathsOut = array();
		
		foreach ($aFiles as $sFile) {
			$sFileOut  = preg_replace("/^(\w:\/|\.\/|\/)/", "", substr($sFile, $iCut));
			$aPathsOut[]           = $sPath . $sFileOut;
		}
		return $aPathsOut;
	}
	
	public static function getFilesByGlob($sPath, $bRecursive = false) {
		
		if (!$bRecursive) {
			return glob($sPath);
		}

		else {
			$sDir       = (dirname($sPath)) ? realpath(dirname($sPath)) : realpath('./');
			$sDir       = Common::normalizeDir($sDir);
			$sDir       = substr($sDir, 0, -1);
			$sGlob      = basename($sPath);
			$dh         = @opendir($sDir);
			
			if (!$dh) {
				throw (new Exception("Cannot open directory '$sPath'"));
			}
			$aMatches = glob($sDir . '/' . $sGlob);
			while ($entry    = @readdir($dh)) {
				
				if ($entry == '.' or $entry == '..') {
					continue;
				}
				elseif (is_dir($sDir . '/' . $entry)) {
					$aMatches = array_merge($aMatches, Common::getFilesByGlob($sDir . '/' . $entry . '/' . $sGlob, true));
				}
			}
			return $aMatches;
		}
	}
	
	public static function wsToString($sText) {
		return str_replace(array(
			"\r",
			"\n",
			"\t"
		) , array(
			'\r',
			'\n',
			'\t'
		) , $sText);
	}
}
