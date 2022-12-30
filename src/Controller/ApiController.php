<?php

namespace App\Controller;

use App\Entity\TaskTables;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;



class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="table_index", methods={"GET"})
     */
    public function index(): Response
    {
        $tabs = $this->getDoctrine()
            ->getRepository(TaskTables::class)
            ->findAll();

        $data = [];

        foreach ($tabs as $tab) {
            $data[] = [
                'id' => $tab->getId(),
                'tab_name' => $tab->getTabName(),
                'description' => $tab->getDescription(),
            ];
        }
//        var_dump($data);
        $data = ["kekw"=>1];
        return $this->json($data);
    }

    /**
     * @Route("/api", name="table_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        $taskTables = new TaskTables();

        $taskTables->setTabName($request->request->get('name'));
        $taskTables->setDescription($request->request->get('description'));
        $taskTables->setIdOwner($request->request->get('owner'));
        $taskTables->setTabMode($request->request->get('tabmode'));

        $entityManager->persist($taskTables);
        $entityManager->flush();

        return $this->json('Created new table successfully with id ' . $taskTables -> getId());
    }

    /**
     * @Route("/table/{id}", name="table_show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $project = $this->getDoctrine()
            ->getRepository(Project::class)
            ->find($id);

        if (!$project) {

            return $this->json('No project found for id' . $id, 404);
        }

        $data =  [
            'id' => $project->getId(),
            'name' => $project->getName(),
            'description' => $project->getDescription(),
        ];

        return $this->json($data);
    }

    /**
     * @Route("/table/{id}", name="table_edit", methods={"PUT"})
     */
    public function edit(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $project = $entityManager->getRepository(Project::class)->find($id);

        if (!$project) {
            return $this->json('No project found for id' . $id, 404);
        }

        $project->setName($request->request->get('name'));
        $project->setDescription($request->request->get('description'));
        $entityManager->flush();

        $data =  [
            'id' => $project->getId(),
            'name' => $project->getName(),
            'description' => $project->getDescription(),
        ];

        return $this->json($data);
    }

    /**
     * @Route("/table/{id}", name="table_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $project = $entityManager->getRepository(Project::class)->find($id);

        if (!$project) {
            return $this->json('No project found for id' . $id, 404);
        }

        $entityManager->remove($project);
        $entityManager->flush();

        return $this->json('Deleted a project successfully with id ' . $id);
    }
}
