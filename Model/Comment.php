<?php
namespace Model;

class Comment {

	private $id,$text,$author,$date,$post,$nick;
	
	public function __construct($id,$text,$author,$date,$post,$nick) {
		$this->id = $id;
		$this->text = $text;
		$this->author = $author;
		$this->date = $date;
		$this->post = $post;
		$this->nick = $nick;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
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
	
	public function getDate() {
		return $this->date = $date;
	}
	
	public function getPostId() {
		return $this->post;
	}
	
	public function setPostId($postId) {
		$this->post = $postId;
	}
	
	public function getNick() {
		return $this->nick;
	}
	
	public function setNick($nick) {
		$this->nick = $nick;
	}
}