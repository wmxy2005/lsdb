<?php
include 'websocket.class.php';
 
$config=array(
  'address'=>'127.0.0.1',
  'port'=>'8000',
  'event'=>'WSevent',//回调函数的函数名
  'log'=>true,
);
$websocket = new websocket($config);
$websocket->run();
function WSevent($type,$event){
  global $websocket;
    if('in'==$type){
      $websocket->log('Client connect id:'.$event['k']);
    }elseif('out'==$type){
      $websocket->log('lient disconnect id:'.$event['k']);
    }elseif('msg'==$type){
      $websocket->log($event['k'].'.msg:'.$event['msg']);
      roboot($event['sign'],$event['msg']);
    }
}
 
function roboot($sign,$t){
  global $websocket;
  switch ($t)
  {
  case 'hello':
    $show='hello,GIt @ OSC';
    break;  
  case 'name':
    $show='Robot';
    break;
  case 'time':
    $show='当前时间:'.date('Y-m-d H:i:s');
    break;
  case '再见':
    $show='( ^_^ )/~~拜拜';
    $websocket->write($sign,'Robot:'.$show);
    $websocket->close($sign);
    return;
    break;
  case '天王盖地虎':
    $array = array('小鸡炖蘑菇','宝塔震河妖','粒粒皆辛苦');
    $show = $array[rand(0,2)];
    break;
  default:
    $show='( ⊙o⊙?)不懂,你可以尝试说:hello,name,time,再见,天王盖地虎.';
  }
  $websocket->write($sign,'Robot:'.$show);
}
?>