<?php
$appear = '';

class Lead_Card implements JsonSerializable
{

	private $name, $location, $category, $query;

	function __construct($name, $location, $category, $query)
	{
		$this->create_card($name, $location, $category, $query);
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($x)
	{
		$this->name = $x;
	}

	public function getLocation()
	{
		return $this->location;
	}

	public function setLocation($x)
	{
		$this->location = $x;
	}

	public function getCategory()
	{
		return $this->category;
	}

	public function setCategory($x)
	{
		$this->category = $x;
	}

	public function getQuery()
	{
		return $this->query;
	}

	public function setQuery($x)
	{
		$this->query = $x;
	}

	private function create_card($_name, $_location, $_category, $_query)
	{
		$this->setName($_name);
		$this->setLocation($_location);
		$this->setCategory($_category);
		$this->setQuery($_query);
	}

	public function edu_shortcode($appear)
	{
		include 'html/lead-portal.html';
		return null;
	}

	public function jsonSerialize()
	{
		return [
			'lead_card' => [
				'name' => $this->getName(),
				'location' => $this->getLocation(),
				'category' => $this->getCategory(),
				'query' => $this->getQuery()
			]
		];
	}

}

$shrt_code1 = new Lead_Card('Rohit', 'Lucknow', 'CEO', 'Nirvana');
$shrt_code2 = new Lead_Card('Anantharam', 'Chennai', 'CTO', 'Life');

add_shortcode('edugorilla_leads', array($shrt_code1, 'edu_shortcode'));


