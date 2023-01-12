<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Entity\TaskTables;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssignUserController extends AbstractController
{
    /**
     * @Route("/task/{id}/user/assign", name="task_assign", methods={"POST"})
     */
    public function assignUser(UserRepository $repository, Request $request, int $id): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        // check credentials
        if(!ApiController::checkCredentials($data['user_id'], $data['token'], $repository)) {

            return $this->json(["status"=>"You don't have permission"]);
        }

        // check owner
        if(!$this->checkOwnerTable($data['table_id'], $data['user_id'], $repository)) {

            return $this->json(["status"=>"You aren't owner this table"]);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Tasks::class)->find($id);

        if(!$task) return $this->json(["state"=>"User doesn't exist"]);

        $task->setAssignUserId($data["id_assign"]);
        $entityManager->persist($task);
        $entityManager->flush();

        return $this->json([
            "status"=>'Assigned user',

        ]);



    }
    /**
     * @Route("/task/{id}/user", name="task_get_assign", methods={"POST"})
     */
    public function getAssignedUser(UserRepository $repository, Request $request, int $id): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        // check credentials
        if(!ApiController::checkCredentials($data['user_id'], $data['token'], $repository)) {

            return $this->json(["status"=>"You don't have permissions"]);
        }


        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Tasks::class)->find($id);

        if(!$task) return $this->json(["state"=>"User doesn't exist"]);

        $userId = $task->getAssignUserId();
        if(!$userId) return $this->json([
            "status"=>"Empty",

        ]);
        $userEmail = $entityManager->getRepository(user::class)->find($userId);

        return $this->json([
            "email"=>$userEmail->getEmail(),

        ]);



    }
    public function checkOwnerTable($currentTabId, $user_id, UserRepository $repository) {
        $tabs = $this->getDoctrine()
            ->getRepository(TaskTables::class)
            ->find($currentTabId);


        if($tabs) {
            if(intval($user_id) === $tabs->getIdOwner()){
                return true;
            }
            return false;
        } else {
            return false;
        }
    }
}
