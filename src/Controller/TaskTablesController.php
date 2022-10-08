<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskTablesController extends AbstractController
{
    /**
     * @Route("/task/tables", name="app_task_tables")
     */
    public function index(): Response
    {
        return $this->render('task_tables/index.html.twig', [
            'controller_name' => 'TaskTablesController',
        ]);
    }
}
