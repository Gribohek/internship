<?php
$name = $_POST['username'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$email = $_POST['email'];
$password = $_POST['password'];

if (trim($name) == "")
    echo "Вы не ввели имя";
else if (strlen($name) <= 1)
    echo "Такого имени не существует";
if (trim($email) == "")
    echo "Вы не ввели email";
if (trim($password) == "")
    echo "Вы не ввели пароль";
else if (strlen($password) < 4)
    echo "Длина пароля должна быть не меншье 4 символов";
else {
    if ($gender == "male") {
        if ($age < 18)
            echo "Вы юноша $age лет";
        else if ($age < 60)
            echo "Вы мужчина $age лет";
        else
            echo "Вы пенсионер $age лет";
    } else {
        if ($age < 18)
            echo "Вы девочка $age лет";
        else if ($age < 60)
            echo "Вы девушка $age лет";
        else
            echo "Вы пенсионерка $age лет";
    }
}
$password = md5($password);
