<?php
namespace Model;

use Model\Base as Base;

class Post extends Base {
	
	private $id,$title,$text,$date,$authorid,$author;
	
	public function __construct($id,$title,$text,$date,$authorid) {
		$this->id = $id;
		$this->title = $title;
		$this->text = $text;
		$this->date = $date;
		$this->authorid = $authorid;
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
		return $this->authorid;
	}
	
	public function getAuthor() {
		return $this->author;
	}
	
	public  function setAuthor($author) {
		$this->author = $author;
	}
	
}