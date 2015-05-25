<?php
namespace Model;

use Model\Base as Base;

class Comment extends Base {

	private $title,$text,$author,$post;
	
	public function __construct($title,$text,$author,$post) {
		$this->title = $title;
		$this->text = $text;
		$this->author = $author;
		$this->post = $post;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function getText() {
		return $this->text;
	}
	
	public function setText($text) {
		$this->text = $text;
	}
	
	public function getAuthor() {
		return $this->author;
	}
	
	public function setAuthor($author) {
		$this->author =$author;
	}
	
	public function getPostId() {
		return $this->post;
	}
	
	public function setPostId($postId) {
		$this->post = $postId;
	}
}