<?php
$appear='';

class lead{

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
		$appear .=
		 '<div class="container col-sm-6" id="stamp">
			<div class="contents">
				<div class="col-sm-12 bgimg">
				 <ul class="card-cont">
					<li><i class="fa fa-user fa-fw icof" aria-hidden="true"></i> '.$name.'</li>
					<li><i class="fa fa-map-marker fa-fw icof" aria-hidden="true"></i> '.$location.'</li>
					<li><i class="fa fa-tag fa-fw icof" aria-hidden="true"></i> '.$category.'</li>
				 </ul>
				</div>
			</div>
			 <div class="content col-sm-12">
				<h6>Asks</h6>
					<p>'.$query.'</p>
					<div class="hide_lead">
						<a href="#" class="btn btn-danger hidden-xs"><i class="fa fa-eye-slash icof" aria-hidden="true"> Hide</i></a>
					</div>
					<div class="unlock">
						<a href="#" class="btn btn-success"><i class="fa fa-unlock-alt icof" aria-hidden="true"> Unlock</i></a>
					</div>
			 </div>
			</div>';

		// $this->edu_shortcode($appear);	
	}

	public function edu_shortcode($appear){
		$final_rend=
		'<div class="container col-lg-12">'.$GLOBALS['appear'].'</div>';
		
		return $final_rend;
	}

}

$shrt_code=new lead('Rohit','Lucknow','CEO','Nirvana');
$scnd_lead=new lead('Mark','Mumbai','JEE','Prep for board');
$shrt_code=new lead('Rohit','Lucknow','CEO','Nirvana');
$scnd_lead=new lead('Mark','Mumbai','JEE','Prep for board');

add_shortcode('edugorilla_leads', array($shrt_code,'edu_shortcode' ));


