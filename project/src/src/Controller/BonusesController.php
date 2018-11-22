<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BonusesController extends AbstractController
{
    /**
     * @Route("/bonuses", name="bonuses")
     */
    public function bonuses()
    {
        return $this->render('bonuses/bonuses.html.twig', [
            'points' => $this->getUser()->getPoints()
        ]);
    }
}
