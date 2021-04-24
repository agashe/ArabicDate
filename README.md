# ArabicDate
A PHP Package to handle hijri/gregorian calendar in both arabic and english

## Features
- Hijri/Gregorian calendars.
- Support both arabic/english.
- Very lightwieght , 0 dependencies.
- Easy to use , set few options and run.

## Installation

``` 
composer require agashe/arabic-date
```

## Documentation

ArabicDate hijri converter depends on Kuwati Algorithm , in it's core a
single class with few setters/gettes to configure your result.

After installation is done , include the class in your project by:
* including vendor/autoload.php for Native PHP projects
* or adding the class to your framework config , *for example app/config/app.php for laravel* , 

This table demonstrate the basic three setters , we use to initialize ArabicDate

| Method | Parameter | Options | Description |
| :----: | :-------: | :-----: | :---------: |
| setCalendar | string | 'hijri', 'gregorian' | Select the calendar |
| setLanguage | string | 'arabic', 'english'  | Select the language |
| setFormat | string | Check the table below  | Select the date format pattern |


ArabicDate uses the default PHP date format characters , this table shows all
supported characters in hijri calendar. 
*(for gregorian calendar , all characters work normally !!)*

| Format character |                         Description                      |
| :--------------: | :------------------------------------------------------: |
|        d         | Day of the month, 2 digits with leading zeros            |
|        D         | A textual representation of a day                        |
|        j         | Day of the month without leading zeros                   |
|        m         | Numeric representation of a month, with leading zeros    |
|        M         | A textual representation of a month                      |
|        n         | Numeric representation of a month, without leading zeros |
|        y         | A two digit representation of a year                     |
|        Y         | A full numeric representation of a year, 4 digits        |
|        h         | 12-hour format of an hour with leading zeros             |
|        H         | 24-hour format of an hour with leading zeros             |
|        i         | Minutes with leading zeros                               |
|        s         | Seconds with leading zeros                               |
|       a/A        | AM/PM , but in arabic there's no lowercase and uppercase |
|                  | so they both are the same!                               |

This is the basic example for using ArabicDate

```
<?php

include "vendor/autoload.php";

use ArabicDate\ArabicDate;

$date = new ArabicDate();

$date->setCalendar('hijri');
$date->setLanguage('english');
$date->setFormat('Y-m-d');

echo $date->get();

// and also you can use the constructor
$date = new ArabicDate('y M D h:i:s a', 'arabic', 'gregorian');
echo $date->get();

```

## Examples

```

// 1. hijri date in arabic
$date = new ArabicDate('Y/m/d هـ', 'arabic', 'hijri');
echo $date->get(); // ١٤٤٢/٠٩/١٢ هـ

// 2. hijri date in arabic with time
$date = new ArabicDate('Y-m-d h:i:s a', 'arabic', 'hijri');
echo $date->get(); // ١٤٤٢-٠٩-١٢ ٠٢:٢٢:٥٤ مساءً

// 3. gregorian date in arabic
$date = new ArabicDate('y M D h:i:s a', 'arabic', 'gregorian');
echo $date->get(); // ٢١ أبريل السبت ٠٢:١٩:٢٢ مساءً

// 4. hijri date in english with time
$date = new ArabicDate();

$date->setCalendar('hijri');
$date->setLanguage('english');
$date->setFormat('Y-m-d h:i:s a');

echo $date->get(); // 1442-09-12 02:26:10 Masa

```

## License
(ArabicDate) released under the terms of the MIT license.
