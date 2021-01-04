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

    public function __construct(string $format = null, 
        string $language = null, string $calendar = null)
    {
        $this->format   = ($format)? $this->setFormat($format) : 'd/m/Y H:i:s a';
        $this->language = ($language)? $this->setLanguage($language) : 'arabic';
        $this->calendar = ($calendar)? $this->setCalendar($calendar) : 'hijri';
    }

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
    
    public function setLanguage(string $language = null)
    {
        $availableLanguages = ['arabic', 'english'];

        if (in_array($language, $availableLanguages)) {
            $this->language = $language;
        } else {
            throw new \Exception("ArabicDate Error: Invalid Language");
        }
    }
    
    public function setCalendar(string $calendar = null)
    {
        $availableCalendars = ['hijri', 'gregorian'];

        if (in_array($calendar, $availableCalendars)) {
            $this->calendar = $calendar;
        } else {
            throw new \Exception("ArabicDate Error: Invalid Calendar");
        }
    }

    public function get()
    {
        print 'good';
    }
}