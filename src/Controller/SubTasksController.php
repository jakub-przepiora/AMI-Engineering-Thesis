<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubTasksController extends AbstractController
{
    /**
     * @Route("/sub/tasks", name="app_sub_tasks")
     */
    public function index(): Response
    {
        return $this->render('sub_tasks/index.html.twig', [
            'controller_name' => 'SubTasksController',
        ]);
    }
}
