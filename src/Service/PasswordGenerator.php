<?php

namespace App\Service;

class PasswordGenerator
{
    public function generate(
        int $length,
        bool $upperCaseLetters = false,
        bool $digits = false,
        bool $specialCharaters = false
    ): string {
        $password = "";

        $characters = [];
        $upperCaseAlphabet = range('A', 'Z');
        $digitsAlphabet = range(0, 9);
        $specialCharatersAlphabet = str_split('!#$%&()+-*:;=?~');
        $lowerCaseAlphabet = range('a', 'z');

        $mapping = [
            [$upperCaseLetters, $upperCaseAlphabet, 30],
            [$digits, $digitsAlphabet, 15],
            [$specialCharaters, $specialCharatersAlphabet, 15]
        ];

        foreach ($mapping as [$constraintEnabled, $constraintAlphabet, $pourcent]) {
            if ($constraintEnabled) {
                $characters = [
                    ...$characters,
                    ...$this->pickUpRandomCharacters($constraintAlphabet, $length, $pourcent)
                ];
            }
        }

        $missingLetters = $length - count($characters);
        for ($i = 0; $i < $missingLetters; $i++) {
            $characters[] = $lowerCaseAlphabet[random_int(0, count($lowerCaseAlphabet) - 1)];
        }

        $this->shuffle($characters);
        return implode($characters);
    }

    private function shuffle(array &$arr): void
    {
        $arr = array_values($arr);
        $length = count($arr);
        for ($i = $length - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            $temp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
        }
    }

    private function pickUpRandomCharacters($characters, $length, $percent = '10'): array
    {
        $randomCharacters = [];
        $nbCharacters = random_int(1, (int) ceil($length * $percent / 100));
        for ($i = 0; $i < $nbCharacters; $i++) {
            $randomCharacters[] = $characters[random_int(0, count($characters) - 1)];
        }
        return $randomCharacters;
    }
}
