<?php



namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {


        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $this->getUsersList(),
        ]);
    }

    public function adminDashboard()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // or add an optional message - seen by developers
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
    }
    public function getUsersList() : Array
    {
        $em = $this->getDoctrine()->getManager();

        $RAW_QUERY = 'SELECT id,email,roles FROM `user`;';

        $statement = $em->getConnection()->prepare($RAW_QUERY);


        $result = $statement->execute()->fetchAllAssociative();

        return $result;
    }
}
