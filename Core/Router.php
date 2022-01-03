<?php

use Core\Sessions;
use Core\Config;
class Router
{
  private $request,$routes,$params;
  private $supportedHttpMethods = array(
    "GET",
    "POST"
  );
  
  function __construct(IRequest $request)
  {
    include('routes.php');
    $route = $this->authRoutes($routes, $request);
    $this->getRoutes($route, $request);
  }

  private function getRoutes($routes, IRequest $request)
  {
      if($routes ===null){
        self::defaultRequestHandler();
        exit();
      }
      $this->request = $request;
      $tRoutes=[];
      array_walk($routes,function($item,$key) use(&$tRoutes){
        $tRouteItem=[];
        array_walk($item, function($value, $k) use(&$tRouteItem){ $k = strtolower($k); $tRouteItem[$k]=$value; });
        $tRoutes[$key] = $tRouteItem;
      });
      $this->routes = $tRoutes;
  }

  /**
   * Removes trailing forward slashes from the right of the route.
   * @param route (string)
   */
  private function formatRoute($route)
  {
    $result = rtrim($route, '/');
    if ($result === '')
    {
      return '/';
    }
    return $result;
  }

  private function invalidMethodHandler()
  {
    header("{$this->request->serverProtocol} 405 Method Not Allowed");
    echo "<h1 style='text-align:center; margin-top:20%;color:#BBB;font-family:Arial'><span style='font-size:75px'>405</span><br>Method Not Allowd!</h1>";
  }

  private function defaultRequestHandler()
  {
    header('HTTP/1.0 404 Not Found', true, 404);
    echo "<h1 style='text-align:center; margin-top:20%;color:#BBB;font-family:Arial'><span style='font-size:75px'>404</span><br>Page Not Found!<br><p style='font-size:16px'>Requested resource not found on this server.</p></h1>";
  }

  private function methodNotExistsHandler()
  {
    header('HTTP/1.0 404 Method Not Exists', true, 404);
    echo "<h1 style='text-align:center; margin-top:20%;color:#BBB;font-family:Arial'><span style='font-size:75px'>404</span><br>Method Not Found!<br><p style='font-size:16px'>Requested method not found on this server.</p></h1>";
  }

  private function csrfMissingHandler()
  {
    header('HTTP/1.0 403 CSRF Token Missing', true, 403);
    echo "<h1 style='text-align:center; margin-top:20%;color:#BBB;font-family:Arial'><span style='font-size:75px'>403</span><br>CSRF Token Missing!<br><p style='font-size:16px'>Csrf token is missing in request.</p></h1>";
  }

  private function csrfMismatchHandler()
  {
    header('HTTP/1.0 403 CSRF Token Mismatch', true, 403);
    echo "<h1 style='text-align:center; margin-top:20%;color:#BBB;font-family:Arial'><span style='font-size:75px'>403</span><br>CSRF Token Mismatch!<br><p style='font-size:16px'>Csrf token mismatch on server.</p></h1>";
  }

  /**
   * Resolves a route
   */
  function resolve()
  {
    $methodDictionary = $this->routes;
    if(!$methodDictionary) exit();
    if(key($methodDictionary) == 'redirect'){
      header('Location:'.$methodDictionary['redirect'][0]);
      exit();
    }
    $formatedRoute = $this->formatRoute($this->request->requestUri);
    $method = isset($methodDictionary[$formatedRoute])?$methodDictionary[$formatedRoute]:null;
    if(is_null($method))
    {
      $this->defaultRequestHandler();
      return;
    }
    if(!in_array(strtolower($this->request->requestMethod),array_keys($method))){
      $this->invalidMethodHandler();
      return;
    }
    if($this->request->requestMethod == "POST"){
      if(!isset($this->request->getBody()['csrf'])){
        $this->csrfMissingHandler();
        return;
      }else if(Sessions::get('csrf')['csrf'] != $this->request->getBody()['csrf']){
        $this->csrfMismatchHandler();
        return;
      }

    }
    
    if(!Sessions::get('csrf') || time() > Sessions::get('csrf')['expiry']){
      $csrf = generateRandomString(20);
      Sessions::set('csrf',true,['csrf'=>$csrf,'expiry'=>time()+20]);
    }
    $this->request->csrf = Sessions::get('csrf')['csrf'];
    $this->request->params = $this->params;
    $controllerMethod = explode("@",$method[strtolower($this->request->requestMethod)]);
    $GLOBALS['controller']=$controllerMethod[0];
    $GLOBALS['method']=$controllerMethod[1];
    $callback = @call_user_func_array($this->loadClassMethod($method[strtolower($this->request->requestMethod)]), array($this->request));
  }

  function __destruct()
  {
    $this->resolve();
  }

  private function setDS($path){
    return str_replace("\\",DIRECTORY_SEPARATOR,$path);
  }

  private function loadClassMethod($path){
    $classmethod = explode('@',$path);
    if(count($classmethod)<2){
      throw new ErrorException("Invalid Route Method");
    }
    include($this->setDS($classmethod[0]).".php");
    $object = new $classmethod[0];
    $method = $classmethod[1];
    return [$object,$method];
  }

  private function authRoutes($routes, IRequest $request)
  {
    $keys = array_keys($routes);
    $request->requestUri = (str_replace(Config::get('base_url'),"",$request->requestUri))==="" ?"/":str_replace(Config::get('base_url'),"",$request->requestUri);
    foreach($keys as $key){
      if(strpos(strtolower($key) , 'auth:') !==false){
        $authenticated = explode("/",$request->requestUri)[1];
        if(Sessions::exist($authenticated) && strpos($request->requestUri,'/login') !== false){
          header('Location:'.str_replace("login",'',Config::get('base_url').$request->requestUri));
        }
        foreach($routes[$key] as $k=>$newRoute){
            $dynamicRoute = $this->dynamicRoute($k,$newRoute,$request);
            if($dynamicRoute){
              $request->requestUri = $dynamicRoute;
            }
          if($k == $request->requestUri)
          if(Sessions::exist($authenticated) && isset($routes[$key][$request->requestUri])){
            return $routes[$key];
          }else{
            return ['redirect'=>[url("/".$authenticated.'/login')]];
          }
        }
      }else if(isset($routes[$key]) && !empty($request->requestUri)){
        $dynamicRoute = $this->dynamicRoute($key,$routes[$key],$request);
        if($dynamicRoute){
          $request->requestUri = $dynamicRoute;
        }
        if($key == $request->requestUri)
        return [$key=>$routes[$key]];
      }else{
        return [$key=>$routes[$key]];
      }
    }
  }

  private function dynamicRoute($key, $route, IRequest $request){
    $keyArr = explode('/',$key);
    $uri = explode('/',$request->requestUri);
    $newKey = '';
    $routeParams =[];
    foreach($keyArr as $index=>$arrItem){
      preg_match('/{([^}]*)}/', $arrItem, $matches);
      if(count($matches)>0){
        if(isset($uri[$index])) $newKey.=$uri[$index]."/";
        if(isset($uri[$index])) $routeParams[$matches[1]] =$uri[$index];

      }else{
        $newKey.=$arrItem."/";
      }
    }
    
    $newKey = rtrim($newKey,"/");
    if($request->requestUri === $newKey){
      if(count($routeParams)) $this->params = (object)$routeParams;
      return $key;
    }
    return false;
  }

}