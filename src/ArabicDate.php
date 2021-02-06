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
        ($format)? $this->setFormat($format) : 'd/m/Y H:i:s a';
        ($language)? $this->setLanguage($language) : 'arabic';
        ($calendar)? $this->setCalendar($calendar) : 'hijri';
    }

    /**
     * Set the format pattern for "date" method
     * 
     * @param string $format
     * @return void
     */
    public function setFormat(string $format = null)
    {
        if (!is_string($format)) {
            throw new \Exception("ArabicDate Error: Invalid Format");
            return;
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

        return [
            'g_day'   => $day,
            'g_month' => $month,
            'g_year'  => $year,

            'h_day'   => $id,
            'h_month' => $im,
            'h_year'  => $iy,
        ];
    }

    /**
     * Convert english digits to arabic
     * 
     * @param string $digits
     * @return string 
     */
    private function convertDigit($digits)
    {
        $arabicDigits = [
            '٠', '١', '٢', '٣' , '٤' , '٥', '٦', '٧' , '٨' , '٩'
        ];

        $result = '';
        foreach (str_split($digits) as $digit) 
            $result .= $arabicDigits[$digit];

        return $result;
    }

    /**
     * Generate the desired date based on the provided parameters 
     * 
     * @return string 
     */
    public function get()
    {
        /**
         * Allowed characters (for hijri !!)
         * 
         * Days: d , D , j
         * Months: m , M , n
         * Years: y , Y
         * Time: h , H , i , s , a , A
         */

        /** Define arabic/english digits , months and days names **/
        $arabicDays = ['الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت', 'الأحد'];

        $arabicGregorianMonths = [
            'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
            'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
        ];
        
        $arabicHijriMonths = [
            'محرم', 'صفر', 'ربيع الأول', 'ربيع الثاني', 'جماد الأول', 'جماد الثاني'
            , 'رجب', 'شعبان', 'رمضان', 'شوال', 'ذو القعدة', 'ذو الحجة'
        ];

        $arabicAmAndPm = [
            'am' => 'صباحاً', 'AM' => 'صباحاً',
            'pm' => 'مساءً', 'PM' => 'مساءً',
        ];
        
        $nameOfDaysInArabic = [
            'Al-Ithnayn', 'Ath-Thulathaa', 'Al-Arbi\'aa', 
            'Al-Khamees', 'Al-Jumu\'ah', 'As-Sabt',
            'Al-Ahad'
        ];

        $englishHijriMonths = [
            'Muharram', 'Safar', 'Rabi-Al-Awwal', 
            'Rabi-Al-Thani', 'Jumada-Al-Awwal', 'Jumada-Al-Thani', 
            'Rajab', 'Shaban', 'Ramadan', 
            'Shawwal', 'Zul-Qa’dah', 'Zul-Hijjah'
        ];

        $namesOfAmAndPmInArabic = [
            'am' => 'Sabahaan', 'AM' => 'Sabahaan',
            'pm' => 'Masa', 'PM' => 'Masa',
        ];

        /** Generate Date **/
        $nowGregorian = date($this->format);

        /** Convert Date **/
        $nowHijri = $this->gregorianToHijri();
        
        /** Baset on the calendar & language return the output **/
        $output = '';

        if ($this->language === 'arabic') {
            if ($this->calendar == 'gregorian') {
                foreach (str_split($this->format) as $char) {
                    // Time, Day , Month and Year (numeric)
                    if ($char == 'd' || $char == 'j' || $char == 'm' || 
                        $char == 'n' || $char == 'y' || $char == 'Y' ||
                        $char == 'h' || $char == 'H' || $char == 'i' || 
                        $char == 's') 
                        $output .= $this->convertDigit(date($char));
                    
                    // Day (textual) 
                    elseif ($char == 'D')
                        $output .= $arabicDays[date('N') - 1];
                    
                    // Month (textual)
                    elseif ($char == 'M')
                        $output .= $arabicGregorianMonths[date('n') - 1];
                    
                    // AM & PM
                    elseif ($char == 'a' || $char == 'A')
                        $output .= $arabicAmAndPm[date($char)];

                    // Anything else ...
                    else 
                        $output .= $char;
                }
            }
            elseif ($this->calendar == 'hijri') {
                foreach (str_split($this->format) as $char) {
                    // Day
                    if ($char == 'd') 
                        $output .= $this->convertDigit(
                            ($nowHijri['h_day'] < 10)? ('0'.$nowHijri['h_day']) : $nowHijri['h_day']
                        );
                    elseif ($char == 'D')
                        $output .= $arabicDays[date('N') - 1];
                    elseif ($char == 'j') 
                        $output .= $this->convertDigit($nowHijri['h_day']);
                    
                    // Month
                    elseif ($char == 'm') 
                        $output .= $this->convertDigit(
                            ($nowHijri['h_month'] < 10)? ('0'.$nowHijri['h_month']) : $nowHijri['h_month']
                        );
                    elseif ($char == 'M')
                        $output .= $arabicHijriMonths[$nowHijri['h_month'] - 1];
                    elseif ($char == 'n') 
                        $output .= $this->convertDigit($nowHijri['h_month']);
                    
                    // Year
                    elseif ($char == 'y') 
                        $output .= $this->convertDigit(substr($nowHijri['h_year'], -2));
                    elseif ($char == 'Y') 
                        $output .= $this->convertDigit($nowHijri['h_year']);
                    
                    // Time
                    elseif ($char == 'h' || $char == 'H' || $char == 'i' || $char == 's') 
                        $output .= $this->convertDigit(date($char));
                    
                    // AM & PM
                    elseif ($char == 'a' || $char == 'A') 
                        $output .= $arabicAmAndPm[date($char)];

                    // Anything else ...
                    else 
                        $output .= $char;
                }
            }
        }
        elseif ($this->language === 'english') {
            if ($this->calendar == 'gregorian') {
                $output = $nowGregorian;
            }
            elseif ($this->calendar == 'hijri') {
                foreach (str_split($this->format) as $char) {
                    // Day
                    if ($char == 'd') 
                        $output .= ($nowHijri['h_day'] < 10)? ('0'.$nowHijri['h_day']) : $nowHijri['h_day'];
                    elseif ($char == 'D')
                        $output .= $nameOfDaysInArabic[date('N') - 1];
                    elseif ($char == 'j') 
                        $output .= $nowHijri['h_day'];
                    
                    // Month
                    elseif ($char == 'm') 
                        $output .= ($nowHijri['h_month'] < 10)? ('0'.$nowHijri['h_month']) : $nowHijri['h_month'];
                    elseif ($char == 'M')
                        $output .= $englishHijriMonths[$nowHijri['h_month'] - 1];
                    elseif ($char == 'n') 
                        $output .= $nowHijri['h_month'];
                    
                    // Year
                    elseif ($char == 'y') 
                        $output .= substr($nowHijri['h_year'], -2);
                    elseif ($char == 'Y') 
                        $output .= $nowHijri['h_year'];
                    
                    // AM & PM
                    elseif ($char == 'a' || $char == 'A') 
                        $output .= $namesOfAmAndPmInArabic[date($char)];

                    // Anything else ...
                    else 
                        $output .= $char;
                }
            }
        }

        return $output;
    }
}
