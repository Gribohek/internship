<?php

$name = trim($_POST['username'] ?? '');
$age = (int)($_POST['age'] ?? 0);
$gender = $_POST['gender'] ?? '';
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($name === '') {
    echo "Вы не ввели имя";
} elseif (strlen($name) <= 1) {
    echo "Такого имени не существует";
} elseif ($email === '') {
    echo "Вы не ввели email";
} elseif ($password === '') {
    echo "Вы не ввели пароль";
} elseif (strlen($password) < 4) {
    echo "Длина пароля должна быть не меньше 4 символов";
} else {
    $safeAge = htmlspecialchars((string)$age, ENT_QUOTES, 'UTF-8');

    if ($gender === "male") {
        if ($age < 18)
            echo "Вы юноша $safeAge лет";
        elseif ($age < 60)
            echo "Вы мужчина $safeAge лет";
        else
            echo "Вы пенсионер $safeAge лет";
    } else {
        if ($age < 18)
            echo "Вы девочка $safeAge лет";
        elseif ($age < 60)
            echo "Вы девушка $safeAge лет";
        else
            echo "Вы пенсионерка $safeAge лет";
    }
}
$passwordHash = password_hash($password, PASSWORD_DEFAULT);
