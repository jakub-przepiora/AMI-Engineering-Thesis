<?php

namespace App\Controller;

use App\Entity\Timers;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TimersController extends AbstractController
{
    /**
     * @Route("/timer/task/{id}", name="timer_get_all", methods={"POST"})
     */
    public function index(int $id, UserRepository $repository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(!ApiController::checkCredentials($data['user_id'], $data['token'], $repository)) {

            return $this->json(["status"=>"You don't have permission"]);
        }


        $timerById = $this->getDoctrine()
            ->getRepository(Timers::class)
            ->findBy(["id_task"=>$id]);

        $timer = [];

        foreach ($timerById as $timery) {
            $timer[] = [
                'id' => $timery->getId(),
                'id_task' => $timery->getIdTask(),
                'id_user' => $timery->getIdUser(),
                'date' => $timery->getDataStart(),
                'value' => $timery->getValue(),

            ];
        }


        return $this->json($timer);

    }


    /**
     * @Route("/timer/task/{id}/add", name="timer_new", methods={"POST"})
     */
    public function addTimer(int $id, UserRepository $repository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);


        if(!ApiController::checkCredentials($data['user_id'], $data['token'], $repository)) {
            return $this->json(["status" => "You don't have permission"]);
        }

        $timerNew = new Timers();

        $timerNew->setIdTask($id);
        $timerNew->setIdUser($data['user_id']);
        $date = new DateTime();

        $timerNew->setDataStart($date->setTimestamp(time()));

        $timerNew->setValue($data["time_value"]);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($timerNew);
        $entityManager->flush();

        return $this->json([
            "status"=>'Added timer value added',

        ]);
    }

    /**
     * @Route("/timer/{id}/remove", name="timer_remove", methods={"POST"})
     */
    public function removeTimer(int $id, UserRepository $repository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);



        if(!ApiController::checkCredentials($data['user_id'], $data['token'], $repository)) {
            return $this->json(["status" => "You don't have permission"]);
        }



        $entityManager = $this->getDoctrine()->getManager();
        $timer = $entityManager->getRepository(Timers::class)->find($id);

        if (!$timer) {
            return $this->json('No timer found for id' . $id, 404);
        }

        $entityManager->remove($timer);
        $entityManager->flush();

        return $this->json([
            "status"=>'Removed timer successfully',

        ]);
    }

    /**
     * @Route("/timer/user/{id}", name="timer_user_get_all", methods={"POST"})
     */
    public function getTimersUser(int $id, UserRepository $repository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(!ApiController::checkCredentials($data['user_id'], $data['token'], $repository)) {

            return $this->json(["status"=>"You don't have permission"]);
        }


        $timerById = $this->getDoctrine()
            ->getRepository(Timers::class)
            ->findBy(["id_user"=>$id]);

        $timer = [];

        foreach ($timerById as $timery) {
            $timer[] = [
                'id' => $timery->getId(),
                'id_task' => $timery->getIdTask(),
                'id_user' => $timery->getIdUser(),
                'date' => $timery->getDataStart(),
                'value' => $timery->getValue(),

            ];
        }


        return $this->json($timer);

    }



}
