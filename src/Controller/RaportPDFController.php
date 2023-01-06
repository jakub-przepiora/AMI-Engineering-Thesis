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

        $this->generatePdfAction(["type"=>"user", "data_table"=>[]]);
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

        $pdf->SetFont('helvetica', 'B', 17);

        $pdf->Write(20, 'Raport', '', 0, 'L', true, 0, false, true, 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(30, 10, 'Task', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Start timer', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Timer Value', 1, 0, 'C');
        $pdf->Cell(30, 10, 'User', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Task Status', 1, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('helvetica', '', 15);
        foreach ($contentPDF["data_table"] as $row) {
            $pdf->Cell(30, 10, $row["task"], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['start_timer'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['value'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['user'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['task_status'], 1, 0, 'C');
            $pdf->Ln();
        }

        // Output the PDF to the browser
        $pdf->Output($title.'.pdf', 'I');
    }
}
