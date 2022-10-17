<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TimersController extends AbstractController
{
    /**
     * @Route("/timers", name="app_timers")
     */
    public function index(): Response
    {
        return $this->render('timers/index.html.twig', [
            'controller_name' => 'TimersController',
        ]);
    }
}
