<?php
// File: unit.php

//require_once __DIR__.'/vendor/autoload.php';

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class UnitTest extends TestCase
{
    public function addtion(): void
    {
        $x=4;
        $y=3;

        $sum= $x+$y;

       $this->assertEquals(7,$sum); 
    }
}
