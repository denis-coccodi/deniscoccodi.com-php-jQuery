<?php
    include('functions.php');
    include('../objects/myDate.php');

    if($_GET['action'] == 'changeLang'){
        
        changeLang($_POST['langRequired']);

    }
    
    if($_GET['action'] == 'loadImgLinks'){
        
        $idArray = explode("_", $_POST['caller']); //will contain idType in the first position, while the id Number in the second
        
        $whereClause = "WHERE ".mysqli_real_escape_string($link,$idArray[0]).'="'.mysqli_real_escape_string($link, $idArray[1]).'"';
        
        $picTitle = getPicTitle(tableNameFromExternalIdName($idArray[0]), $idArray[1]);
        
        echo json_encode(array('imgLinksArray' => loadImages($whereClause), 'imgTitle' => $picTitle));
        
    }
    
    if($_GET['action'] == 'loadTimeline'){
	
        echo json_encode(loadTimelineArrays());
		
	}
    
?>