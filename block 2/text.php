<?php
class Text
{
    public $text;
    public function __construct($text)
    {
        $this->text = $text;
    }
}
class ScreenOutput extends Text
{
    public function output()
    {
        echo "Вывод на экран:" . $this->text . "<br>";
    }
}
class PrintOutput extends Text
{
    public function output()
    {
        echo "Вывод на принтер:" . $this->text . "<br>";
    }
}

$text1 = new ScreenOutput("Hello");
$text1->output();

$text2 = new PrintOutput("Okay");
$text2->output();
