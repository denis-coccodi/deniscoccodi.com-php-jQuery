<?php 	

class timelineDate {
			
	public $title = '';
	public $startDate;
	public $endDate;	
    
	function __construct() {
		
        $this -> title = '';
        $this -> startDate = null;
        $this -> endDate = null;
		
	}
	
    public function getTitle() {
        return $this -> title;
    }
	
    public function getStartDate() {
        return $this -> startDate;
    }	
    
    public function getEndDate() {
        return $this -> endDate;
    }
    
    public function setTitle($title) {
        $this -> title = $title;
        return $this;
    }
    
    public function setStartDate($startDate) {
        $this -> startDate = $startDate;
        return $this;
    }
    
    public function setEndDate($endDate) {
        $this -> endDate = $endDate;
        return $this;
    }

}

?>