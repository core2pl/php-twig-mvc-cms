<?php
namespace Model;

use Model\Base as Base;

class Post extends Base {
	
	private $title,$text,$date,$author_id,$author;
	
	public function __construct($title="",$text="",$date="",$author_id=null) {
		$this->title = $title;
		$this->text = $text;
		$this->date = $date;
		$this->author_id = $author_id;
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