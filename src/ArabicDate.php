<?php

/**
 * ArabicDate v1.0.0
 * <https://github.com/agashe/ArabicDate>
 *
 * Handle hijri/gregorian calendar in both arabic and english.
 *
 * Author: Mohamed Yousef <engineer.mohamed.yossef@gmail.com>
 * License: MIT
 */

namespace ArabicDate;

class ArabicDate
{
    private $format;
    private $language;
    private $calendar;

    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct(string $format = null, 
        string $language = null, string $calendar = null)
    {
        $this->format   = ($format)? $this->setFormat($format) : 'd/m/Y H:i:s a';
        $this->language = ($language)? $this->setLanguage($language) : 'arabic';
        $this->calendar = ($calendar)? $this->setCalendar($calendar) : 'hijri';
    }

    /**
     * Set the format pattern for "date" method
     * 
     * @param string $format
     * @return void
     */
    public function setFormat(string $format = null)
    {
        $allowedCharacters = [
            'd', 'D', 'm',
            'M', 'n', 'y', 
            'Y', 'h', 'H', 
            'i', 's', ':',
            '/', '_', '-'
        ];

        foreach (str_split($format) as $char) {
            if (!in_array($char, $allowedCharacters)) {
                throw new \Exception("ArabicDate Error: Invalid Format");
                break;
            }
        }
        
        $this->format = $format;
    }
    
    /**
     * Set the language for the output
     * 
     * @param string $language
     * @return void
     */
    public function setLanguage(string $language = null)
    {
        $availableLanguages = ['arabic', 'english'];

        if (in_array($language, $availableLanguages)) {
            $this->language = $language;
        } else {
            throw new \Exception("ArabicDate Error: Invalid Language");
        }
    }
    
    /**
     * Set the calendar hijri/gregorian
     * 
     * @param string $calendar
     * @return void
     */
    public function setCalendar(string $calendar = null)
    {
        $availableCalendars = ['hijri', 'gregorian'];

        if (in_array($calendar, $availableCalendars)) {
            $this->calendar = $calendar;
        } else {
            throw new \Exception("ArabicDate Error: Invalid Calendar");
        }
    }

    /**
     * Convert the current date into hijri
     * 
     * NOTE : this function based on "Kuwaiti Algorithm" and this code
     *        original source can be found at :
     *        https://www.al-habib.info/islamic-calendar/hijricalendartext.htm
     * 
     * @return array
     */
    private function gregorianToHijri()
    {        
        $day   = (float) date('d');
        $month = (float) date('m');
        $year  = (float) date('Y');
        
        $y = $year;
        $m = $month;
        if ($m < 3) {
            $y -= 1;
            $m += 12;
        }

        $a = floor($y / 100.0);
        $b = (2 - $a) + floor($a / 4.0);
        $jd = floor(365.25 * ($y + 4716)) + floor(30.6001 * ($m + 1)) + $day + $b - 1524;
        
        if ($jd > 2299160) {
            $a = floor(($jd - 1867216.25) / 36524.25);
            $b = 1 + $a - floor($a / 4.0);
        }

        $bb = $jd + $b + 1524;
        $cc = floor(($bb - 122.1) / 365.25);
        $dd = floor(365.25 * $cc);
        $ee = floor(($bb - $dd) / 30.6001);
        $day = ($bb - $dd) - floor(30.6001 * $ee);
        $month = $ee - 1;
        
        if ($ee > 13) {
            $cc += 1;
            $month = $ee - 13;
        }

        $year = $cc - 4716;
        $iyear = 10631.0 / 30.0;
        $epochastro = 1948084;
        $epochcivil = 1948085;
        
        $shift1 = 8.01 / 60.0;
        
        $z = $jd - $epochastro;
        $cyc = floor($z / 10631.0);
        $z = $z - (10631 * $cyc);
        
        $j = floor(($z - $shift1) / $iyear);
        $iy = (30 * $cyc) + $j;
        $z = $z - floor(($j * $iyear) + $shift1);
        
        $im = floor(($z + 28.5001) / 29.5);
        if ($im == 13) $im = 12;
        
        $id = $z - floor((29.5001 * $im) - 29);

        $myRes = [];
        $myRes[0] = $day;
        $myRes[1] = $month;
        $myRes[2] = $year;
        $myRes[4] = $id;
        $myRes[5] = $im;
        $myRes[6] = $iy;

        return $myRes;
    }

    /**
     * Generate the desired date based on the provided parameters 
     * 
     * @return string 
     */
    public function get()
    {
        /** Define arabic/english months and days names **/
        $arabicDays = ['السبت', 'الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'];
        $arabicGregorianMonths = [
            'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
        ];
        
        $arabicHijriMonths = [
            'محرم', 'صفر', 'ربيع الأول', 'ربيع الثاني', 'جماد الأول', 'جماد الثاني'
            , 'رجب', 'شعبان', 'رمضان', 'شوال', 'ذو القعدة', 'ذو الحجة'
        ];
        
        $englishHijriMonths = [
            'Muharram', 'Safar', 'Rabi-Al-Awwal', 
            'Rabi-Al-Thani', 'Jumada-Al-Awwal', 'Jumada-Al-Thani', 
            'Rajab', 'Shaban', 'Ramadan', 
            'Shawwal', 'Zul-Qa’dah', 'Zul-Hijjah'
        ];
        
        /** Generate Date **/
        $nowGregorian = date($this->format);

        /** Convert Date **/
        $nowHijri = $this->gregorianToHijri();

        /** Baset on the calendar & language return the output **/
        if ($this->language === 'arabic') {
            
        }
        elseif ($this->language === 'english') {

        }
    }
}