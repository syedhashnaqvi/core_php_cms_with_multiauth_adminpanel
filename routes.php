<?php

$routes = [
    //authenticated admin routes
    'auth:admin'=>[
        '/admin/logout'=>['post'=>'App\Admin\AdminController@logout'],
        '/admin'=>['get'=>'App\Admin\AdminController@dashboard'],
        '/admin/demos'=>['get'=>'App\Admin\DemoController@index'],
        '/admin/demos/create'=>['get'=>'App\Admin\DemoController@create'],
        '/admin/demos/store'=>['post'=>'App\Admin\DemoController@store'],
        '/admin/demos/edit/{id}'=>['get'=>'App\Admin\DemoController@edit'],
        '/admin/demos/update/{id}'=>['post'=>'App\Admin\DemoController@update'],
        // '/admin/demos/edit/{id}/abc/{slug}'=>['get'=>'App\Admin\DemoController@edit'],
        '/admin/demos/destroy'=>['post'=>'App\Admin\DemoController@destroy'],
        
        //Media library
        '/admin/media'=>['get'=>'App\Admin\MediaController@index'],
        '/admin/media/upload'=>['post'=>'App\Admin\MediaController@upload'],
    ],

    //authenticated user routes
    'auth:user'=>[
        '/user'=>['get'=>'App\Admin\AdminController@dashboard'],
    ],

    //web routes
    '/'=>['get'=>'App\Site\SiteController@homePage'],
    '/user/login'=>['get'=>'App\Site\SiteController@login','post'=>'App\Site\SiteController@login_verify'],

    //admin routes
    '/admin/login'=>['get'=>'App\Admin\AdminController@login','post'=>'App\Admin\AdminController@login_verify'],
    '/admin/edit/{id}'=>['get'=>'App\Admin\DemoController@edit'],

];