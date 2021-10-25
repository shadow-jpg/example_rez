<?php
//вывод косяков
declare(strict_types=1);
ini_set('error_reporting',(string)E_ALL);
ini_set('display_errors','1');
ini_set('display_startup_errors','1');
//

include '../acces.php';


function analize(&$d,&$texts,&$resScore){
    $j0= 0;
    $i= 0;
    $d=array();
    while($i < strlen($texts)){
        $str='';
        if($texts[$i]=='$'){
            $i++;
            while(($texts[$i]!=' ')&&($texts[$i]!='$')){
                $str=$str.$texts[$i];
                $i++;
            }
            $d[$j0]=$str;
            $j0++;
        }
        $i++;
    }   
    $resScore=$j0;
}


function goods($inv,$link,$assoc,$words,&$texts){
    //берем набор фраз
    $sql = "SELECT goods.g_variable, goods.g_text
            FROM GOODS_DESCR goods
                join ( SELECT b1.g_text, b1.g_variable, b1.g_id FROM GOODS_DESCR b1 WHERE b1.g_type=2 and b1.g_inv_num=$inv ) inventory 
                ON goods.g_inv_num = inventory.g_id and goods.g_type=1	
                    UNION ALL ( SELECT g_variable, g_text FROM GOODS_DESCR b2 WHERE b2.g_type=2 and b2.g_inv_num=$inv ) ";
    /*Вывод, выгруженных данных в специальном обьекте*/
    $result = mysqli_query($link, $sql);
    if($result){
        /*Формирования визуального отображения данных*/
        $test = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $array1=array();
        $array2=array();
        for ($i = 0; $i < $words; $i++) {
            $key = array_search($assoc[$i], array_column($test, 'g_variable'));
            if($test[$key]["g_text"]){
                $array1[$i]=$test[$key]["g_text"];
                $array2[$i]="$$assoc[$i]";
            }
        }
        update_text($texts,$array1,$array2,$words);
        mysqli_close($link);
    }
}

function update_text(&$texts,&$assoc1,&$assoc2,$words){    
    for ($i = 0; $i < $words; $i++) {    
        $texts=str_replace($assoc2[$i],$assoc1[$i],$texts);
        }   
}



echo "<html lang='ru'>";
echo"<head>";
    echo"<meta charset='utf-8'>";
    echo"<meta http-equiv='X-UA-Compatible' content='IE=edge'>";
    echo"<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo"<link rel='stylesheet' href='1.css'>";
    echo"<title>Document</title>";
echo"</head>";



include 'texts.php';
$link = new mysqli($db_adress,$db_login ,$db_pass ,$db_name);
if ($link == false){
    echo("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
}
else{   
        $char=mysqli_set_charset($link,'utf8mb4'); 
        if($char){
        $get_num=0;
        $get_assoc=array();
        analize($get_assoc,$texts,$get_num);
        $get_goods=goods($inv,$link,$get_assoc,$get_num,$texts);
        echo($texts);
        }
}
?>
