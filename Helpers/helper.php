<?php


function dd($variable){

    echo '<pre style="background:#000; color:#00FF00">';

    die(var_dump($variable));

    echo '</pre>';

}



function asset($path){

    echo url("/App/assets/".$path);

}

function getMedia($path){
    echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://").$_SERVER['HTTP_HOST'].BASE_URL."/Uploads/".MEDIA_PATH."/".$path;
}



function url($path=''){

    return sprintf(

      "%s://%s",

      isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',

      $_SERVER['HTTP_HOST'].BASE_URL.$path

    );

}



function basePath(){

    return __DIR__."/../";

}

function storagePath(){
    return basePath()."/Uploads".MEDIA_PATH;
}



function redirect($path){

    $url = url($path);

    header('Location:'.$url);exit;

}



function route($path){

    echo url($path);

}



function __($var){

    echo ___($var);

}



function ___($var){

    return isset($var)? $var:'';

}



function generateRandomString($length = 10) {

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    $charactersLength = strlen($characters);

    $randomString = '';

    for ($i = 0; $i < $length; $i++) {

        $randomString .= $characters[rand(0, $charactersLength - 1)];

    }

    return $randomString;

}





function uploadFile($path,$file,$inputName,$extensions=''){

    $dir = getDirectory($path);

    if($dir){

        if(is_array($file[$inputName][0])){

            $results=[];

            foreach($file as $key=>$value){

                foreach($value as $valueItem){

                    $results[] = uploadsinglefile($dir,[$key=>$valueItem],$key,$extensions);

                }

            }

            return $results;

        }

        return uploadsinglefile($dir,$file,$inputName,$extensions);

    }

    return ['uploadpath'=>null,'message'=>"Unable to create or locate directory."];

}



function uploadsinglefile($dir,$file,$inputName,$extensions){

    global $allowedFileExtensions;

    $fileTmpPath = $file[$inputName]['tmp_name'];

    $fileName = $file[$inputName]['name'];

    $fileSize = $file[$inputName]['size'];

    $fileType = $file[$inputName]['type'];

    $fileNameCmps = explode(".", $fileName);

    $fileExtension = strtolower(end($fileNameCmps));

    // sanitize file-name

    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;



    // check if file has one of the following extensions

    $allowedfileExtensions = $extensions==='' ? $allowedFileExtensions : $extensions;

    if (in_array($fileExtension, $allowedfileExtensions))

    {

        // directory in which the uploaded file will be moved

        $uploadFileDir = basePath().$dir;

        $dest_path = $uploadFileDir . $newFileName;

        if(move_uploaded_file($fileTmpPath, $dest_path)) 

        {

            $message ='File is successfully uploaded.';

        }

        else 

        {

            $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';

        }

        return ['uploadpath'=>$dir.$newFileName,'message'=>$message];

    }

    else

    {

        $message = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);

        return ['uploadpath'=>null,'message'=>$message];

    }

}



function getDirectory($path){

    $dirPath = "/Uploads/".$path;

    $path = basePath().$dirPath;

    if(!file_exists($path)){

        $dir = mkdir($path, 0777, true); 

        return ($dir)? $dirPath: false;

    }

    return $dirPath;

}


