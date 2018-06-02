<?php

// loads an array with the data of the entire cv
function getCvData($link) {
    $cvData = [];
    
    $cvData['education'] = getTableData($link, 'education');
    $cvData['workExperience'] = getTableData($link, 'workExperience');
    $cvData['volunteering'] = getTableData($link, 'volunteering');
    $cvData['hobbies'] = getTableData($link, 'hobbies');
    echo json_encode($cvData);
}

// query generator for the section tables (education, workExperience, etc...)
function getTableData($link, $tableName) {
    $query = sprintf("SELECT * FROM %s ORDER BY endDate DESC",
    mysqli_real_escape_string($link, $tableName));
    return loadSection($link, $query);
}

// loads a section of items (education, workExperience, Hobbies, etc...)
function loadSection($link, $query){
    $sectionData = [];
    $result = mysqli_query($link, $query);

    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sectionData, loadRowContent(
                    $link, $row,
                    extractTableName($query)
                ));
        }
    }
    return $sectionData;
}

// get table name from query
function extractTableName ($query){
    $ret = explode(" ", $query);

    return $ret[3]; 
}

// loads an array with the content of a single item
function loadRowContent($link, $row, $tableName){
    $rowArray = [];

    $rowArray['startDate'] = $row["startDate"];
    $rowArray['endDate'] = $row["endDate"];
    
    $row['attachedFile']? $rowArray['attachedFile'] = 
            $row['attachedFile'] : $rowArray['attachedFile'] = '';
    
    $rowArray['title'] = $row['title'];
    $rowArray['titoloIta'] = $row['titoloIta'];
    $rowArray['score'] = $row["score"];
    $rowArray['company'] = $row['company'];
    $rowArray['location'] = $row['location'];
    $rowArray['luogo'] = $row['luogo'];
    $rowArray['description'] = $row['content'];
    $rowArray['descrizione'] = $row['contenutoIta'];
    $rowArray['addToCv'] = $row['addToCv'];

    $examsQuery = createQueryString($link, 'exams', $tableName, $row['id']);
    (hasCertificates($link, $examsQuery))?
        $rowArray['certificates'] = loadCertificatesLinks($link, $examsQuery) :
        $rowArray['certificates'] = '';
    
    $imagesQuery = createQueryString($link, 'images', $tableName, $row['id']);
    (hasImages($link, $imagesQuery))?
        $rowArray['images'] = loadImagesLinks($link, $imagesQuery) :
        $rowArray['images'] = '';
    return $rowArray;
}

// creates query strings for exams and images tables
function createQueryString($link, $tableName, $extTableName, $extId) {
    $query = sprintf("SELECT * FROM %s WHERE extTableName='%s' AND extId=%s",
    mysqli_real_escape_string($link, $tableName),
    mysqli_real_escape_string($link, $extTableName),
    mysqli_real_escape_string($link, $extId));
    
    return $query;
}

// checks if current item has any certificate to it
function hasCertificates ($link, $query){
    $result = mysqli_query($link, $query);

    if(mysqli_num_rows($result) > 0){
        return true;
    } else {
        return false;
    }
}

// loads all certificates of current item
function loadCertificatesLinks($link, $query){
    $certificateList = [];
    $result = mysqli_query($link, $query);

    if(mysqli_num_rows($result) > 0){
        $certificateItem = [];
        while($row = mysqli_fetch_array($result)){
            $row['attachedFile']?
                $certificateItem['attachedFile'] = $row['attachedFile'] :
                $certificateItem['attachedFile'] = '';
            
            $certificateItem['enDesc'] = $row['enDesc'];
            $certificateItem['itDesc'] = $row['itDesc'];
            $row['score']?
                $certificateItem['score'] = $row['score'] :
                $certificateItem['score'] = '';
            array_push($certificateList, $certificateItem);
        }
    } 
    return $certificateList;
}   

// checks if the current item has pictures 
function hasImages($link, $query){
    $result = mysqli_query($link, $query);

    if(mysqli_num_rows($result) > 0){
        return true;
    } else {
        return false;
    }
}

// load links to images for the current item
function loadImagesLinks($link, $query) {
    $imgLinks = array();
    $result = mysqli_query($link, $query);

    if(mysqli_num_rows($result) > 0){
        $imgLink = [];
        while($row = mysqli_fetch_array($result)) {
            $imgLink['picUrl'] = './'. $row['picUrl'];
            array_push($imgLinks, $imgLink);
        }
        return $imgLinks;
    } else {
        return false;
    }
}
?>