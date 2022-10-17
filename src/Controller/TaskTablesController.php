<?php

namespace App\Controller;

use App\Entity\TaskTables;
use App\Form\TaskTableRemoveType;
use App\Form\TaskTableType;
use App\Repository\TaskTablesRepository;
use phpDocumentor\Reflection\Types\Boolean;
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
    /**
     * @Route("/task/tables/create", name="app_task_tables")
     */
    public function create(): Response  // @TODO: Change Response to boolean
    {
        $tableForTasks = new TaskTables();
        $form = $this->createForm(TaskTableType::class, $tableForTasks);

        $login = true; // @TODO: change this $login to function checked login user

        if($login === true) {
            if ($form->isSubmitted() && $form->isValid()) {

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($tableForTasks);
                $entityManager->flush();



                return $this->redirectToRoute('app_admin');
            }
        }
        return $this->render(
            'task_tables/create.html.twig',
            array('form' => $form->createView())
        );
//        return false;
    }

    /**
     * @Route("/task/tables/remove", name="app_task_tables")
     */
    public function remove(): Response
    {
        $tableForTasks = new TaskTables();
        $form = $this->createForm(TaskTableRemoveType::class,$tableForTasks);
        $login = true; // @TODO: change this $login to function checked login user
        if($login === true) {

            if ($form->isSubmitted() && $form->isValid()) {

                TaskTablesRepository::remove($tableForTasks); // @TODO check if it works :)

            }
        }
        return $this->redirectToRoute('app_admin');
    }
}
