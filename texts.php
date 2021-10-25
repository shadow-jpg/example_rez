<?php

include '../acces.php';

$link = new mysqli($db_adress,$db_login ,$db_pass ,$db_name);
//проверка работы
if ($link == false){
    echo("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
}
else{ 
        $char=mysqli_set_charset($link,'utf8mb4'); //установит utf-8 соедениение к бд
        if($char){  
            $sql="SELECT d.d_text
            FROM GOODS_DESCR g 
            LEFT JOIN DESCR d
            ON g.g_id_text=d.d_id
            WHERE g.g_inv_num=$inv and g.g_id_text=d.d_id
            LIMIT 1";
            $result = mysqli_query($link, $sql);
            $textA=mysqli_fetch_array($result, MYSQLI_NUM);
            $texts=$textA[0];
        }
}
?>