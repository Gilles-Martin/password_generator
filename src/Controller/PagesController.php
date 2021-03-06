<?php

namespace App\Controller;

use App\Service\PasswordGenerator;
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
    public function generatePassword(Request $request, PasswordGenerator $passwordGenerator): Response
    {
        $password = $passwordGenerator->generate(
            length: max(min($request->query->getInt('length'), 60), 8),
            upperCaseLetters: $request->query->getBoolean('uppercase_letters'),
            digits: $request->query->getBoolean('digits'),
            specialCharaters: $request->query->getBoolean('special_characters')
        );

        return $this->render('pages/password.html.twig', [
            'password' => $password
        ]);
    }
}
