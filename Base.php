<?php
class Base{
  public $ws;
  public $frame;
  public $table;
  public $data;
  public $cha;
  public function __construct($ws,$frame,$table,$data,$cha){
    $this->ws=$ws;
    $this->frame=$frame;
    $this->table=$table;
    $this->data=$data;
    $this->cha=$cha;
  }
}