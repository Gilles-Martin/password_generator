<?php

namespace App\Service;

class PasswordGenerator
{
    public function generate($request): string
    {
        $length = $request->query->getInt('length');
        $uppercaseLetters = $request->query->getBoolean('uppercase_letters');
        $digits = $request->query->getBoolean('digits');
        $specialCharaters = $request->query->getBoolean('special_characters');
       
        $password = "";

        $characters = [];

        if ($uppercaseLetters) {
            $upperCase = range('A', 'Z');
            $characters = array_merge($characters, $this->pickUpRandomCharacters($upperCase, $length, 30)) ;
        }

        if ($digits) {
            $digits = range(0, 9);
            $characters = array_merge($characters, $this->pickUpRandomCharacters($digits, $length, 10));
        }

        if ($specialCharaters) {
            $specials = str_split('!#$%&()+-*:;=?~');
            $characters = array_merge($characters, $this->pickUpRandomCharacters($specials, $length, 10));
        }

        $lowerCaseLetters = range('a', 'z');
        $missingLetters = $length - count($characters);
        for ($i = 0; $i < $missingLetters; $i++) {
            $characters[] = $lowerCaseLetters[random_int(0, count($lowerCaseLetters) - 1)];
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
