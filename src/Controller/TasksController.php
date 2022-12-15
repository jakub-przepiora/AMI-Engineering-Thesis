<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;



class TasksController extends AbstractController
{
    /**
     * @Route("/tasks", name="app_tasks")
     */
    public function index(): Response
    {
        return $this->render('tasks/index.html.twig', [
            'controller_name' => 'TasksController',
        ]);
    }


    private function fetchID() {
        $randomNumber = mt_rand(1, 20);

        return $randomNumber;
    }

    /**
     * @Route("/tasks/getallexample", name="app_getallexample")

     */
    public function getallexample(): Response
    {
        $example = [
            "pending" =>[
            "title" =>"pending",
            "items" =>[
                [
                    "id" => $this->fetchID(),
                    "title" =>"Send the Figma file to Dima",
                    "comments" =>[],
                ],
            ],
	    ],
        "ongoing" =>[
            "title" =>"ongoing",
		    "items" =>[
                [
                    "id" => $this->fetchID(),
                    "title" =>"Review GitHub issues",
                    "comments" =>[
                        [
                            "name" =>"David",
                            "text" =>"Ensure you review before merging",
                            "id" => $this->fetchID(),
                        ],
                    ],
                ],
		    ],
	    ],
        "completed" => [
            "title" =>"completed",
            "items" => [
                [
                    "id" => $this->fetchID(),
                    "title" =>"Create technical contents",
                    "comments" =>[
                        [
                            "name" =>"Dima",
                            "text" =>"Make sure you check the requirements",
                            "id" => $this->fetchID(),
                        ],
                    ],
                ],
            ],
	    ],
    ];

        $respons = new Response(json_encode($example));

        return $respons;
    }
}
