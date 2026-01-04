<?php
$lenght = rand(10, 100);
$username = "";
for ($i = 0; $i < $lenght; $i++)
    $username .= chr(rand(97, 122));
echo "Имя пользователя: $username" . "<br>";
$arr_username = str_split($username);
$arr_unique = array_unique($arr_username);
$unique_char_count = count($arr_unique);
if ($unique_char_count % 2 == 0)
    echo "Girl!";
else
    echo "Boy!";
