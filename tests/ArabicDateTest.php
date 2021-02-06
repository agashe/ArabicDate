<?php

include "../src/ArabicDate.php";

use ArabicDate\ArabicDate;

try {
    $foo = new ArabicDate('D/M/Y a', 'english', 'hijri');
    print $foo->get() . PHP_EOL;
} catch (\Throwable $th) {
    print $th->getMessage() . PHP_EOL;
}
