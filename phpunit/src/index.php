<?php

use ParagonIE\ConstantTime\Encoding;
use ParagonIE\ConstantTime\Base64;
use ParagonIE\ConstantTime\Base32;

require "../vendor/autoload.php";

$data = "Hello";
echo Base64::encode($data), "\n";
echo Base32::encode($data), "\n";

$encodeData= Base32::encode($data);

echo Base32::decode($encodeData), "\n";





