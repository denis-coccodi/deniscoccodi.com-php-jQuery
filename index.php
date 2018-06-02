<?php

include("./php/functions.php");

include("views/header.php");

if($_GET['page'] == 'curriculum'){
   
    ?><style><?;
        include("./css/jumbo.css");
        include("./css/cvStyles.css");
    ?></style><?;
    
	include("views/jumbotron.php");
    include("views/curriculum.php");
	include("views/footer.php");
	include("views/scriptLibsAndModal.php");
    
	?> <script src="./scripts/jumbo.js" type="text/javascript"></script>
	<script type="text/javascript" src="./scripts/cvScript.js"></script> <?;
    
} else if($_GET['page'] == 'timeline') {
   
    ?><style><?;
        include("./css/jumbo.css");
        include("./css/timelineStyles.css");
    ?></style><?;
	
	include("views/jumbotron.php");
    include("views/timeline.php");
	include("views/footer.php");
	include("views/scriptLibsAndModal.php");

	?> <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.9.0/bootstrap-slider.js"></script>
	<script src="./scripts/jumbo.js" type="text/javascript"></script>
	<script type="text/javascript" src="./scripts/timelineScript.js"></script> <?;
    
} else if($_GET['page'] == 'exercises') {
   
    ?><style><?;
        include("./css/jumbo.css");
        include("./css/exercisesStyles.css");
    ?></style><?;
	
	include("views/jumbotron.php");
    include("views/exercises.php");
	include("views/footer.php");
	include("views/scriptLibsAndModal.php");
    
	?> <script src="./scripts/jumbo.js" type="text/javascript"></script> <?;
    
} else {
   
    ?><style><?;
        include("./css/home.css");
    ?></style><?;
	
    include("views/home.php");
	include("views/scriptLibsAndModal.php");
    
	?> <script src="./scripts/home.js" type="text/javascript"></script> <?;
    
}

if($_GET['page'] == 'curriculum'){
    
} else if($_GET['page'] == 'timeline'){

}

?>