<?php
class srokap_zip {
	/**
	 * Extracts whole name index from archive
	 * @param string $path
	 * @return array|false array of filepatths in archive or false on failure
	 */
	static function getArchiveNameIndex($path) {
		$result = false;
		$zip = new ZipArchive();
		if ($zip->open($path)) {
			$result = array();
			for ($i = 0; $i<$zip->numFiles; $i++) {
				$result[$i] = $zip->getNameIndex($i);
			}
		}
		$zip->close();
		return $result;
	}
}