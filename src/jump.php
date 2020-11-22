<?php
	
	$postdata = $_POST ?? '';
	if(isset($postdata['keywords']) && is_string($postdata['keywords'])) {

		$response = _runEndpoint($postdata['keywords']);
	
		if($response) {
	
			echo $response;
		} else {
			$response['result'] = "keywords not fount";
			echo json_encode($response);
		}
	} 

	function _runEndpoint($keywords)
	{
		
		$keywords = addslashes($keywords);        

		$acceptFormat = "Accept: application/sparql-results+json";
		$contentType = "Content-Type: application/x-www-form-urlencoded";        
        
		$url = "http://ec2-35-175-133-209.compute-1.amazonaws.com/rest/qaas/search";
		
		$userpwd = 'admin:i-0931763b2d97a1e1a';
		$postdata = "keywords=$keywords";
        
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERPWD, $userpwd);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [$acceptFormat, $contentType]);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
		$data = curl_exec($ch);
        	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
   	     
		curl_close($ch);
		if ($code === 200 || $code === 204) {
			return $data;
		} 
		return false;
	}


	 
?>
