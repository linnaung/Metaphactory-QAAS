<?php
	
	$postdata = $_POST ?? '';

	//typeahead	
	if(isset($postdata['keywords']) && is_string($postdata['keywords'])) {

		$keywords = "keywords=". addslashes($postdata['keywords']);

		$response = _runEndpoint(
			$keywords,
			"http://ec2-35-175-133-209.compute-1.amazonaws.com/rest/qaas/search"
		);

	
		if($response) {
	
			echo $response;
		} else {
			$response['result'] = "No result found!";
			echo json_encode($response);
		}
	} 

	//create new suggestion	
	if(isset($postdata['label']) && is_string($postdata['label'])) {
	
		$action = $postdata['action'] ?? '';
		
		if(is_string($action) && $action === "create") {

			$label = "label=". addslashes($postdata['label']);
			
			$response = _runEndpoint(
				$label,
				"http://ec2-35-175-133-209.compute-1.amazonaws.com/rest/qaas/create"
			);
			
			$result['success'] = false;
			if($response) {
				$result['success'] = true;
				echo json_encode($result);
			} 
		
		} else {
			
			$response['result'] = "Invalid";
			echo json_encode($response);
		}
	
	}

	function _runEndpoint($postdata, $url)
	{
		
		$acceptFormat = "Accept: application/sparql-results+json";
		$contentType = "Content-Type: application/x-www-form-urlencoded";        
		$userpwd = "admin:i-0931763b2d97a1e1a";	

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
			return (!empty($data)) ? $data : true;
		} 
		return false;
	}


	 
?>
