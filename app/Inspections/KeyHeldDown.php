<?php

namespace App\Inspections;

class KeyHeldDown
{
    /**
     * @param string $text
     * @throws \Exception
     */
    public function detect(string $text)
    {
        if (preg_match('/(.)\\1{4,}/', $text)) {
            throw new \Exception('Spam is detected.');
        }
    }
}
