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

        // Vérification des conditions pour générer le mot de passe
        $mapping = [
            [$upperCaseLetters, $upperCaseAlphabet, 30],
            [$digits, $digitsAlphabet, 15],
            [$specialCharaters, $specialCharatersAlphabet, 15]
        ];

        foreach ($mapping as [$constraintEnabled, $constraintAlphabet, $pourcent]) {
            if ($constraintEnabled) {
                $characters[] = $this->pickUpRandomCharacters($constraintAlphabet, $length, $pourcent);
            }
        }

        // Remettre $characters sur 1 niveau (Suppression du multilevel)
        $characters = array_merge(...$characters);

        $missingLetters = $length - count($characters);
        for ($i = 0; $i < $missingLetters; $i++) {
            $characters[] = $lowerCaseAlphabet[random_int(0, count($lowerCaseAlphabet) - 1)];
        }

        // Mélange des lettres
        $this->shuffle($characters);

        return implode($characters);
    }

    /**
     * Mélange d'un tableau valide pour la cryptographie
     */
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

    /**
     * Sélection aléatoire de caractères  à partir d'un tableau et suivant le pourcentage. Min 1
     */
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
