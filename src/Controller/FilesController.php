<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Files;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FilesController extends AbstractController
{
    /**
     * @Route("/file/task/{id}", name="file_comment_get_all", methods={"POST"})
     */
    public function index(int $id, UserRepository $repository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(!ApiController::checkCredentials($data['user_id'], $data['token'], $repository)) {

            return $this->json(["status"=>"You don't have permission"]);
        }


        $fileById = $this->getDoctrine()
            ->getRepository(Files::class)
            ->findBy(["task_id"=>$id]);

        $file = [];

        foreach ($fileById as $filey) {
            $file[] = [
                'id' => $filey->getId(),
                'id_task' => $filey->getTaskId(),
                'id_user' => $filey->getUserId(),
                'date' => $filey->getAddData(),
                'filename' => $filey->getName(),
                'url' => "http://localhost:8000/uploads/".$filey->getName(),

            ];
        }


        return $this->json($file);

    }


    /**
     * @Route("/file/task/{id}/add", name="file_comment_new", methods={"POST"})
     */
    public function addFile(int $id, UserRepository $repository, Request $request, FileUploader $fileUploader): JsonResponse
    {

        if(!ApiController::checkCredentials($request->get('user_id'), $request->get('token'), $repository)) {
            return $this->json(["status" => "You don't have permission"]);
        }

        $fileNew = new Files();

        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }
        $fileNameNew = $fileUploader->upload($uploadedFile);
        $fileNew->setName(str_replace(" ", "_",$fileNameNew));
        $fileNew->setUserId($request->get('user_id'));
        $date = new DateTime();

        $fileNew->setAddData($date->setTimestamp(time()));

        $fileNew->setLinkToFile($fileNameNew);

        $fileNew->setTaskId(intval($request->get('task_id')));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($fileNew);
        $entityManager->flush();

        return $this->json([
            "status"=>'Added file value added. '.$request->files->get('file'),
            "url"=> $fileUploader->getUrl($fileNameNew)
        ]);
    }

    /**
     * @Route("/file/task/{id}/remove", name="file_comment_remove", methods={"POST"})
     */
    public function removeFile(int $id, UserRepository $repository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);



        if(!ApiController::checkCredentials($data['user_id'], $data['token'], $repository)) {
            return $this->json(["status" => "You don't have permission"]);
        }



        $entityManager = $this->getDoctrine()->getManager();
        $file = $entityManager->getRepository(Files::class)->find($data["file_id"]);

        if (!$file) {
            return $this->json('No file found for id: ' . $data["file_id"], 404);
        }
        if($file->getUserId() != $data['user_id'])
            return $this->json([
                "status"=>"File it's not yours",

            ]);
        $entityManager->remove($file);
        $entityManager->flush();

        return $this->json([
            "status"=>'Removed file successfully',

        ]);
    }

    /**
     *
     *      Private special METHODS
     *
     */
    private function uploadFile(Request $request) {
        $uploadedFile = $request->files->get('file');

        if (!$uploadedFile instanceof UploadedFile) {
            return false;
        }

        // Move the uploaded file to a permanent location
        $uploadedFile->move('/upload/', $uploadedFile->getClientOriginalName());
        return true;
    }
}
