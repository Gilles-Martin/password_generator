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

        $characters = range('a', 'z');
        if ($uppercaseLetters) {
            $characters = array_merge($characters, range('A', 'Z'));
        }

        if ($digits) {
            $characters = array_merge($characters, range(0, 9));
        }

        if ($specialCharaters) {
            $characters = array_merge($characters, str_split('!#$%&()+-*:;=?~'));
        }

        $password = "";

        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, count($characters) - 1)];
        }

        return $this->render('pages/password.html.twig', [
            'password' => $password
        ]);
    }
}
