<?php

include "../src/ArabicDate.php";

use ArabicDate\ArabicDate;
//test
try {
    $foo = new ArabicDate('H:i:s');
    $foo->get();
} catch (\Throwable $th) {
    print $th->getMessage() . PHP_EOL;
}