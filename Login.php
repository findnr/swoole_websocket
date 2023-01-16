<?php
include_once('./Base.php');
class Login extends Base{
  
  public function index(){
      $path=$this->data['path'];
      $name=$this->data['name'];
      $is_user=false;
      foreach($this->table as $row){
          if($row['name'] == $name) $is_user=true; 
      }
      if($is_user){
        return ['code'=>400,'path'=>$path,'msg'=>'不能重新登录'];
      }
      $id = (string) $this->frame->fd;
      $this->table->set($id,['name'=>$name]);
      $this->cha->push(['a'=>1]);
      return ['code'=>200,'path'=>$path,'msg'=>'登录成功','name'=>$name];
  }
}