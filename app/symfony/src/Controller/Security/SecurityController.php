<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\Security\LoginType;
use App\Form\Security\RegisterType;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/user/login", name="login", methods={"GET","POST"})
     * @param AuthenticationUtils $authUtils
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function user(
        AuthenticationUtils $authUtils,
        Request $request
    ): Response {
        $form = $this->createForm(LoginType::class, [
            '_username' => $authUtils->getLastUsername(),
        ]);

        return $this->render('security/login.html.twig', [
            'error' => $authUtils->getLastAuthenticationError(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/register", name="register", methods={"GET","POST"})
     * @param Request $request
     * @param UserManager $userManager
     * @return Response
     * @throws \Exception
     */
    public function register(Request $request, UserManager $userManager): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $user = new User();
        $user->setRole(User::ROLE_USER);
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->registerAccount($user);

            return $this->redirectToRoute('register');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/logout", name="user_logout", methods={"GET"})
     */
    public function logout()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }
}