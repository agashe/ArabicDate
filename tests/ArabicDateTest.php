<?php

include "../src/ArabicDate.php";

use ArabicDate\ArabicDate;

date_default_timezone_set('Africa/Cairo');

try {
    $foo = new ArabicDate('d/m/Y h:i:s A', 'arabic', 'hijri');
    print $foo->get() . PHP_EOL;
} catch (\Throwable $th) {
    print $th->getMessage() . PHP_EOL;
}
