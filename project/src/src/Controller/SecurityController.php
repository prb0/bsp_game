<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $utils, AuthorizationCheckerInterface $authChecker)
    {
        $error = $utils->getLastAuthenticationError();
        $lasUsername = $utils->getLastUsername();
        $isUser = $authChecker->isGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('security/login.html.twig', [
            'error'             => $error,
            'last_username'     => $lasUsername,
            'isUser'            => $isUser
        ]);
    }

    /**
     * @Route("/regpage", name="regpage")
     */
    public function regpage(Request $request, AuthorizationCheckerInterface $authChecker)
    {
        $isUser = $authChecker->isGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('security/regpage.html.twig', [
            'isUser'            => $isUser
        ]);
    }

    /**
     * @Route("/registration", name="registration")
     */
    public function registration(
        Request $request,
        AuthorizationCheckerInterface $authChecker,
        UserPasswordEncoderInterface $encoder
    ) {
        $rep                = $this->getDoctrine()->getRepository(User::class);
        $email              = $request->request->get('_email');
        $username           = $request->request->get('_username');
        $password           = $request->request->get('_password');
        $password_confirm   = $request->request->get('_password_confirm');
        $free_points        = 500;

        if ($authChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['success' => false, 'message' => 'Вы уже зарегистрированы']);
        }
        
        if (strlen($username) < 3) {
            return $this->json(['success' => false, 'message' => 'Никнейм должен состоять из 3 и более символов']);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->json(['success' => false, 'message' => 'Введите корректный E-Mail']);
        }

        if (strlen($password) < 6) {
            return $this->json(['success' => false, 'message' => 'Слишком короткий пароль']);
        }

        if ($password != $password_confirm) {
            return $this->json(['success' => false, 'message' => 'Пароли не совпадают']);
        }

        if (null != $rep->findOneBy(['username' => $username])) {
            return $this->json(['success' => false, 'message' => 'Такой пользователь уже существует']);
        }

        if (null != $rep->findOneBy(['email' => $email])) {
            return $this->json(['success' => false, 'message' => 'Данный E-Mail уже используется']);
        }

        try {
            $user = new User();
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setPoints($free_points);
            $user->setPassword(
                $encoder->encodePassword($user, $password)
            );

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);

            $em->flush();
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => 'Неизвестная ошибка! ' . $e->getMessage()]);
        }

        return $this->json(['success' => true, 'message' => "Вы успешно зарегистрированы! Вам начислено <b>$free_points</b> бонусов!"]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }
}
