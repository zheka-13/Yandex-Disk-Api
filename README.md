# Yandex-Disk-Api
Simple API Class for Yandex Disk. aInfo, Upload and Download just for now.

usage: 


require_once "YaDisk.php";
$token = "XXXXXXXXXXXXXXXXXXXXXXXX"; // need to obtain from user;<br>
$disk= new YaDisk($token);<br><br>

// get info about disk<br>
$res = $disk->getInfo();<br><br>

//upload file to folder, folder will be created if it doesn't exist<br> 
$res = $disk->uploadFile("file.txt", "new_folder");<br><br>

// just upload file to root folder <br>
$res = $disk->uploadFile("file1.txt");<br><br>

// remove file <br>
$res = $disk->removeFile("oki/test.mp3");

