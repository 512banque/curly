<?php
function curly($url = null, $options = array()) {
	$output = array();
	$url = (!empty($options['url'])) ? $options['url'] : $url;
	if(empty($url)) return false;
	if(empty($options['useragent'])) $options['useragent'] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36";
	if(empty($options['referer'])) $options['referer'] = $url;
	if(empty($options['cookies_file'])) $options['cookies_file'] = uniqid().'cookies.txt';;
	
	//Initialise une session CURL
	$ch = curl_init($url);
	//cookies
	if(!empty($options['cookies_file']))
	{
		curl_setopt($ch, CURLOPT_COOKIEJAR, $options['cookies_file']); 
		curl_setopt($ch, CURLOPT_COOKIEFILE, $options['cookies_file']); 
	}
	//fin cookies
	// post
	if(!empty($options['postfields']))
	{
		curl_setopt($ch, CURLOPT_POST, 1);
		if(is_array($options['postfields']))
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($options['postfields']));
		else
			curl_setopt($ch, CURLOPT_POSTFIELDS, $options['postfields']);
	}
	//fin post
	if (preg_match('`^https://`i', $url)) 
	{ 
	  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
	}
	// custom headers
	if(!empty($options['headers']))
		curl_setopt($ch, CURLOPT_HTTPHEADER, $options['headers']);
	//proxy
	if(!empty($options['proxy'])) {
		// we are using a proxy
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true); 

		// proxy address
		curl_setopt($ch, CURLOPT_PROXY, $options['proxy']['host']); 

		// if proxy needs authenticate
		if (isset($options['proxy']['login'])&&!empty($options['proxy']['login']))
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $options['proxy']['login']); 
	}
	curl_setopt($ch, CURLOPT_USERAGENT, $options['useragent']);
	curl_setopt($ch, CURLOPT_REFERER, $options['referer']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

	// add arbitrary info
	if(isset($options['setopt'])) {
		foreach ($options['setopt'] as $key => $value) {
			curl_setopt($ch, $key, $value);
		}
	}

	$result = curl_exec($ch);
	curl_close($ch);

	$output['raw'] = $result;
	$output['xpath'] = $output['delimiters'] = $output['preg_match'] = '';

	if(!empty($options['delimiters'])) {
		$new_string = substr($result, strpos($result, $options['delimiters'][0])+strlen($options['delimiters'][0]));
		$output['delimiters'] = substr($new_string, 0, strpos($new_string, $options['delimiters'][1]));
	}

	if(!empty($options['preg_match'])) {

	}

	if(!empty($options['xpath'])) {
		$html = new DOMDocument();
		@$html->loadHTML($result);
		$xpath = new DOMXPath( $html );

		$nodelist = $xpath->query($options['xpath']);
		$output['xpath'] = @$result = $nodelist->item(0)->nodeValue;
	}
	return $output;
}
