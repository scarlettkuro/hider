<?php

class Hider
{
  const CHARACTER_BIT_LENGTH = 8;

  protected $image;
  protected $width;

  public function Hider($image)
  {
    $this->image = $image;
    $this->width = imagesx($image);
  }

  protected function changeLastBit($number, bool $bit)
  {
    $bitNumber = $bit ? 1 : 0;
    return (($number >> 1) << 1) | $bitNumber;
  }

  protected function getLastBit($number)
  {
    return ($number & 1) == 1 ? true : false;
  }

  private function characterToBits($character)
  {
    $ascii = ord($character);
    $binaryString = str_pad(decbin($ascii), self::CHARACTER_BIT_LENGTH, 0, STR_PAD_LEFT);
    $bits = [];
    foreach(str_split($binaryString) as $bit)
    {
        $bits[] = $bit == "1" ? true : false;
    }

    return $bits;
  }

  private function bitsToCharacter($bits)
  {
    $binaryString = "";
    foreach($bits as $bit)
    {
        $binaryString .= $bit ? "1" : "0";
    }

    return chr(bindec($binaryString));
  }

  private function indexToPoint($index)
  {
    return [
        "x" => $index % $this->width,
        "y" => intval(ceil($index / $this->width))
    ];
  }

  private function writeCharacter($character, $index)
  {
    foreach($this->characterToBits($character) as $i => $bit)
    {
        $point = $this->indexToPoint($index * self::CHARACTER_BIT_LENGTH + $i);
        $x = $point["x"];
        $y = $point["y"];
        $rgbValue = imagecolorat($this->image, $x, $y);
        imagesetpixel($this->image, $x, $y, $this->changeLastBit($rgbValue, $bit));
    }
  }

  private function readCharacter($index)
  {
    $bits = [];
    for($i = 0; $i < self::CHARACTER_BIT_LENGTH; $i++)
    {
        $point = $this->indexToPoint($index * self::CHARACTER_BIT_LENGTH + $i);
        $x = $point["x"];
        $y = $point["y"];
        $rgbValue = imagecolorat($this->image, $x, $y);
        $bits[] = $this->getLastBit($rgbValue);
    }

    return $this->bitsToCharacter($bits);
  }

  public function writeString($string)
  {
    $this->writeCharacter(chr(0), 0);
    foreach(str_split($string) as $index => $character)
    {
        $this->writeCharacter($character, $index + 1);
    }
    $this->writeCharacter(chr(0), strlen($string) + 1);
  }

  public function readString()
  {
    if ($this->readCharacter(0) != chr(0)) return "";
    $string = "";
    $index = 1;
    while($this->readCharacter($index) != chr(0) && $index < $this->maxIndex())
    {
        $string .= $this->readCharacter($index++);
    }

    return $string;
  }

  public function getImage()
  {
    return $this->image;
  }

  public function maxIndex()
  {
      return intval((imagesx($this->image) * imagesy($this->image)) / self::CHARACTER_BIT_LENGTH) - 2;
  }

}
