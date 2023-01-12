<?php

namespace App\Controller;

use App\Entity\ColumnFromTable;
use App\Entity\Files;
use App\Entity\Tasks;
use App\Entity\Timers;
use App\Entity\User;
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
     * @Route("/raport/table/{id}", name="raport_id_table", methods={"GET"})
     */
    public function index(int $id, UserRepository $repository, Request $request)
    {


        $tasksFromTable = $this->getDoctrine()
            ->getRepository(Tasks::class)
            ->findBy(["id_table"=>$id]);

        $rows = [];

        foreach ($tasksFromTable as $task) {

           $timers = $this->getDoctrine()
                ->getRepository(Timers::class)
                ->findBy(["id_task"=>$task->getId()]);
            foreach ($timers as $timer) {
                $date = $timer->getDataStart();

                $rows[] = [
                    'task' => $task->getId(),
                    'start_timer' => $date->format('Y-m-d H:i:s'),
                    'value' => $timer->getValue(),
                    'user' => $this->getUserNameById($timer->getIdUser()),
                    'task_status' => $this->getColumnNameById($task->getIdColumn())


                ];
            }

        }
//        var_dump($rows);
        $this->generatePdfAction(["type"=>"user", "data_table"=>$rows]);

//        return $this->json($rows);

    }
    public function getColumnNameById($colId){
        $col = $this->getDoctrine()
            ->getRepository(ColumnFromTable::class)
            ->find($colId);
        return $col->getColumnName();
    }
    public function getUserNameById($userId){
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);
        return $user->getEmail();
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

        $pdf->Write(20, 'Raport '.$date->format('Y-m-d-H-i-s'), '', 0, 'L', true, 0, false, true, 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(20, 10, 'Task', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Start timer', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Timer Value', 1, 0, 'C');
        $pdf->Cell(50, 10, 'User E-mail', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Task Status', 1, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('helvetica', '', 10);
        foreach ($contentPDF["data_table"] as $row) {
            $pdf->Cell(20, 10, $row["task"], 1, 0, 'C');
            $pdf->Cell(40, 10, $row['start_timer'], 1, 0, 'C');
            $pdf->Cell(30, 10, $this->msToTime(intval($row['value'])), 1, 0, 'C');
            $pdf->Cell(50, 10, $row['user'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['task_status'], 1, 0, 'C');
            $pdf->Ln();
        }

        // Output the PDF to the browser
        $pdf->Output($title.'.pdf', 'I');
    }
    function msToTime($duration) {
        // Get the minutes
        $minutes = floor($duration / (1000 * 60));

        // Get the seconds
        $seconds = floor(($duration % (1000 * 60)) / 1000);

        // Get the milliseconds
        $milliseconds = $duration % 1000;

        // Pad the minutes and seconds with leading zeros if necessary
        $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
        $seconds = str_pad($seconds, 2, '0', STR_PAD_LEFT);
        $milliseconds = str_pad($milliseconds, 3, '0', STR_PAD_LEFT);

        // Return the formatted time string
        return "$minutes:$seconds:$milliseconds";
    }
}
