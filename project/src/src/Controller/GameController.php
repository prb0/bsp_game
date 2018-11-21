<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

class GameController extends AbstractController
{
    /**
     * @Route("/game", name="game")
     */
    public function game()
    {
        return $this->render('game/index.html.twig', [
            'points' => $this->getUser()->getPoints()
        ]);
    }

    /**
     * @Route("/game/findenemy", name="findenemy")
     */
    public function findenemy()
    {
        sleep(random_int(1, 2));
        return $this->json(['success' => true]);
    }

    /**
     * @Route("/game/execute", name="gameexecute")
     */
    public function execute(Request $request)
    {
        $choosen = $request->request->get('choosen');
        $user = $this->getUser();

        if (random_int(0, 9) > 2) {
            $user->setPoints($user->getPoints() + 100);

            switch ($choosen) {
                case 'Камень':
                    $response = ['success' => true, 'message' => 'Поздравляем, вы победили!', 'opponent' => 'Ножницы', 'bonus' => $user->getPoints()];
                    break;

                case 'Ножницы':
                    $response = ['success' => true, 'message' => 'Поздравляем, вы победили!', 'opponent' => 'Бумага', 'bonus' => $user->getPoints()];
                    break;

                case 'Бумага':
                    $response = ['success' => true, 'message' => 'Поздравляем, вы победили!', 'opponent' => 'Камень', 'bonus' => $user->getPoints()];
                    break;
                
                default:
                    return $this->json(['success' => false, 'message' => 'Неизвестная ошибка, попробуйте еще раз.']);
                    break;
            }
        } else {
            if (random_int(0, 1)) {
                $response = ['success' => true, 'message' => 'Ничья!', 'opponent' => $choosen, 'bonus' => $user->getPoints()];
            } else {
                $user->setPoints($user->getPoints() - 100);

                switch ($choosen) {
                    case 'Камень':
                        $response = ['success' => true, 'message' => 'К сожалению, вы проиграли :(', 'opponent' => 'Бумага', 'bonus' => $user->getPoints()];
                        break;
    
                    case 'Ножницы':
                        $response = ['success' => true, 'message' => 'К сожалению, вы проиграли :(', 'opponent' => 'Камень', 'bonus' => $user->getPoints()];
                        break;
    
                    case 'Бумага':
                        $response = ['success' => true, 'message' => 'К сожалению, вы проиграли :(', 'opponent' => 'Ножницы', 'bonus' => $user->getPoints()];
                        break;
                    
                    default:
                        return $this->json(['success' => false, 'message' => 'Неизвестная ошибка, попробуйте еще раз.']);
                        break;
                }
            }
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);

        $em->flush();

        sleep(random_int(1, 2));
        return $this->json($response);
    }
}
