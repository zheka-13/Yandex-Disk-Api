# Yandex-Disk-Api
Simple API Class for Yandex Disk. aInfo, Upload and Download just for now.

usage: 


require_once "YaDisk.php";
$token = "XXXXXXXXXXXXXXXXXXXXXXXX"; // need to obtain from user;
$disk= new YaDisk($token);

// get info about disk
$res = $disk->getInfo();

//upload file to folder, folder will be created if it doesn't exist 
$res = $disk->uploadFile("file.txt", "new_folder");

// just upload file to root folder 
$res = $disk->uploadFile("file1.txt");

// remove file 
$res = $disk->removeFile("oki/test.mp3");

