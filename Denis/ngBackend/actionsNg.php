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
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

}
    include('functions.php');
    include('../objects/myDate.php');
    //if($_GET['action'] == 'loadImgLinks'){
        
        $whereClause = "WHERE ".mysqli_real_escape_string($link,"eduId").'="'.mysqli_real_escape_string($link, 1).'"';
        
        $picTitle = getPicTitle(tableNameFromExternalIdName("eduId"), 1);
        
        echo json_encode(array('imgLinksArray' => loadImages($whereClause), 'imgTitle' => $picTitle));
        
    //}
    
?>