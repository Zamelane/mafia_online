<?php
ini_set('display_errors','Off');
$login =$_GET['login'];
$pass =$_GET['pass'];
$email =$_GET['email'];
$func =$_GET['func'];
$room_id =$_GET['room_id'];
$vers_data =$_GET['data'];

if ($_GET['max_players'] ==null) {
$max_players ='24';
} else {
$max_players =$_GET['max_players'];
}
define('ENCRYPTION_KEY', 'tyl6lqv6y452zf9ah9yqx27bvpo042jx3e9w05h457167pj9y5a8331mnz76y71f'); //Ключь шифрования
 
// Пример******
//$encrypted = mc_encrypt($txt, ENCRYPTION_KEY);
//$decrypted = mc_decrypt($encrypted, ENCRYPTION_KEY);

// Шифрование**
function mc_encrypt($encrypt, $key) {
  $encrypt = serialize($encrypt);
  $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
  $key = pack('H*', $key);
  $mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
  $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
  $encoded = base64_encode($passcrypt).'|'.base64_encode($iv);
  return $encoded;
}
 
// Расшифровка
function mc_decrypt($decrypt, $key) {
  $decrypt = explode('|', $decrypt.'|');
  $decoded = base64_decode($decrypt[0]);
  $iv = base64_decode($decrypt[1]);
  if(strlen($iv)!==mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)){ return false; }
  $key = pack('H*', $key);
  $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
  $mac = substr($decrypted, -64);
  $decrypted = substr($decrypted, 0, -64);
  $calcmac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
  if($calcmac!==$mac){ return false; }
  $decrypted = unserialize($decrypted);
  return $decrypted;
}







function players($str){
$str =str_replace('[{^%$*&@^}]', '', $str);
$str = explode(" ", $str);
return $str;
}
function myscandir($dir, $sort=0){
    $list = scandir($dir, $sort);
    
    // если директории не существует
    if (!$list) return false;
    
    // удаляем . и .. (я думаю редко кто использует)
    if ($sort == 0) unset($list[0],$list[1]);
    else unset($list[count($list)-1], $list[count($list)-1]);
    return $list;
}

if ($func =='reg') {
if (file_exists('users/'.$email.'.txt')) {
    print_r("email");
} else {
    if (file_exists('users/'.$login.'.txt')) {
print_r("login");
    } else{
        $apiCode =rand(1, 999999);
        $apiCode =$apiCode . rand(1, 999999);
        file_put_contents('users/'.$email.'.txt', $login);
        file_put_contents('users/'.$login.'.txt', $apiCode);
        mkdir('users/'.$apiCode);
        file_put_contents('users/'.$apiCode.'/apiCode.txt', $apiCode);
        file_put_contents('users/'.$apiCode.'/login.txt', $login);
        file_put_contents('users/'.$apiCode.'/pass.txt', $pass);
        file_put_contents('users/'.$apiCode.'/email.txt', $email);
        file_put_contents('users/'.$apiCode.'/bayte.txt', '0');
        print_r('1');
    }
   
}
} elseif ($func =='auth') {
    if (file_exists('users/'.$login.'.txt')) {
 $apiCode =file_get_contents('users/'.$login.'.txt');
 $pass2 =file_get_contents('users/'.$apiCode.'/pass.txt');
 if ($pass ==$pass2) {
    $base =file_get_contents('users/'.$apiCode.'/bayte.txt');
    print_r('5');
 } else {
    print_r('pass');
 }
    } else{
        print_r('login');
    }
} elseif ($func =='create_room') {
    if ($max ==null) {
        $max =$max_players;
    }
    $room_id =rand(100000, 9999999);
    mkdir('room/'.$room_id);
    file_put_contents('room/'.$room_id.'/name.txt', 'Henry Hill');
    file_put_contents('room/'.$room_id.'/id.txt', $room_id);
    file_put_contents('room/'.$room_id.'/players.txt', $login.'[{^%$*&@^}]');
    file_put_contents('room/'.$room_id.'/max_players.txt', $max);
    file_put_contents('room/'.$room_id.'/players_colvo.txt', '1');
    file_put_contents('room/'.$room_id.'/chats.txt', null);
    file_put_contents('room/'.$room_id.'/chats_vers.txt', 0);
    print_r($room_id);
} elseif ($func =='room_name') {
    $result =file_get_contents('room/'.$room_id.'/name.txt');
    print_r($result);

} elseif ($func =='check' && file_get_contents('room/'.$room_id.'/status.txt') !='1' || file_get_contents('room/'.$room_id.'/status.txt') !='2') {
    $S1 =file_get_contents('room/'.$room_id.'max_players.txt');
    $S2 =file_get_contents('room/'.$room_id.'players_colvo.txt');
    if ($S1 < $S2 || $S1 ==$S2) {
        file_put_contents('room/'.$room_id.'/status.txt', '2');
        $text = file_get_contents('room/'.$room_id.'players.txt');
        $r1 =100;
        $r =1;
        while ($r < $r1) {
            $text = str_replace("  ", " ", $text);
            $r +=1;
        }

        
    }
}  elseif ($func =='rooms_load') {
$dir = 'room/';
$files2 = myscandir($dir, 1);
$count =count($files2);
$pos =1;
while ($pos < $count) {
    $dir =$files2[$pos];
    $directory ='room/'.$dir.'/';
    $nameRoom =file_get_contents($directory.'name.txt');
    $id =file_get_contents($directory.'id.txt');
    $ar[] =array('name' =>$nameRoom, 'id' =>$id);
    $pos +=1;
}
$ar =json_encode($ar);
print_r($ar);
} elseif ($func =='room_auth') {
$fs =file_get_contents('room/'.$room_id.'/players.txt');
if(strpos($fs, $login.'[{^%$*&@^}]') !== false){
   print_r('1');
   $str = $fs;
$arr = array_unique(explode("\n", $str));
$arrLength = count($arr);
for($i = 0; $i < $arrLength; ++$i){
    $arr[$i] = implode(" ", array_unique(explode(" ", $arr[$i])));
}
$str = implode("\n", $arr);
file_put_contents('room/'.$room_id.'/players.txt', $str);
} elseif (file_get_contents('room/'.$room_id.'/max_players.txt') !==file_get_contents('room/'.$room_id.'/players_colvo.txt')) {

file_put_contents('room/'.$room_id.'/players_colvo.txt', file_get_contents('room/'.$room_id.'/players_colvo.txt') + 1);

file_put_contents('room/'.$room_id.'/players.txt', file_get_contents('room/'.$room_id.'/players.txt').$login.'[{^%$*&@^}]');
    print_r('8');
} else {
    print_r('0');
}
} elseif ($func =='room_players') {
    $res =file_get_contents('room/'.$room_id.'/players.txt');
    $res =players($res);
    $res =json_encode($res);
    print_r($res);
} elseif ($func =='check_chat') {
    if ($_GET['vers'] != file_get_contents('room/'.$room_id.'/chats_vers.txt')) {
    $res =array('chat' => file_get_contents('room/'.$room_id.'/chats.txt'), 'vers' => file_get_contents('room/'.$room_id.'/chats_vers.txt'));
    $res =json_encode($res);
    print_r($res);
} else {
    $rr =0;
    $rrr =1;
    $v2 =10;
    while ($rr != $rrr) {
sleep(2);
        if ($_GET['vers'] != file_get_contents('room/'.$room_id.'/chats_vers.txt')) {
        $res =array('chat' => file_get_contents('room/'.$room_id.'/chats.txt'), 'vers' => file_get_contents('room/'.$room_id.'/chats_vers.txt'));
        $res =json_encode($res);
        print_r($res);
        $rr =1;
    } elseif ($v == $v2) {
        $res =array('chat' => file_get_contents('room/'.$room_id.'/chats.txt'), 'vers' => file_get_contents('room/'.$room_id.'/chats_vers.txt'));
        $res =json_encode($res);
        print_r($res);
        $rr =1;
    }
$v +=1;
}
}
    
} elseif ($func =='messages_room') {
    $messages1 =$_GET['messages'];
    $messages1 =str_replace('{sec_probel}', ' ', $messages1);
    file_put_contents('room/'.$room_id.'/chats.txt', file_get_contents('room/'.$room_id.'/chats.txt').$login.'{[{typ}]}user{[{typ}]}'.$messages1.'{s{e{c{r}e}t}!}');
    file_put_contents('room/'.$room_id.'/chats_vers.txt', file_get_contents('room/'.$room_id.'/chats_vers.txt') + 1);
    print_r('OK');
} elseif ($func =='add_messages') {
    
} elseif ($func =='dates') {
    $s =file_get_contents('room/'.$room_id.'time2.txt') . ':' . file_get_contents('room/'.$room_id.'time.txt');
    print_r($s);
} elseif ($func =='load_player_role') {
    $type =$_GET['type'];
    if ($type == 1) {
    $login = $_GET['login'];
    $pass = $_GET['pass'];
    $room_id =$_GET['room_id'];
    if (file_exists('users/'.$login.'.txt')) {
 $apiCode =file_get_contents('users/'.$login.'.txt');
 $pass2 =file_get_contents('users/'.$apiCode.'/pass.txt');
 if ($pass ==$pass2) {
    $array =file_get_contents('room/'.$room_id.'/play_gen.txt');
    $role =json_decode($array);
    foreach ($role as $array) {
        if ($array[0] ==$login) {
            print_r($array[1]);
        }
    }
    //print_r($role);
 } else {
    print_r('error_login_acc');
 }
    } else {

    }
}
}


?>
