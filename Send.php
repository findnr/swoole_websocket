<?php
include_once('./Base.php');
class Send extends Base{
  public function one(){
    $error=['path'=>'send/error'];
    if(empty($this->data['fd'])) return $error;
    if($this->ws->isEstablished((int)$this->data['fd'])){
      $title=$this->table->get((string)$this->frame->fd);
      $time=date("Y-m-d H:i:s",time()+8*60*60);
      $data=['path'=>'msg/list','title'=>$time.' '.$title['name'].'对我说','info'=>$this->data['info']];
      $this->ws->push((int)$this->data['fd'],json_encode($data,JSON_UNESCAPED_UNICODE));
      $owntitle=$this->table->get((string)$this->data['fd']);
      return ['path'=>'msg/own','title'=>$time.' 我对'.$owntitle['name'].'说','info'=>$this->data['info']];
    }else{
      return $error;
    }
  }
}