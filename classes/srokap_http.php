<?php
class srokap_http {
	/**
	 * Tries to fetch contents of the URL.
	 * @param string $url URL to fetch
	 * @return string|boolean returns response contents on success or false on failure
	 */
	private static function getUrl($url) {
		//try url fopen
		$file = fopen($url, 'r');
		if ($file!==false) {
			$result = stream_get_contents($file);
			fclose($file);
			return $result;
		}
		//try curl
		if (extension_loaded('curl')) {
			$ch = curl_init($url);
			curl_setopt_array($ch, array(
				CURLOPT_HEADER => false,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYPEER => false,
			));
			$result = curl_exec($ch);
			curl_close($ch);
			if ($result!==false) {
				return $result;
			}
		}
		return false;
	}
	
	/**
	 * Tries to fetch contents of the URL.
	 * @param string $url URL to fetch
	 * @param resource $file file handle to write response to
	 * @return string|boolean returns response contents on success or false on failure
	 */
	private static function getUrlToFile($url, $file) {
// 		//try url fopen
// 		$file = fopen($url, 'r');
// 		if ($file!==false) {
// 			$result = stream_get_contents($file);
// 			fclose($file);
// 			return $result;
// 		}
// 		//try curl
// 		if (extension_loaded('curl')) {
// 			$ch = curl_init($url);
// 			curl_setopt_array($ch, array(
// 					CURLOPT_HEADER => false,
// 					CURLOPT_RETURNTRANSFER => true,
// 					CURLOPT_SSL_VERIFYPEER => false,
// 			));
// 			$result = curl_exec($ch);
// 			curl_close($ch);
// 			if ($result!==false) {
// 				return $result;
// 			}
// 		}
// 		return false;
	}
	
	/**
	 * @param string $url
	 * @throws IOException
	 * @return array with two items, content and metadata
	 */
	static function getUrlWithMetadata($url) {
		$context = stream_context_create(array(
			'http' => array(
				'method' => 'GET',
				'max_redirects' => '0',
				'ignore_errors' => '0',
			)
		));
		$stream = fopen($url, 'r', false, $context);
		if ($stream===false) {
			throw new IOException("Unable to open URL via stream context wrapper: ".$url);
		}
	
		$content = stream_get_contents($stream);
		$meta = stream_get_meta_data($stream);
		fclose($stream);
	
		return array(
			$content,
			$meta,
		);
	}
}