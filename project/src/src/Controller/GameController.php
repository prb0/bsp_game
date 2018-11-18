<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class GameController extends AbstractController
{
    /**
     * @Route("/game", name="game")
     */
    public function game()
    {
        return $this->render('game/index.html.twig');
    }

    /**
     * @Route("/game/findenemy", name="findenemy")
     */
    public function findenemy()
    {
        sleep(random_int(3, 10));
        return $this->json(['success' => true]);
    }

    /**
     * @Route("/game/execute", name="gameexecute")
     */
    public function execute(Request $request)
    {
        $choosen = $request->request->get('choosen');

        if (random_int(0, 9) > 2) {
            switch ($choosen) {
                case 'Камень':
                    $response = ['success' => true, 'message' => 'Поздравляем, вы победили!', 'opponent' => 'Ножницы'];
                    break;

                case 'Ножницы':
                    $response = ['success' => true, 'message' => 'Поздравляем, вы победили!', 'opponent' => 'Бумага'];
                    break;

                case 'Бумага':
                    $response = ['success' => true, 'message' => 'Поздравляем, вы победили!', 'opponent' => 'Камень'];
                    break;
                
                default:
                    return $this->json(['success' => false, 'message' => 'Неизвестная ошибка, попробуйте еще раз.']);
                    break;
            }
        } else {
            if (random_int(0, 1)) {
                $response = ['success' => true, 'message' => 'Ничья!', 'opponent' => $choosen];
            } else {
                switch ($choosen) {
                    case 'Камень':
                        $response = ['success' => true, 'message' => 'К сожалению, вы проиграли :(', 'opponent' => 'Бумага'];
                        break;
    
                    case 'Ножницы':
                        $response = ['success' => true, 'message' => 'К сожалению, вы проиграли :(', 'opponent' => 'Камень'];
                        break;
    
                    case 'Бумага':
                        $response = ['success' => true, 'message' => 'К сожалению, вы проиграли :(', 'opponent' => 'Ножницы'];
                        break;
                    
                    default:
                        return $this->json(['success' => false, 'message' => 'Неизвестная ошибка, попробуйте еще раз.']);
                        break;
                }
            }
        }

        sleep(random_int(3, 10));
        return $this->json($response);
    }
}
