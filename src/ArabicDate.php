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
     * @return array
     */
    private function gregorianToHijri()
    {
        
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
        $nowHijri = '';

        /** Baset on the calendar & language return the output **/
        if ($this->language === 'arabic') {
            
        }
        elseif ($this->language === 'english') {

        }
    }
}