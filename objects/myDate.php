<?php 	
class myDate {
			
	public $day = '';
	public $month = '';
	public $year = '';	
    
	function __construct() {
		
        $this -> day = '';
        $this -> month = '';
        $this -> year = '';
		
		return $this;
	}
	
    public function getDay() {
		
        return $this -> day;
		
    }
	
    public function getMonth() {
		
        return $this -> month;
		
    }	
    
    public function getYear() {
		
        return $this -> year;
		
    }
    
    public function setDay($day) {
		
        $this -> day = $day;
        return $this;
		
    }
    
    public function setMonth($month) {
		
        $this -> month = $month;
        return $this;
		
    }
    
    public function setYear($year) {
		
        $this -> year = $year;
        return $this;
		
    }

}

?>