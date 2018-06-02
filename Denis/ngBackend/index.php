<?php

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");         

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: "
                . "{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

}

include_once('ngActions.php');

//ESSENTIAL FUNCTIONS

header( 'content-type: text/html; charset=utf-8' );

$link = mysqli_connect(
        "shareddb-f.hosting.stackcp.net",
        "ngDenisWebSite-323631e7",
        "YufEbJmvZahx",
        "ngDenisWebSite-323631e7");
$link->set_charset("utf8");

if(mysqli_connect_errno()){
    die(mysqli_connect_error());
}

// protects the $ _GET input from malicious injection
function GET($name=NULL, $value=false, $option="default")
{
    $option=false;
    $content=(!empty($_GET[$name]) ? trim($_GET[$name]) : 
        (!empty($value) && !is_array($value) ? trim($value) : false));
    if(is_numeric($content))
        return preg_replace("@([^0-9])@Ui", "", $content);
    else if(is_bool($content))
        return ($content?true:false);
    else if(is_float($content))
        return preg_replace("@([^0-9\,\.\+\-])@Ui", "", $content);
    else if(is_string($content))
    {
        if(filter_var ($content, FILTER_VALIDATE_URL))
            return $content;
        else if(filter_var ($content, FILTER_VALIDATE_EMAIL))
            return $content;
        else if(filter_var ($content, FILTER_VALIDATE_IP))
            return $content;
        else if(filter_var ($content, FILTER_VALIDATE_FLOAT))
            return $content;
        else
            return preg_replace(
                    "@([^a-zA-Z0-9\+\-\_\*\@\$\!\;\.\?\#\:\=\%\/\ ]+)@Ui", "",
                    $content
                    );
    }
    else false;
}

$request_method = htmlspecialchars($_SERVER["REQUEST_METHOD"]);

switch($request_method)
{
  case 'GET':
    if (GET('fetch') === 'cvData') {
        getCvData($link);
    }
    break;
  case 'POST':
    break;
  case 'PUT':
    break;
  case 'DELETE':
    break;
  default:
    // Invalid Request Method
    header("HTTP/1.0 405 Method Not Allowed");
    break;
}

?>