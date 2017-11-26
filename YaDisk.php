<?php

class YaDisk {
    
    public function __construct($access_token){
	$this->access_token = $access_token;
	$this->upload_file="";
	$this->upload_link="";
	$this->upload_path="";
    }
    public function getInfo(){
	return $this->request("info");
    }
    public function uploadFile($file, $path=""){
	if (is_file($file)){
	    $this->upload_path = $path;
	    $this->upload_file =  $file;
	    $res = $this->request("get_upload_link");
	    $link = json_decode($res, true);
	    if (isset($link['href'])){
		$this->upload_link = $link['href'];
		return $this->request("upload");
	    }
	    else{
		if (isset($link['error']) && $link['error']=="DiskPathDoesntExistsError"){
		    $res = $this->request("mkdir");
		    $answer = json_decode($res, true);
		    if (isset($answer['created']) or isset($answer['href'])){
			return $this->uploadFile($file, $path);
		    }
		    else {
			return $res;
		    }
		}
		return $res;
	    }
	    
	}
	else {
	    return json_encode([
		"error" => "FileNotFound"
	    ]);
	}
    }
    private function getRequestData($type){
	$data = [];
	switch ($type){
	    case "info":
		$data['url'] = "https://cloud-api.yandex.net/v1/disk/";
		break;
	    case "mkdir":
		$data['url'] = "https://cloud-api.yandex.net/v1/disk/resources/?path=".urlencode("/".$this->upload_path);
		break;
	    case "get_upload_link":
		$data['url'] = "https://cloud-api.yandex.net/v1/disk/resources/upload?path=".
		    urlencode("/".(strlen($this->upload_path)>0?$this->upload_path."/":"").$this->upload_file);
		break;
	    case "upload":
		$data['url'] = $this->upload_link;
		break;
	}
	return $data;
    }


    private function request($type){
	$data = $this->getRequestData($type);
	$url = $data["url"];
	$ch = curl_init();     
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
	    "Accept: application/json",
	    "Content-Type: application/json",
	    "Authorization: OAuth ".$this->access_token
	]);
	if ($type=='mkdir'){
	    curl_setopt($ch, CURLOPT_PUT, 1);
	}
	if ($type=='upload'){
	    $fp = fopen($this->upload_file, "rb");
	    curl_setopt($ch, CURLOPT_PUT, 1);
	    curl_setopt($ch, CURLOPT_HEADER, 1);
	    curl_setopt($ch, CURLOPT_INFILE, $fp);
	    curl_setopt($ch, CURLOPT_INFILESIZE, filesize($this->upload_file));
	}
	curl_setopt($ch, CURLOPT_URL, $url);  
	$result = curl_exec($ch);  
	curl_close($ch);
	if ($type=='upload'){
	    fclose($fp);
	}
	if (strstr($result, "201 Created")){
	    $result = json_encode([
		"created" => "ok",
		"responce" => $result
	    ]);
	}
	return $result;  
    }

}
?>