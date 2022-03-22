<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PagesController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('pages/home.html.twig');
    }

    #[Route('/generate-password', name : 'app_generate_password')]
    public function generatePassword(Request $request): Response
    {
        $length = $request->query->getInt('length');
        $uppercaseLetters = $request->query->getBoolean('uppercase_letters');
        $digits = $request->query->getBoolean('digits');
        $specialCharaters = $request->query->getBoolean('special_characters');
        
        $password = "";

        $characters = [];

        if ($uppercaseLetters) {
            $nbUppercase = random_int(1, (int) ceil($length * 0.3)); // 30% de upperCase
            $upperCase = range('A', 'Z');
            for ($i = 0; $i < $nbUppercase; $i++) {
                $characters[] = $upperCase[random_int(0, count($upperCase) - 1)];
            }
        }

        if ($digits) {
            $nbDigits = random_int(1, (int) ceil($length * 0.1)); // 10%
            $digits = range(0, 9);
            for ($i = 0; $i < $nbDigits; $i++) {
                $characters[] = $digits[random_int(0, count($digits) - 1)];
            }
        }

        if ($specialCharaters) {
            $nbSpecials = random_int(1, (int) ceil($length * 0.1)); // 10%
            $specials = str_split('!#$%&()+-*:;=?~');
            for ($i = 0; $i < $nbSpecials; $i++) {
                $characters[] = $specials[random_int(0, count($specials) - 1)];
            }
        }

        $lowerCaseLetters = range('a', 'z');
        $missingLetters = $length - count($characters);
        for ($i = 0; $i < $missingLetters; $i++) {
            $characters[] = $lowerCaseLetters[random_int(0, count($lowerCaseLetters) - 1)];
        }

        $this->shuffle($characters);
        $password = implode($characters);

        return $this->render('pages/password.html.twig', [
            'password' => $password
        ]);
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
}
