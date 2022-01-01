<?php
use root\Config;
// dump variable and die
function dd($variable){
    echo '<pre style="background:#000; color:#00FF00">';
    die(var_dump($variable));
    echo '</pre>';
}

// print asset path
function asset($path){
    echo url("/App/assets/".$path);
}

// print file path
function getMedia($path){
    echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://").$_SERVER['HTTP_HOST'].Config::get('base_url')."/Uploads/".Config::get('media_path')."/".$path;
}

// return url with protocol and base path
function url($path=''){
    return sprintf(
      "%s://%s",
      isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
      $_SERVER['HTTP_HOST'].Config::get('base_url').$path
    );
}

// return base path
function basePath(){
    return __DIR__."/../";
}

// return media storage path
function storagePath(){
    return basePath()."/Uploads".Config::get('media_path');
}

// redirect to given location
function redirect($path){
    $url = url($path);
    header('Location:'.$url);exit;
}

function route($path){
    echo url($path);
}

// print variable with isset check
function __($var){
    echo ___($var);
}


// checks if a variable is set or note
function ___($var){
    return isset($var)? $var:'';
}

// generate random string of 10 or specified length
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


// upload single or multiple files
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

// upload file
function uploadsinglefile($dir,$file,$inputName,$extensions){
    $allowedFileExtensions = Config::get('allowed_ext');
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
        $message = 'Upload failed. Allowed file types: ' . implode(',', $allowedFileExtensions);
        return ['uploadpath'=>null,'message'=>$message];
    }
}

// create directories at given path and return
function getDirectory($path){
    $dirPath = "/Uploads/".$path;
    $path = basePath().$dirPath;
    if(!file_exists($path)){
        $dir = mkdir($path, 0777, true); 
        return ($dir)? $dirPath: false;
    }
    return $dirPath;
}

// sends email
use PHPMailer\PHPMailer\PHPMailer;
function sendEmail($fromName,$fromEmail,$toName,$toEmail,$subject,$body){
    $body = html_entity_decode($body);
    $mail = new PHPMailer(false);
    try {
        $mail->SMTPDebug = 0;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'mail.viralwebbs.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'smtp@viralwebbs.com';                     //SMTP username
        $mail->Password   = 'viral123_';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($toEmail, $toName);     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = html_entity_decode($body);
        // $mail->AltBody = 'https://viralwebbs.com';

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// return current route method
function getCurrentMethod(){
    return $GLOBALS['method'];
}

// return current route controller
function getCurrentController(){
    return $GLOBALS['controller'];
}

// removes element from array
function except($remove,$data){
    return array_diff_key($data, array_flip($remove));
}

// check password for validation
function validPassword($password){
    if(preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{10,}$/', $password))
        return true;
    return false;
}

// return active route
function activeRoute($current,$navLink)
{
    if($current == $navLink){
        echo " active";
    }else{
        echo "";
    }
    return false;
}