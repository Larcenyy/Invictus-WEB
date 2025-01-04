<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @param UserPasswordHasherInterface $passwordHasher
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route(path: "/login", name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, UserPasswordHasherInterface $passwordHasher, Request $request, EntityManagerInterface $entityManager): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $registerForm = $this->createForm(RegisterUserType::class);
        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $data = $registerForm->getData();

            $ipAddress = $request->getClientIp(); // Récupérer l'adresse IP du client
            $existingAccounts = $entityManager->getRepository(User::class)
                ->findBy(['ip' => $ipAddress]);

            if (count($existingAccounts) > 0) {
                $allowedAccounts = $existingAccounts[0]->getNumberAccountIp();

                if (count($existingAccounts) >= $allowedAccounts) {
                    $this->addFlash('danger', "Vous ne pouvez pas créer plus de $allowedAccounts comptes avec cette adresse IP !");
                    return $this->redirectToRoute("app_register");
                }
            }

            $existingUser = $entityManager->getRepository(User::class)->findOneBy([
                'email' => $data['register-email']
            ]);

            $existingName= $entityManager->getRepository(User::class)->findOneBy([
                'username' => $data['register-username']
            ]);

            $existingDiscord = $entityManager->getRepository(User::class)->findOneBy([
                'discord_name' => $data['register-discord']
            ]);

            if ($existingUser || $existingDiscord || $existingName) {
                $this->addFlash('danger', "Impossible d'inscrire un compte avec des données déjà utilisés !");
                return $this->redirectToRoute("app_login");
            }


            // create a new user
            $user = new User();
            $user->setEmail($data['register-email']);
            $user->setUsername($data['register-username']);
            $user->setDiscord($data['register-discord']);
            $user->setIp($ipAddress); // Associer l'IP au compte
            $user->setPassword(
                $passwordHasher->hashPassword($user, $data['password']) // hash the password
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "Vous vous êtes inscrit avec succès !");
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'registerForm' => $registerForm,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Déconnction de l'utilisateur
    }
}
