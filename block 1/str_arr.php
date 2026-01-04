<?php
$strings = ['123', '134', '545', '512', '102', '222'];
$subString = "12";
$arr_A = [];
$arr_B = [];
foreach ($strings as $string) {
    if (str_contains($string, $subString)) {
        $arr_A[] = $string;
    } else {
        $arr_B[] = $string;
    }
}
print_r($arr_A);
print_r($arr_B);
