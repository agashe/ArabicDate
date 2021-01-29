<?php

include "../src/ArabicDate.php";

use ArabicDate\ArabicDate;

try {
    $foo = new ArabicDate('d/m/Y', 'english', 'gregorian');
    print $foo->get() . PHP_EOL;
} catch (\Throwable $th) {
    print $th->getMessage() . PHP_EOL;
}
