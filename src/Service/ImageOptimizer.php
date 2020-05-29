<?php

namespace App\Service;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class ImageOptimizer
{
  //private const MAX_WIDTH = 600;
  //private const MAX_HEIGHT = 600;

  private $imagine;

  public function __construct()
  {
    $this->imagine = new Imagine();
  }

  public function resize(string $filename, int $maxWidth, int $maxHeight): void
  {
    list($iwidth, $iheight) = getimagesize($filename);
    $ratio = $iwidth / $iheight;
    // $width = self::MAX_WIDTH;
    // $height = self::MAX_HEIGHT;

    if ($iwidth > $maxWidth || $iheight > $maxHeight) {
      if ($maxWidth / $maxHeight > $ratio) {
        $maxWidth = $maxHeight * $ratio;
      }
      else {
        $maxHeight = $maxWidth / $ratio;
      }

      $photo = $this->imagine->open($filename);
      $photo -> resize(new Box($maxWidth, $maxHeight)) -> save($filename);
    }
  
  }

}