<?php
function addq($texts,$years,$INN,$phone,$taxes,$salary,$named,$email,$spam){
    include '../acces.php';
    if(strlen($texts)>0){
        $text=mb_substr ($texts,0,strlen($texts),'utf8');
    }
    //$texts  = nl2br($texts);
    $texts = htmlspecialchars($texts);
    $texts = urldecode($texts);
    $texts = trim($texts);
    
    $spam = htmlspecialchars($spam);
    $spam = urldecode($spam);
    $spam = trim($spam);
    
    $years = htmlspecialchars($years);
    $INN = htmlspecialchars($INN);
    $taxes = htmlspecialchars($taxes);
    $salary = htmlspecialchars($salary);
    $phone = htmlspecialchars($phone);
    $named = htmlspecialchars($named);
    $email = htmlspecialchars($email);
    
    $INN = urldecode($INN);
    $taxes = urldecode($taxes);
    $years = urldecode($years);
    $salary = urldecode($salary);
    $phone = urldecode($phone);
    $named = urldecode($named);
    $email = urldecode($email);
    
    $phone = trim($phone);
    $email = trim($email);
    $INN = trim($INN);
    $taxes = trim($taxes);
    $salary = trim($salary);
    $years = trim($years);
    $named = trim($named);
    
    $salary=$salary." rubles";

    mail($mail,"Заявка с сайта", "\nФИО:".$named.".\nE-mail: ".$email."\nphone number: ".$phone."\ntaxes: ".$taxes."\nИНН: ".$INN."\nsalary: ".$salary."\nГоды создания: ".$years."\nкоментарий: ".$texts."\n рассылка: ".$spam,"From: guest@gmail.ru\r\n");
    //подключение
    $link = new mysqli($db_adress,$db_login ,$db_pass ,$db_name);
    //проверка работы
    if ($link == false){
        $s1="Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error();
    } 
    else{
        mysqli_set_charset($link, 'utf8');
    //проверка на существование двух e-mail
        $sql=mysqli_prepare($link, "SELECT email,count(email) AS score FROM questioning WHERE email=?");
        mysqli_stmt_bind_param($sql, "s", $email);
         mysqli_stmt_execute($sql);
        $bundle= mysqli_stmt_get_result($sql);
        $row = mysqli_fetch_assoc($bundle);
        if($row['score']<2){
        //задаем команду добавления, подтверждаем транзакцию и закрываем бд
            $sql1=mysqli_prepare($link, "INSERT INTO questioning (named,phone,email,salary,INN,taxes,years,texts,spam) values(?,?,?,?,?,?,?,?,?)");
            mysqli_stmt_bind_param($sql1, "sssssssss", $named,$phone,$email,$salary,$INN,$taxes,$years,$texts,$spam);
            $result=mysqli_stmt_execute($sql1);
            mysqli_commit($link);
            mysqli_close($link);
    //ну дефолтная проверка
            if (!$result) {
                $s1='failed result of adding' ;
            }
            else{
                $s1='succes';
            }
    
        }
        else{
            $s1='too much emails';
        }
    
        }
    return $s1;
}




function adds($phone,$named,$email){
    include '../acces.php';
    $phone = htmlspecialchars($phone);
    $named = htmlspecialchars($named);
    $email = htmlspecialchars($email);
    $phone = urldecode($phone);
    $named = urldecode($named);
    $email = urldecode($email);
    $phone = trim($phone);
    $named = trim($named);
    $email = trim($email);




    mail($mail,"Заявка с сайта", "\nФИО:".$named.".\n E-mail: ".$email."\n phone number: ".$phone,"From: guest@gmail.ru\r\n");







//подключение
    $link = new mysqli($db_adress,$db_login ,$db_pass ,$db_name);
    if ($link == false){
        $s1="Ошибка: Невозможно подключиться к MySQL ";
    }
    else{
        mysqli_set_charset($link, 'utf8');
        //задаем команду добавления, подтверждаем транзакцию и закрываем бд через предподготовленный
        $sql=mysqli_prepare($link, "INSERT INTO recall (named,phone) values(?,?)");
        mysqli_stmt_bind_param($sql, "ss", $named,$phone);
        $result=mysqli_stmt_execute($sql);
        mysqli_commit($link);
        mysqli_close($link);
        if (!$result) {
            $s1='failed result of adding';
        }
        $s1='succes';
   
    }
    return $s1;
}
?>