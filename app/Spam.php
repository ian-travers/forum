<?php

namespace App;

class Spam
{
    /**
     * @param string $body
     * @return bool
     * @throws \Exception
     */
    public function detect(string $body): bool
    {
        $this->detectInvalidKeywords($body);

        return false;
    }

    /**
     * @param string $body
     * @throws \Exception
     */
    protected function detectInvalidKeywords(string $body): void
    {
        $invalidKeywords = [
            'yahoo customer support',
        ];

        foreach ($invalidKeywords as $keyword) {
            if (stripos($body, $keyword) !== false) {
                throw new \Exception('Spam is detected.');
            }
        }
    }
}
