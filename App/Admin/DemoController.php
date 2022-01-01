<?php
namespace App\Admin;
use root\DB;
use root\Sessions;
use App\Auth;
use Template;

class DemoController{
    public function __construct(){
       
    }
    
    public function index($request){
        $demos = DB::table('demo')->select()->get();
        $template = new Template('admin/demos/index');
        $template->title('Admin - Demo');
        $template->demos($demos);
        $template->request($request);

        print $template;
    }

    public function create($request){
        $template = new Template('admin/demos/create');
        $template->title('Admin - Demo/Create');
        $template->request($request);
        print $template;
    }

    public function store($request){
        $data = $request->getBody();
        unset($data['csrf']);
        $demos = DB::table('demo')->insert($data);
        $message ='';
        if($demos>0){
            $message = ['type'=>'success','msg'=>'Demo created successfully!'];
        }else{
            $message = ['type'=>'danger','msg'=>'Something went wrong!'];
        }
        Sessions::set('flash_message',true,$message);
        redirect('/admin/demos');
    }

    public function edit($request)
    {
        $demo = DB::table('demo')->select()->where(['id'=>$request->params->id])->first();
        $template = new Template('admin/demos/edit');
        $template->title('Admin - Demo/Edit');
        $template->demo($demo);
        $template->request($request);

        print $template;
    }

    public function update($request)
    {
        $files = $request->getFiles('imageurl');
        $has_file = false;
        $uploadFilePath = '';
        if(count($files)>0){
            $uploadFilePath = uploadFile('myfolder/',$files,'imageurl');
            if($uploadFilePath['uploadpath']){
                $has_file = true;
                $uploadFilePath = $uploadFilePath['uploadpath'];
            }
        }
        $data = $request->getBody();
        unset($data['csrf']);
        ($has_file) ? $data['imageurl']=$uploadFilePath:'';
        $demos = DB::table('demo')->update($data,$request->params->id);
        $message ='';
        if($demos>0){
            $message = ['type'=>'success','msg'=>'Demo updated successfully!'];
        }else{
            $message = ['type'=>'danger','msg'=>'Something went wrong!'];
        }
        Sessions::set('flash_message',true,$message);
        redirect('/admin/demos');
    }

    public function destroy($request)
    {
        $id = $request->getBody()['id'];
        $demo = DB::table('demo')->destroy('id',$id);
        $message ='';
        if($demo>0){
            $message = ['type'=>'success','msg'=>'Demo deleted successfully!'];
        }else{
            $message = ['type'=>'danger','msg'=>'Something went wrong!'];
        }
        
        Sessions::set('flash_message',true,$message);
        redirect('/admin/demos');
    }


}