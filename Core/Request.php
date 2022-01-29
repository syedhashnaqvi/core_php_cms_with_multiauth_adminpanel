<?php
class Request implements IRequest
{
  function __construct()
  {
    $this->bootstrapSelf();
  }
  private function bootstrapSelf()
  {
    foreach($_SERVER as $key => $value)
    {
      if($key=='REQUEST_URI')$value=rtrim($value,"/");
      $this->{$this->toCamelCase($key)} = $value;
    }
  }
  private function toCamelCase($string)
  {
    $result = strtolower($string);
        
    preg_match_all('/_[a-z]/', $result, $matches);
    foreach($matches[0] as $match)
    {
        $c = str_replace('_', '', strtoupper($match));
        $result = str_replace($match, $c, $result);
    }
    return $result;
  }
  public function getBody()
  {
    if($this->requestMethod === "GET")
    {
      return;
    }

    if ($this->requestMethod == "POST")
    {
      $body = array();
      foreach($_POST as $key => $value)
      {
        $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
      }
      return $body;
    }
  }
  public function getFiles($index = '')
  {
    if(!isset($_FILES[$index]) && $index !==''){
      return false;
    }

    if($index !==''){
      $files[$index] = $_FILES[$index];
    }else{
      $files = $_FILES;
    }
    $files2 = [];
    foreach ($files as $input => $infoArr) {
        $filesByInput = [];
        foreach ($infoArr as $key => $valueArr) {
            if (is_array($valueArr)) { 
                foreach($valueArr as $i=>$value) {
                    $filesByInput[$input][$i][$key] = $value;
                }
            }
            else { 
                $filesByInput[$input] = $infoArr;
                break;
            }
        }
        $files2 = array_merge($files2,$filesByInput);
      }
    $files3 = [];
    foreach($files2 as $key => $file) { 
        if (!$file['error']) $files3[$key] = $file;
    }
    return $files3;
  }
}