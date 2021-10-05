# Core PHP CMS with multi-auth and admin panel

An open source content management system build using core PHP to kick start any project in core PHP.


## Helper Functions
```php
// dump and die any object or variable
dd($var);
  
// create path to assets folder App/assets
asset($filePath);

//create a url with base url and provided path
url($path);


//returns root path of project
basePath();


//return storage path of media files
storagePath();


//redirects to given path
redirect($path);


//create a link to route
route($path);


//check if a variable isset then return the variable else empty string;
___($var);



// echo given variable
__($var);

// creates a random string of given length
generateRandomString($length)

//uploads single or multiple files on system and return array containing url of file and message
// with optional parameter of allowed extensions
uploadFile($path,$file,$inputName,$extensions='')


// checks if a directory exits and return path if not then create a new directory at provided path
getDirectory($path);

```

## Admin Template
[***AdminLte***](https://github.com/ColorlibHQ/AdminLTE)


## Rendering Views
[***Xeoncross PHP Template***](http://github.com/Xeoncross/php-template)
