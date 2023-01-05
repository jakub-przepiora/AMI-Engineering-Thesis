<?php

namespace App\Controller;


use App\Entity\ColumnFromTable;
use App\Entity\Tasks;
use App\Entity\TaskTables;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;



class ApiController extends AbstractController
{
    /**
     * @Route("/api/tables", name="table_index", methods={"POST"})
     */
    public function index(UserRepository $repository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(!$this->checkCredentials($data['user_id'], $data['token'], $repository)) {

            return $this->json(["status"=>"You don't have permission"]);
        }
        $tabs = $this->getDoctrine()
            ->getRepository(TaskTables::class)
            ->findBy(["id_owner"=>$data['user_id']]);

        $tables = [];

        foreach ($tabs as $tab) {
            $tables[] = [
                'id' => $tab->getId(),
                'tab_name' => $tab->getTabName(),
                'description' => $tab->getDescription(),
                'tab_owner' => $tab->getIdOwner()
            ];
        }

        return $this->json($tables);
    }
    /**
     * @Route("/api/teamtables", name="table_team_index", methods={"POST"})
     */
    public function teamTables(UserRepository $repository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);



        if(!$this->checkCredentials($data['user_id'], $data['token'], $repository)) {

            return $this->json(["status"=>"You don't have permission"]);
        }

        $tables = [];
        $user = $repository->find($data["user_id"]);
        $tables_ids = $user->getTableProject();

        if(count($tables_ids) <= 0) {
            return $this->json(["status"=>"You don't have teammate tables"]);
        }

        foreach ($tables_ids as $table_id){
            $tabs = $this->getDoctrine()
                ->getRepository(TaskTables::class)
                ->findBy(["id"=>$table_id]);



            foreach ($tabs as $tab) {
                $tables[] = [
                    'id' => $tab->getId(),
                    'tab_name' => $tab->getTabName(),
                    'description' => $tab->getDescription(),
                    'tab_owner' => $tab->getIdOwner()
                ];
            }
        }


        return $this->json($tables);
    }

    /**
     * @Route("/api/table/add", name="table_new", methods={"POST"})
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


    private function getTasksToCol(int $id_column){

        $tasksFromDB = $this->getDoctrine()
            ->getRepository(Tasks::class)
            ->findBy(["id_column"=>$id_column]);

        $tasks = [];

        foreach ($tasksFromDB as $tab) {
            $tasks[] = [
                'id' => $tab->getId(),
                'title' => $tab->getTaskName(),
                'creator' => $tab->getIdCreator(),
                'create_data' => $tab->getCreateData(),
                'content' => $tab->getTaskDesc(),
                "comments" =>[],
            ];
        }
        return $tasks;
    }
    private function getTasksToColString(int $id_column){

        $tasksFromDB = $this->getDoctrine()
            ->getRepository(Tasks::class)
            ->findBy(["id_column"=>$id_column]);

        $tasks = "";

        foreach ($tasksFromDB as $tab) {
            $tasks .= "{'id':'".$tab->getId()."','content':'".$tab->getTaskDesc()."'},";
//                'id' => $tab->getId(),
//                'title' => $tab->getTaskName(),
//                'creator' => $tab->getIdCreator(),
//                'create_data' => $tab->getCreateData(),
//                'content' => $tab->getTaskDesc(),
//                "comments" =>[],
//            ];
        }

        return rtrim($tasks,",");
    }

    /**
     * @Route("/api/table/{id}", name="table_show", methods={"POST"})
     */
    public function showTab(int $id, UserRepository $repository, Request $request): JsonResponse
    {

        $data = json_decode($request->getContent(), true);
        if(!$this->checkCredentials($data['user_id'], $data['token'], $repository)) {

            return $this->json(["status"=>"You don't have permission"]);
        }

        $table = $this->getDoctrine()
            ->getRepository(TaskTables::class)
            ->find($id);
        if (!$table) {

            return $this->json('No project found for id' . $id, 404);
        }

        $columnsById = $this->getDoctrine()
            ->getRepository(ColumnFromTable::class)
            ->findBy(["id_table"=>$id]);

        $columns = "{";

//        foreach ($columnsById as $column) {
//            $columns[] = [
//                $column->getColumnName() =>[
//                    'id' => $column->getId(),
//                    'name' => $column->getColumnName(),
//                    'items'=> $this->getTasksToCol($column->getId())
//                ]
//
//            ];
//        }
        foreach ($columnsById as $column) {
            $columns .=
                "'".$column->getColumnName()."':{'id':'".$column->getId()."','name':'".$column->getColumnName()."','items':[".$this->getTasksToColString($column->getId())."]},";

        }
        $columns = rtrim($columns, ",");
        $columns .= "}";
//        $response = str_replace("'", '"', $columns);
        return $this->json($columns);
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

    /**
     *
     *      TASKS METHOD
     *
     */





    /**
     * @Route("/api/table/{id}/task/add", name="table_task_add", methods={"POST"})
     */
    public function taskAdd(int $id, UserRepository $repository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(!$this->checkCredentials($data['user_id'], $data['token'], $repository)) {

            return $this->json(["status"=>"You don't have permission"]);
        }

        $new_task = new Tasks();
        $new_task->setIdCreator($data['user_id']);
        $new_task->setIdTable($id);
        $date = new DateTime();

        $new_task->setCreateData($date->setTimestamp(time()));
        $new_task->setTaskName($data['title']);
        $new_task->setTaskDesc($data['description']);
        $new_task->setIdColumn($data['column_id']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($new_task);
        $entityManager->flush();



        return $this->json([
            'status'=> "added",
            'id'=> $new_task->getId()
        ]);
    }

    /**
     * @Route("/api/table/{id}/tasks", name="table_tasks_get_all", methods={"POST"})
     */
    public function tasksGetAll(int $id, UserRepository $repository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(!$this->checkCredentials($data['user_id'], $data['token'], $repository)) {

            return $this->json(["status"=>"You don't have permission"]);
        }


        $tasksFromDB = $this->getDoctrine()
            ->getRepository(Tasks::class)
            ->findBy(["id_table"=>$id]);

        $tasks = [];

        foreach ($tasksFromDB as $tab) {
            $tasks[] = [
                'id' => $tab->getId(),
                'creator' => $tab->getIdCreator(),
                'create_data' => $tab->getCreateData(),
                'task_title' => $tab->getTaskName(),
                'Description' => $tab->getTaskDesc()
            ];
        }


        return $this->json($tasks);

    }
    /**
     *
     *      Columns METHODS
     *
     */

    /**
     * @Route("/api/table/{id}/column/add", name="column_new", methods={"POST"})
     */
    public function addColumn(int $id, UserRepository $repository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);



        if(!$this->checkCredentials($data['user_id'], $data['token'], $repository)) {
            return $this->json(["status" => "You don't have permission"]);
        }

        $columnNew = new ColumnFromTable();

        $columnNew->setColumnName($data["colname"]);
        $columnNew->setIdTable($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($columnNew);
        $entityManager->flush();

        return $this->json([
            "status"=>'Created column successfully with id',

        ]);
    }
    /**
     * @Route("/api/table/{id}/columns", name="table_columns_get_all", methods={"POST"})
     */
    public function columnGetAll(int $id, UserRepository $repository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(!$this->checkCredentials($data['user_id'], $data['token'], $repository)) {

            return $this->json(["status"=>"You don't have permission"]);
        }


        $columnsById = $this->getDoctrine()
            ->getRepository(Tasks::class)
            ->findBy(["id_table"=>$id]);

        $columns = [];

        foreach ($columnsById as $column) {
            $columns[] = [
                'id' => $column->getId(),
                'idtable' => $column->getIdTable(),
                'columnname' => $column->getColumnName(),

            ];
        }


        return $this->json($columns);

    }
    /**
     *
     *      Public METHODS
     *
     */


    public function checkCredentials($user_id, $user_token, UserRepository $repository) {
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
