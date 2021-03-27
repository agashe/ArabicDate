<?php

include "../src/ArabicDate.php";

use PHPUnit\Framework\TestCase;
use ArabicDate\ArabicDate;

class ArabicDateTest extends TestCase
{
    public function testSetFormat()
    {
        $tries = 0;
        $invalidValues = [50, 100.50, false, true, null, [], new stdClass()];
        $validValues = ['d/m/y', 'H:i:s', 'D - M - Y', 'j/n/Y H:i:s A', 'test'];

        foreach (array_merge($invalidValues, $validValues) as $value) {
            try {
                $date = new ArabicDate();
                $date->setFormat($value);
            }
            catch (Exception $error) {
                $tries += 1;
            }
        }

        $this->assertEquals($tries, count($invalidValues));
    }
    
    public function testSetLanguage()
    {
        $tries = 0;
        $validValues = ['arabic', 'english'];
        $invalidValues = [50, 100.50, false, true, null, [], new stdClass()];

        foreach ($invalidValues as $value) {
            try {
                $date = new ArabicDate();
                $date->setLanguage($value);
            }
            catch (Exception $error) {
                $tries += 1;
            }
        }

        $this->assertEquals($tries, count($invalidValues));
    }
    
    public function testSetCalendar()
    {
        $tries = 0;
        $validValues = ['hijri', 'gregorian'];
        $invalidValues = [50, 100.50, false, true, null, [], new stdClass()];

        foreach ($invalidValues as $value) {
            try {
                $date = new ArabicDate();
                $date->setLanguage($value);
            }
            catch (Exception $error) {
                $tries += 1;
            }
        }

        $this->assertEquals($tries, count($invalidValues));
    }
}

