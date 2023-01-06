<?php

namespace App\Controller;

use App\Entity\Files;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use setasign\Fpdi\Tcpdf\Fpdi;

class RaportPDFController extends AbstractController
{
    /**
     * @Route("/raport/user/{id}", name="raport_id_user", methods={"GET"})
     */
    public function index(int $id, UserRepository $repository, Request $request): JsonResponse
    {
//        $data = json_decode($request->getContent(), true);

//        if(!ApiController::checkCredentials($data['user_id'], $data['token'], $repository)) {
//
//            return $this->json(["status"=>"You don't have permission"]);
//        }

        $this->generatePdfAction(["type"=>"user"]);
//        $fileById = $this->getDoctrine()
//            ->getRepository(Files::class)
//            ->findBy(["task_id"=>$id]);
//
//        $file = [];
//
//        foreach ($fileById as $filey) {
//            $file[] = [
//                'id' => $filey->getId(),
//                'id_task' => $filey->getTaskId(),
//                'id_user' => $filey->getUserId(),
//                'date' => $filey->getAddData(),
//                'filename' => $filey->getName(),
//
//            ];
//        }
//
//
//        return $this->json($file);

    }


    public function generatePdfAction(array $contentPDF)
    {
        // Create a new PDF document
        $pdf = new Fpdi();
        $date = new DateTime();
        // Set the document title and author
        $title = "raport-".$contentPDF["type"]."-".$date->format('Y-m-d-H-i-s');
        $pdf->SetTitle($title);
        $pdf->SetAuthor('Ami Task Menager');

        // Add a page
        $pdf->AddPage();

        // Set the font and write some text
        $pdf->SetFont('helvetica', '', 14);
        $pdf->Write(0, 'Hello world', '', 0, 'L', true, 0, false, false, 0);

        // Output the PDF to the browser
        $pdf->Output($title.'.pdf', 'I');
    }
}
