<?php
namespace App\Admin;
use Core\DB;
use Core\Sessions;
use App\Auth;
use Template;

class MediaController{
    public function __construct(){
       
    }
    
    public function index($request){
        $template = new Template('admin/media/index');
        $template->title('Admin - Media');

        $ls = scandir(storagePath());
        
        $template->request($request);
        $template->mediaFiles(array_diff($ls, array('.', '..')));

        print $template;
    }

    public function upload($request)
    {
        $files = $request->getFiles('mediafiles');
        $has_file = false;
        $uploadFilePath = '';
        if(count($files)>0){
            $uploadFilePath = uploadFile(MEDIA_PATH,$files,'mediafiles');
            if($uploadFilePath['uploadpath']){
                $has_file = true;
                $uploadFilePath = $uploadFilePath['uploadpath'];
                print_r($uploadFilePath);
            }
        }
    }

}