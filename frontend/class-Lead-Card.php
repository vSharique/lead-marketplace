<?php
$appear='';

class Lead_Card{

	private $name,$location,$category,$query;

	function __construct($name, $location, $category, $query)
	{
		$this->create_card($name, $location, $category, $query);
	}

	public function print_lead(){
		echo $this->name.' '.$this->location;
	}

	private function create_card($name, $location, $category, $query)
	{
		global $appear;
		$appear .= '';
	}

	public function edu_shortcode($appear){
		$final_rend= ''.$GLOBALS['appear'].'';
        include 'html/lead-portal.html';
		return $final_rend;
	}

}

$shrt_code1=new Lead_Card('Rohit','Lucknow','CEO','Nirvana');
$shrt_code2=new Lead_Card('Anantharam','Chennai','CTO','Life');

add_shortcode('edugorilla_leads', array($shrt_code1,'edu_shortcode' ));


