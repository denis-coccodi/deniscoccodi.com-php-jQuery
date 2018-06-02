<?php

    //ESSENTIAL FUNCTIONS
    include('../objects/timelineDate.php');

    header( 'content-type: text/html; charset=utf-8' );

    session_start();

    $link = mysqli_connect();
    $link->set_charset("utf8");

    if(mysqli_connect_errno()){    
        
		die(mysqli_connect_error());

	}

    function changeLang($lang){
        
        if($lang == 'eng'){
            
            $_SESSION['lang'] = 'eng';
            
        } else {
            
            $_SESSION['lang'] = 'ita';
            
        }
        
    }

    //CV FUNCTIONS

    function loadCurriculum(){
        
        global $link;
        
        //EDUCATION
        
        createSectionTitle("EDUCATION AND TRAINING", "ISTRUZIONE E FORMAZIONE", "#eduContainer");
        
        $query = "SELECT * FROM education WHERE addToCv=1 ORDER BY endDate DESC";

        echo '<div class="collapse show cvCollapsable" id="eduContainer">';
        
        loadSection($link, $query);
        
        echo '</div>';
        
        //WORK EXPERIENCE
        
        createSectionTitle("WORK EXPERIENCE", "ESPERIENZA LAVORATIVA", "#workContainer");
        
        $query = "SELECT * FROM workExperience WHERE addToCv=1 ORDER BY endDate DESC";

        echo '<div class="collapse show cvCollapsable" id="workContainer">';
        
        loadSection($link, $query);
        
        echo '</div>';
        
        //TABLES
        
        createSectionTitle("PERSONAL SKILLS", "COMPETENZE PERSONALI", "#cvTableContainer");
        
        echo '<div class="collapse show cvCollapsable" id="cvTableContainer">';
        
        langTable();
        digiTable();
        
        echo '</div>';
        
        //VOLUNTEERING
        
        createSectionTitle("VOLUNTEERING", "VOLONTARIATO", "#volunteering");
        
        $query = "SELECT * FROM volunteering WHERE addToCv=1 ORDER BY endDate DESC";

        echo '<div class="collapse show cvCollapsable" id="volunteering">';
        
        loadSection($link, $query);
        
        echo '</div>';
        
        //ADDITIONAL iNFORMATION
        
        createSectionTitle("HOBBIES", "HOBBIES", "#hobbies");
        
        $query = "SELECT * FROM hobbies WHERE addToCv=1 ORDER BY endDate DESC";

        echo '<div class="collapse show cvCollapsable" id="hobbies">';
        
            loadSection($link, $query);
        
        echo '</div>';
        
    }

    
    function createSectionTitle($engTitle, $titoloIta, $targetContainer) {     
        
        echo '<div href="#" class="button sectionTitleDiv d-flex flex-row justify-content-between" data-toggle="collapse" data-target="'.$targetContainer.'">

                <div class="ml-2 pb-0 my-0 d-flex"><a class="py-0 my-0 align-self-center" href="#" class="ml-3 sectionTitle">';

                    if($_SESSION['lang'] == 'eng'){

                        echo '<h4 class="py-0 my-0">'. $engTitle .'</h4>';

                    } else {

                        echo '<h4 class="py-0 my-0">'. $titoloIta .'</h4>';

                    }

                echo '</a></div>

                <div class="sectionHr mr-3"></div>

        </div>';
        
    }
    
    function extractTableName ($query){
        
        $ret = explode(" ", $query);
        
        return $ret[3]; 
        
    }

    function loadSection($link, $query){
        
        $result = mysqli_query($link, $query);
            
        if(mysqli_num_rows($result) > 0){

            while($row = mysqli_fetch_array($result)){

                generateContent($row, extractTableName($query));
                
            }

        }
        
    }

    function generateContent($row, $tableName){
        
        echo '<div class="cvRowContainer row">

            <div class="leftColumn"><span>'.

                adaptDates($row["startDate"], $row["endDate"])

            .'</span></div>
            <div class="rightColumn">

                <div class="titleContainer cvTitle"><h5>';

                if ($row['attachedFile']) {

                    echo '<a href="'.$row['attachedFile'].'" target="_blank"><span class="">'. (($_SESSION['lang'] == 'eng')?$row['title']:$row['titoloIta']).' '.$row["score"].'</span></a>';

                } else {

                    echo (($_SESSION['lang'] == 'eng')?$row['title']:$row['titoloIta']). ' ' .$row["score"];

                }

                echo '</h5></div>
                <div class="cvContent">
                    <h6 class="darkGrey">'. $row['company'] .' - '. (($_SESSION['lang'] == 'eng')?$row['location']:$row['luogo']) .'</h6>

                    <div class="mt-3"><h7>'. (($_SESSION['lang'] == 'eng')?$row['content']:$row['contenutoIta']) .'</h7></div>';

                    echo '</div>

                    <div class="mt-2">

                        <div class="d-flex flex-row">';

                        if($tableName == "education"){ //if I'm in the education table, load certificates (exam table holds only externalIDs from the education Table)

                            if(hasCertificates($row['id'])){

                                echo '<div class="certCol">';
                                    showCertificatesLink($row['id']);
                                echo '</div>';

                            }
                        }

                        $extId = tableNameToExternalId($tableName);

                        if (hasImages($row['id'], $extId)){

                            echo '<div class="imgCol">';
                                showImagesLink($row['id'], tableNameToExternalId($tableName));
                            echo '</div>';

                        }

                        echo '</div>';

                        if(hasCertificates($row['id'])){

                            listCertificates($row['id']);

                        }

                echo '</div>

            </div>

        </div>';

    }

    function tableNameToExternalId($tableName){ //returns external key name for a given table 
        
        if($tableName){
        
            return substr ($tableName, 0, 3) ."Id";
            
        } else {
            
            return false;   
            
        }
        
    }

    function tableNameFromExternalIdName($id){ //returns table name for a given external key name  
        
        if($id){
            
            if(substr ($id, 0, 3) == 'edu') {
                
                return 'education';
                
            } else if(substr ($id, 0, 3) == 'exa') {
                
               return 'exams'; 
                
            } else if(substr ($id, 0, 3) == 'hob') {
                
               return 'hobbies'; 
                
            }else if(substr ($id, 0, 3) == 'ima') {
                
               return 'images'; 
                
            }else if(substr ($id, 0, 3) == 'vol') {
                
               return 'volunteering'; 
                
            }else if(substr ($id, 0, 3) == 'wor') {
                
               return 'workExperience'; 
                
            }  
            
        } else {
            
            return false;  
        }
        
    }
        
    function hasCertificates ($itemId){
        
        global $link;
        
        $query = "SELECT * FROM exams WHERE eduId=".mysqli_real_escape_string($link, $itemId);
            
        $result = mysqli_query($link, $query);

        if(mysqli_num_rows($result) > 0){
            
            return true;
            
        } else {
            
            return false;
            
        }
       
    }

    function showCertificatesLink($itemId){
        
        echo '<button class="button silverButton" data-toggle="collapse" data-target="#listExams'.$itemId.'">

            <i class="fa fa-id-card certificateIcon" aria-hidden="true"></i>'.(($_SESSION['lang'] == 'eng')?'<small>Certificates</small>':'<small>Certificati</small>').'<i class="ml-2 fa fa-caret-down" aria-hidden="true"></i>'

        .'</button>';
        
    }

    function listCertificates($itemId){ 
    
        echo '<div class="ml-2 m-0 p-0 rounded-bottom  cvEduList collapse" id="listExams'.$itemId.'">'.

            innerListCertificates($itemId)

        .'</div>';
        
    }
        
    function innerListCertificates($itemId){
        
        global $link;
        
        $ret = "";
        
        $examsQuery = "SELECT * FROM exams WHERE eduId=".mysqli_real_escape_string($link, $itemId);
            
        $result = mysqli_query($link, $examsQuery);

        if(mysqli_num_rows($result) > 0){

            $ret = '<ul class=" listExamHeader p-0 m-0">';

            while($row = mysqli_fetch_array($result)){
                
                $ret .= '<li class="listExamItem mx-0 my-auto px-2 py-2"><p  class="m-0 my-auto p-0">';
                
                if ($row['attachedFile']) {

                $ret .= '<a href="'.$row['attachedFile'].'" target="_blank"><i class="mr-1 fa fa-fw fa-file-pdf-o certificateListIcon" aria-hidden="true"></i>'. (($_SESSION['lang'] == 'eng')?$row['enDesc']:$row['itDesc']) .(($row['score'])?" - ". $row['score']:"") .'</a>';
                    
                } else {
                    
                    $ret .= '<i class="fa fa-fw fa-file-pdf-o certificateListIcon" aria-hidden="true"></i>'. (($_SESSION['lang'] == 'eng')?$row['enDesc']:$row['itDesc']) .(($row['score'])?" - ". $row['score']:"");
                    
                }
                
                $ret .= '</p></li>';
                    
            }
                
        $ret .= '</ul>';    
            
        return $ret;    
            
        }
       
    }   
        
    function hasImages($itemId, $idType){ //I will need both the id of the sender and the type of id to look for in the images table
        
        global $link;
        
        $query = "SELECT * FROM images WHERE ". $idType ."=".mysqli_real_escape_string($link, $itemId);
            
        $result = mysqli_query($link, $query);

        if(mysqli_num_rows($result) > 0){
            
            return true;
            
        } else {
            
            return false;
            
        }
       
    }

    function showImagesLink($itemId, $idType){
        
        
        echo '<button class="button silverButton" data-toggle="modal" id="'.$idType.'_'.$itemId.'" data-caller="'.$idType.'_'.$itemId.'"  data-target="#picsModal" data-classmc="imgModLink">

            <i class="fa fa-picture-o certificateIcon" aria-hidden="true"></i>'.(($_SESSION['lang'] == 'eng')?"<small>Pictures</small>":"<small>Foto</small>")

        .'</button>';
        
    }

    function loadImages($whereClause) {
        
        global $link;
        
        $imgLinks = array();
        $imgRatios = array(); //works only to keep the aspect of the height
        $ret = array(); //array of arrays
        
        $query = "SELECT * FROM images ". $whereClause;
            
        $result = mysqli_query($link, $query);
        
        if(mysqli_num_rows($result) > 0){

            while($row = mysqli_fetch_array($result)){
                
                array_push($imgLinks, './'. $row['picUrl']);
                $imgInfo = getimagesize('../'. $row['picUrl']); 
                array_push($imgRatios, $imgInfo[1]/ $imgInfo[0]);
                
            }
            
            
            array_push($ret, $imgLinks);
            array_push($ret, $imgRatios);
            return $ret;
            
        } else {
            
            return false;
            
        }
        
    }

    function getPicTitle($table, $id) {
        
        global $link;
        
        $ret = "";
        
        $query = "SELECT * FROM ". mysqli_real_escape_string($link, $table) ." WHERE id='". mysqli_real_escape_string($link, $id)."' LIMIT 1";
            
        $result = mysqli_query($link, $query);
        
        if(mysqli_num_rows($result) > 0){
            
            $row = mysqli_fetch_array($result);
            
            return (($_SESSION['lang'] == 'eng')?$row['title']:$row['titoloIta']);
            
        }
        
    }

    function adaptDates($startDate, $endDate){
        
        $startYear = substr($startDate, 0, 4);
        $startMonth = monthFromNumber(substr($startDate, 5, 2)); 
        
        $endYear = "";
        $endMonth = "";
        $endDateRebuilt = "";
            
        if((time() - strtotime($endDate)) > 0){
            
            $endYear = substr($endDate, 0, 4);
            $endMonth = monthFromNumber(substr($endDate, 5, 2));
            $endDateRebuilt = $endMonth.". ".$endYear;
        
        } else {
            if($_SESSION['lang'] == 'eng'){
                
                    $endDateRebuilt = "Today";    
                
            } else {
                
                $endDateRebuilt = "Oggi";
            
            }
            
        }
        
        return $startMonth.". ".$startYear." - ". $endDateRebuilt;
        
    }

    function monthFromNumber($num){
        
        $months = array (1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec');
        
        $mesi = array (1=>'Gen',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mag',6=>'Giu',7=>'Lug',8=>'Ago',9=>'Set',10=>'Ott',11=>'Nov',12=>'Dic');
        
        $strMonth = "";
        
        (substr($num, 0, 1) == 0)? $num = substr($num, 1, 1) : $num = $num;
        
        if($_SESSION['lang'] == 'eng'){
            
           $strMonth = $months[$num];
            
        } else {
            
           $strMonth = $mesi[$num];
            
        }
        
        return $strMonth;
        
    }

    function langTable(){
            
            echo '<div class="row">
                <div class="leftColumn">
                    <span>'. (($_SESSION['lang'] == 'eng')?'Mother tongue':'Lingua madre') .'</span>
                </div>
                <div class="rightColumn">
                    <span class="">'.(($_SESSION['lang'] == 'eng')?'Italian':'Italiano').'</span>
                </div>
            </div>
            <div class="row cvTableRow">
                <div class="leftColumn">
                    <span class="">'.(($_SESSION['lang'] == 'eng')?'Other languages':'Altre lingue').'</span>
                </div>
                <div class="rightColumn">
                <span class="">'. (($_SESSION['lang'] == 'eng')?'English':'Inglese') .'</span>
                    <table class="table cvTable table-responsive mt-3">
                        <tr>
                            <th colspan="2" class="text-uppercase">'.(($_SESSION['lang'] == 'eng')?'understanding':'COMPRENSIONE').'</th>
                            <th colspan="2" class="text-uppercase">'.(($_SESSION['lang'] == 'eng')?'SPEAKING':'PARLATO').'</th>
                            <th class="text-uppercase">'.(($_SESSION['lang'] == 'eng')?'writing':'PRODUZIONE SCRITTA').'</th>
                        </tr>
                        <tr>
                            <th>'.(($_SESSION['lang'] == 'eng')?'Listening':'Ascolto').'</th>
                            <th>'.(($_SESSION['lang'] == 'eng')?'Reading':'Lettura').'</th>
                            <th>'.(($_SESSION['lang'] == 'eng')?'Interaction':'Interazione').'</th>
                            <th>'.(($_SESSION['lang'] == 'eng')?'Oral Production':'Produzione orale').'</th>
                            <th></th>
                        </tr>
                        <tr>
                            <td class="text-uppercase">C2</td>
                            <td class="text-uppercase">C2</td>
                            <td class="text-uppercase">C2</td>
                            <td class="text-uppercase">C2</td>
                            <td class="text-uppercase">C2</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="background-color: lightgray;">EF Standardized English Test: Advanced / Proficient (C1 / C2 Bilingual, 93/100)</td>
                        </tr>
                    </table>
                    <p style="color: #808182"><small><span class="darkGrey">'.(($_SESSION['lang'] == 'eng')?'Levels: A1/A2: Basic user - B1/B2: Independent user - C1/C2 Proficient user (Common European Framework of Reference for Languages)':'Livelli: A1 e A2: Utente base - B1 e B2: Utente autonomo - C1 e C2: Utente avanzato> (Quadro Comune Europeo di Riferimento delle Lingue)').'</span></p></small>

                </div>
            </div>';
            
    }

    function digiTable(){
            
            echo '<div class="row cvTableRow">
                <div class="leftColumn">
                    <span class="cvLeftSide">'.(($_SESSION['lang'] == 'eng')?'DIGITAL COMPETENCE':'COMPETENZA DIGITALE').'</span>
                </div>
                <div class="rightColumn">
                    <table class="table cvTable table-responsive">
                        <tr>
                            <th colspan="5">'.(($_SESSION['lang'] == 'eng')?'SELF-ASSESSMENT':'AUTOVALUTAZIONE').'</th>
                        </tr>
                        <tr>
                            <th>'.(($_SESSION['lang'] == 'eng')?'Information processing':'Elaborazione delle informazioni').'</th>
                            <th>'.(($_SESSION['lang'] == 'eng')?'Communication':'Comunicazione').'</th>
                            <th>'.(($_SESSION['lang'] == 'eng')?'Content creation':'Creazione di contenuti').'</th>
                            <th>'.(($_SESSION['lang'] == 'eng')?'Safety':'Sicurezza').'</th>
                            <th>'.(($_SESSION['lang'] == 'eng')?'Problem solving':'Risoluzione di problemi').'</th>
                        </tr>
                        <tr>
                            <td>'.(($_SESSION['lang'] == 'eng')?'Independent user':'Utente autonomo').'</td>
                            <td>'.(($_SESSION['lang'] == 'eng')?'Proficient user':'Utente avanzato').'</td>
                            <td>'.(($_SESSION['lang'] == 'eng')?'Independent user':'Utente autonomo').'</td>
                            <td>'.(($_SESSION['lang'] == 'eng')?'Independent user':'Utente autonomo').'</td>
                            <td>'.(($_SESSION['lang'] == 'eng')?'Independent user':'Utente autonomo').'</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row licenseRow">
                <div class="leftColumn">
                    <span class="cvLeftSide">'.(($_SESSION['lang'] == 'eng')?'Driving licence':'Patente di guida').'</span>
                </div>
                <div class="rightColumn">
                    <span class="cvTitle">A1, B</span>
                </div>
            </div>';
            
    }

//TIMELINE--------------------------------------------------------------

	function LoadTimeline(){
		
        echo '<div id="tlError" class="alert alert-warning"></div>
		<div id="tlPortraitWarning" class="alert alert-warning"></div>
		<h3 class="text-center pt-4 pb-1 text-uppercase">TIMELINE</h3>';
		
		echo '<div class="p-3">
			<div id="tlDiv" class="p-3">
				<div id="tlLoading" class="divLoading"><div class="divLoader"></div></div>
				<table class="table cvTable table-responsive"  id="timeline"></table>
				<div id="sliderDiv" class="p-2 border border-dark border-top-0">
					<div class="d-flex flex-column">
						<div class="d-flex justify-content-center text-center orangeRow m-0 align-items-stretch"><label id="tlValues" class=""></label></div>
						<div class="row mt-4 text-center">
							<div class="align-self-center col-md-10 py-2">
								<input id="slider" type="text" class="" value="" data-slider-tooltip="show" data-slider-enabled="false" data-slider-handle="custom" data-slider-step="1"/>
							</div>
							<div  class="align-self-center col-md-2">
								<label id="redraw" class=" silverButton">'. (($_SESSION['lang'] == 'eng')?'Redraw':'Ridisegna') .'</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>';
		
	}

	function loadTimelineArrays() {
		
		$tablesContent = array();
		
		$query = "SELECT * FROM education";
		$tablesContent['education'] = loadTimelineTable($query);
		
		$query = "SELECT * FROM workExperience";
		$tablesContent['workExperience'] = loadTimelineTable($query);
		
		$query = "SELECT * FROM volunteering";
		$tablesContent['volunteering'] = loadTimelineTable($query);
		
		$query = "SELECT * FROM hobbies";
		$tablesContent['hobbies'] = loadTimelineTable($query);
		
		$query = "SELECT * FROM nations, nationsDates WHERE nationsDates.nationId = nations.id";
		$tablesContent['nationsDates'] = loadTimelineTable($query);
		
		return $tablesContent;
		
	}

    function loadTimelineTable($query) {
        
        global $link;
		$rows = array();
		
        $result = mysqli_query($link, $query);
        
        if(mysqli_num_rows($result) > 0){

            while($row = mysqli_fetch_array($result)){
				
				$timelineRow = new timelineDate();
                $timelineRow -> setTitle((($_SESSION['lang'] == 'eng') ?  $row['title'] : $row['titoloIta']));
				$timelineRow -> setStartDate(splitDate($row['startDate']));
				$timelineRow -> setEndDate(splitDate($row['endDate']));	
				
				array_push($rows, $timelineRow);
				
            }
			
			return $rows;
            
        } else {
            
            return false;
            
        }
        
    }



    function splitDate($inputDate){
		
		$dateSplit = new myDate();
            
        if((time() - strtotime($inputDate)) < 0){
			
			$inputDate = date('Y-m-d');
			
        }
		
		$dateSplit -> setDay(substr($inputDate, 8, 2));
		$dateSplit -> setMonth(substr($inputDate, 5, 2));
		$dateSplit -> setYear(substr($inputDate, 0, 4));
        
        return $dateSplit;
        
    }

//EXERCISES FUNCTIONS----------------------------------------------

    function loadExercises(){
        
        global $link;
        
        echo '<h3 class="text-center pb-1 text-uppercase">'. (($_SESSION['lang'] == 'eng')? 'Exercises' : 'Esercizi') .'</h3>';
        
        $query = "SELECT * FROM exercises";

        echo '<div class="d-flex flex-column" id="exeContainer">';
		
        $result = mysqli_query($link, $query);
            
        if(mysqli_num_rows($result) > 0){

            while($row = mysqli_fetch_array($result)){

                exerContent($row);
                
            }

        }
        
        echo '</div>';
    }

    function exerContent($row){
        
        echo '<div class="p-2 singleExeContainer">
		
			<div class="row">

				<div class="exeTitle col-lg-4 text-uppercase p-0 my-auto"><h5 class="my-auto">'; //exercise Title

					if ($row['attachedFile']) {

						echo '<a href="'.$row['attachedFile'].'" target="_blank">'. (($_SESSION['lang'] == 'eng')?$row['title']:$row['titoloIta']) .'</a>';

					} else if(hasExeFiles($row['id'])) {

						echo '<a href="#" class="button" data-toggle="collapse" data-target="#listExeFiles_'.$row['id'].'">'. (($_SESSION['lang'] == 'eng')?$row['title']:$row['titoloIta']) .'<i class="ml-2 darkGrey fa fa-caret-down" aria-hidden="true"></i></a>';
						
					} else {

						echo (($_SESSION['lang'] == 'eng')?$row['title']:$row['titoloIta']);

					}

				echo '</h5></div>
				<div class="exeLang col-lg-8 m-0 p-0 my-auto"><h6 class="my-auto">'.
					
					$row['language'] //Computer languages used

				.'</h6></div>
			</div>';

			if(hasExeFiles($row['id'])){

				echo '<div class="ml-1">';

					listExeFiles($row['id']);

				echo '</div>';

			}
		
		echo '</div>';

    }
        
    function hasExeFiles ($itemId){
        
        global $link;
        
        $query = "SELECT * FROM exeFiles WHERE exeId=".mysqli_real_escape_string($link, $itemId);
            
        $result = mysqli_query($link, $query);

        if(mysqli_num_rows($result) > 0){
            
            return true;
            
        } else {
            
            return false;
            
        }
       
    }

    function listExeFiles($itemId){ 
    
        echo '<div class="rounded-bottom exeFilesList collapse" id="listExeFiles_'.$itemId.'">'.

            innerListExeFiles($itemId)

        .'</div>';
        
    }
        
    function innerListExeFiles($itemId){ //generate list of pdf files containing code
        
        global $link;
        
        $ret = "";
        
        $query = "SELECT * FROM exeFiles WHERE exeId=".mysqli_real_escape_string($link, $itemId);
            
        $result = mysqli_query($link, $query);

        if(mysqli_num_rows($result) > 0){

            $ret = '<ul class="m-0 p-0 pt-0">';

            while($row = mysqli_fetch_array($result)){
                
                $ret .= '<li class="ml-0 pl-1 my-0 py-2"><p  class="my-auto">';
                
                if ($row['attachedFile']) {

	                $ret .= '<a href="'.$row['attachedFile'].'" target="_blank"><i class="mr-1 fa fa-fw ' . ((returnExtension($row['attachedFile']) == 'pdf')?'fa-file-pdf-o' : 'fa-file-image-o') . ' exeFileListIcon" aria-hidden="true"></i>' . (($_SESSION['lang'] == 'eng')?$row['title']:$row['titoloIta']) . '</a>';
                    
                } else {
                    
                    $ret .= '<i class="fa fa-font-awesomemr-1 fa fa-fw exeFileListIcon" aria-hidden="true"></i>'. (($_SESSION['lang'] == 'eng')?$row['title']:$row['titoloIta']);
                    
                }
                
                $ret .= '</p></li>';
                    
            }
                
        $ret .= '</ul>';    
            
        return $ret;    
            
        }
       
    }

	function returnExtension($url) {
		
		return substr($url, -3);
		
	}

?>