<?php

namespace App\Controller;

use App\Entity\TaskTables;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;



class ApiController extends AbstractController
{
    /**
     * @Route("/api/tables", name="table_index", methods={"GET"})
     */
    public function index(): JsonResponse
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

        return $this->json($data);
    }

    /**
     * @Route("/api/add", name="table_new", methods={"POST"})
     */
    public function new(UserRepository $repository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $entityManager = $this->getDoctrine()->getManager();


        if(!$this->checkCredentials($data['owner'], $data['token'], $repository)) {

            return $this->json(["status"=>"You don't have permission"]);
        }
        $taskTables = new TaskTables();

        $taskTables->setTabName($data['name']);
        $taskTables->setDescription($data['description']);
        $taskTables->setIdOwner($data['owner']);
        $taskTables->setTabMode($data['tabmode']);

        $entityManager->persist($taskTables);
        $entityManager->flush();

        return $this->json([
            "status"=>'Created new table successfully with id ' . $taskTables -> getId(),
            "id_table"=> $taskTables -> getId()
        ]);
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
    function checkCredentials($user_id, $user_token, UserRepository $repository) {
        $user = $repository->find($user_id);


        if ($user) {
            $token = $user->getTokenJWT();
            if($token === $user_token){
                return true;
            }
            else
                return false;
        } else {
            return false;
        }
    }
}