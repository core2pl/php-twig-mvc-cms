<?php
namespace Model;

use Model\Base as Base;

class Post extends Base {
	
	public $id,$title,$text,$date,$author_id,$author;
	
	public function __construct($id=null,$title="",$text="",$date="",$author_id=null) {
		$this->id = $id;
		$this->title = $title;
		$this->text = $text;
		$this->date = $date;
		$this->author_id = $author_id;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function getText() {
		return $this->text;
	}
	
	public function getDate() {
		return $this->date;
	}
	
	public function getAuthorId() {
		return $this->author_id;
	}
	
	public function getAuthor() {
		return $this->author;
	}
	
	public  function setAuthor($author) {
		$this->author = $author;
	}
	
}