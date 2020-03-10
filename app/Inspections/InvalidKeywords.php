<?php

namespace App\Inspections;

class InvalidKeywords
{
    protected $keywords = [
        'yahoo customer support',
    ];

    /**
     * @param string $text
     * @throws \Exception
     */
    public function detect(string $text)
    {
        foreach ($this->keywords as $keyword) {
            if (stripos($text, $keyword) !== false) {
                throw new \Exception('Spam is detected.');
            }
        }
    }
}
