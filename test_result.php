<?php

	echo "<pre>";
	print_r($_POST);
	$data=$_POST;
	
	if(!empty($data)){
		$url= $data['url'];		
		post_to_url($url,$data);
 
	}
	function post_to_url($url, $data) {
		 $fields = '';
		   foreach($data as $key => $value) { 
		  $fields .= $key . '=' . $value . '&'; 
	   }
	   rtrim($fields, '&');
	   $post = curl_init();
	   curl_setopt($post, CURLOPT_URL, $url);
	   curl_setopt($post, CURLOPT_POST, count($data));
	   curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
	   curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
	   $result = curl_exec($post);
	   header('Content-Type: text/plain');
		echo $result;
	   curl_close($post);
	}

