<?php
$login =$_GET['login'];
$pass =$_GET['pass'];
$email =$_GET['email'];
$func =$_GET['func'];
$room_id =$_GET['room_id'];
$vers_data =$_GET['data'];

function riKal($dok, $sher, $mafia, $mir, $terrorist, $shpion, $bar, $jurnalist, $lubovnica){
$f =0;
while ($f ==0) {
    $rands =rand(1, 9);
    if ($rands ==1) {
        if ($dok !=1) {
            $s =1;
            return $s;
        }
    }elseif ($rands ==2) {
        if ($sher !=1) {
            $s =2;
            return $s;
        }
    }elseif ($rands ==3) {
        if ($mafia !=5) {
            $s =3;
            return $s;
        }
    }elseif ($rands ==4) {
        if ($mir !=9) {
            $s =4;
            return $s;
        }
    }elseif ($rands ==5) {
        if ($terrorist !=1) {
            $s =5;
            return $s;
        }
    }elseif ($rands ==6) {
        if ($shpion !=1) {
            $s =6;
            return $s;
        }
    }elseif ($rands ==7) {
        if ($bar !=1) {
            $s =7;
            return $s;
        }
    }elseif ($rands ==8) {
        if ($jurnalist !=1) {
            $s =8;
            return $s;
        }
    }elseif ($rands ==9) {
        if ($lubovnica !=1) {
            $s =9;
            return $s;
        }
    }
}
}



if ($func =='room_start' && file_get_contents('room/'.$room_id.'/status.txt') !='1') {
    file_put_contents('room/'.$room_id.'/time.txt', '40');
    file_put_contents('room/'.$room_id.'/status.txt', '1');
    $players =file_get_contents('room/'.$room_id.'/players.txt');
    while (strpos($players, ' ') ===true) {
    $players =str_replace(' ', '', $players);
}

$players =explode('[{^%$*&@^}]', $players);
//$dok =1;//-------------// =>1
//$sher =1;//-----------// =>2
//$mafia =5;//---------// =>3
//$mir =9;//----------// =>4
//$terrorist =1;//---// =>5
//$shpion =1;//-----// =>6
//$bar =1;//-------// =>7
//$jurnalist =1;  // =>8
//$lubovnica =1; // =>9

$dok =0;//-------------// =>1
$sher =0;//-----------// =>2
$mafia =0;//---------// =>3
$mir =0;//----------// =>4
$terrorist =0;//---// =>5
$shpion =0;//-----// =>6
$bar =0;//-------// =>7
$jurnalist =0;  // =>8
$lubovnica =0; // =>9

$pos =0;
$posMax =20;

while ($pos < $posMax) {
    $player = $players[$pos];
    $ranggg =riKal($dok, $sher, $mafia, $mir, $terrorist, $shpion, $bar, $jurnalist, $lubovnica);
    $players_generate[] =array($player, $ranggg);
    if ($ranggg ==1) {
        $dok +=1;
    } elseif ($ranggg ==2) {
        $sher +=1;
    } elseif ($ranggg ==3) {
        $mafia +=1;
    } elseif ($ranggg ==4) {
        $mir +=1;
    } elseif ($ranggg ==5) {
        $terrorist +=1;
    } elseif ($ranggg ==6) {
        $shpion +=1;
    } elseif ($ranggg ==7) {
        $bar +=1;
    } elseif ($ranggg ==8) {
        $jurnalist +=1;
    } elseif ($ranggg ==9) {
        $lubovnica +=1;
    }

    $pos +=1;

}
print_r(json_encode($players_generate));
file_put_contents('room/'.$room_id.'/play_gen.txt', json_encode($players_generate));



    $r =0;
    $r1 =1;
    $date =2;
    $krug =1;
$time =40;
    while ($r != $r1) {
if ($krug ==1 && $date ==2) {
    sleep(1);
    $time -=1;
    if ($time ==0) {
        sleep(1);
        $time =20;
        $krug =2;
    }
} elseif ($krug ==2 && $date ==2) {
    file_put_contents('room/'.$room_id.'/mafia_glosovanie.txt', '1');
    file_put_contents('room/'.$room_id.'/mafia_glosovanie_player.txt', file_get_contents('room/'.$room_id.'/mafia_glosovanie_player.txt'));
    sleep(1);
    $time -=1;
    if ($time ==0) {
        sleep(1);
        $time =60;
        $krug =3;
        $date =1;
    }
} elseif ($krug ==3 && $date ==1) {
    sleep(1);
    $time -=1;
    if ($time ==0) {
        sleep(1);
        $time =30;
        $krug =4;
    }
} elseif ($krug ==4 && $date ==1) {
    file_put_contents('room/'.$room_id.'/glosovanie.txt', '1');
    file_put_contents('room/'.$room_id.'/glosovanie_player.txt', file_get_contents('room/'.$room_id.'/glosovanie_player.txt'));
    sleep(1);
    $time -=1;
    if ($time ==0) {
        sleep(1);
        $time =40;
        $krug =1;
        $date =2;
    }
}
if ($date ==1) {
    $date2 ='День';
} else {
    $date2 ='Ночь';
}
file_put_contents('room/'.$room_id.'/time.txt', $time);
file_put_contents('room/'.$room_id.'/time2.txt', $date2);

    }
}
?>
