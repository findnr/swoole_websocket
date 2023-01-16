<?php
include_once('./Login.php');
include_once('./Send.php');
$table = new \Swoole\Table(1024);
$table->column('name', \Swoole\Table::TYPE_STRING,30);
$table->create();
$cha = new \Swoole\Coroutine\Channel(10);
//创建WebSocket Server对象，监听0.0.0.0:9502端口
$ws = new \Swoole\WebSocket\Server('0.0.0.0', 9502);
$ws->set([
    'open_http_protocol' => true, // 设置这个端口关闭HTTP协议功能
    'enable_coroutine' =>true,
]);
$ws->on('Request', function ($request, $response) {
    $response->header('Content-Type', 'text/html; charset=utf-8');
    $response->end('<h1>Hello Swoole</h1>');
});
//监听WebSocket连接打开事件
$ws->on('Open', function ($ws, $request) {
    $ws->push($request->fd,json_encode(['path'=>'wecome','msg'=>'欢迎使用本程序'],JSON_UNESCAPED_UNICODE));
});

//监听WebSocket消息事件
$ws->on('Message', function ($ws, $frame) use($table,$cha) {
    $data = json_decode($frame->data,true);
    list($controller, $action) = explode('/', trim($data['path'], '/'));
    $controller=ucfirst($controller);
    $res_data=(new $controller($ws,$frame,$table,$data,$cha))->$action();
    $ws->push($frame->fd,json_encode($res_data,JSON_UNESCAPED_UNICODE));
});

//监听WebSocket连接关闭事件
$ws->on('Close', function ($ws, $fd) use ($table){
    if($table->get((string)$fd)) $table->del((string)$fd);
    sendList($ws,$table);
    //echo "client-{$fd} is closed\n";
});
\Swoole\Timer::tick(1000,function()use($cha,$ws,$table){
  if($cha->pop(0.5)){
      sendList($ws,$table);
  }
});
\Swoole\Timer::tick(300000,function()use($cha,$ws,$table){
      sendList($ws,$table);
});
function sendList($ws,$table){
  $del=[];
  $data=[];
      foreach($table as $k=> $v){
        if($ws->isEstablished((int)$k)){
          $v['fd']=$k;
          $data['data'][]=$v;
        }else{
          array_push($del,(int)$k);
        }
      }
      foreach($del as $v){
        $table->del($v);
      }
      $data['num']=$table->count();
      $data['path']='list/user';
      foreach($table as $k=> $v){
        if($ws->isEstablished((int)$k)){
            $ws->push((int)$k,json_encode($data,JSON_UNESCAPED_UNICODE));
        }
      }
}
$ws->start();