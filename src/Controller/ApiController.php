<?php

namespace App\Controller;


use App\Entity\ColumnFromTable;
use App\Entity\Comments;
use App\Entity\Tasks;
use App\Entity\TaskTables;
use App\Entity\User;
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


        if(!$this->checkCredentials($data['user_id'], $data['token'], $repository)) {

            return $this->json(["status"=>"You don't have permission"]);
        }
        $taskTables = new TaskTables();

        $taskTables->setTabName($data['name']);
        $taskTables->setDescription($data['description']);
        $taskTables->setIdOwner($data['user_id']);
        $taskTables->setTabMode("1");

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
            $tasks .= "{'id':'".$tab->getId()."','content':'".$tab->getTaskDesc()."','title':'".$tab->getTaskName()."'},";
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
     * @Route("/table/{id}/remove", name="table_delete", methods={"POST"})
     */
    public function removeTable(int $id, UserRepository $repository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);



        if(!$this->checkCredentials($data['user_id'], $data['token'], $repository)) {
            return $this->json(["status" => "You don't have permission"]);
        }

        // check owner
        if(!$this->checkOwnerTable($id, $data['user_id'], $data['token'], $repository)) {

            return $this->json(["status"=>"You aren't owner this table"]);
        }


        $entityManager = $this->getDoctrine()->getManager();
        $tab = $entityManager->getRepository(TaskTables::class)->find($id);

        if (!$tab) {
            return $this->json('No table found for id' . $id, 404);
        }

        $entityManager->remove($tab);
        $entityManager->flush();

        return $this->json([
            "status"=>'Removed tab successfully with id '.$id,

        ]);
    }

    private function updatePositionTask($id_task, $id_destination_col){

        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Tasks::class)->find($id_task);
        $task->setIdColumn($id_destination_col);
        $entityManager->persist($task);
        $entityManager->flush();

        return true;
    }

    /**
     * @Route("/table/{id}/update", name="table_update", methods={"POST"})
     */
    public function updateTab(UserRepository $repository, Request $request, int $id): JsonResponse
    {

        $data = json_decode($request->getContent(), true);
        if(!$this->checkCredentials($data['user_id'], $data['token'], $repository)) {

            return $this->json(["status"=>"You don't have permission"]);
        }
        // start array col
        foreach ($data["cols"] as $key => $col ){
            $id_desti = intval($col["id"]);
            foreach ($col["items"] as $task) {
                    $this->updatePositionTask($task["id"],$id_desti);
            }
        }


        return $this->json('Updated table with id: ' . $id);
    }


    /**
     * @Route("/api/table/{id}/user/add", name="table_add_user", methods={"POST"})
     */
    public function addUserToTab(UserRepository $repository, Request $request, int $id): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        // check credentials
        if(!$this->checkCredentials($data['user_id'], $data['token'], $repository)) {

            return $this->json(["status"=>"You don't have permission"]);
        }

        // check owner
        if(!$this->checkOwnerTable($id, $data['user_id'], $data['token'], $repository)) {

            return $this->json(["status"=>"You aren't owner this table"]);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->findBy(["email"=>$data["user_email"]]);
        if(!$user) return $this->json(["state"=>"User doesn't exist"]);
        $fromDbTableProject = $user[0]->getTableProject();


        if(!in_array($id,$fromDbTableProject)){
            $fromDbTableProject[] = $id;
            $user[0]->setTableProject($fromDbTableProject);
            $entityManager->persist($user[0]);
            $entityManager->flush();
            return $this->json(["state"=>'User has been added']);
        }
        else {
            return $this->json(["state"=>"User can't be added"]);
        }


    }
    /**
     * @Route("/api/table/{id}/team", name="table_team", methods={"POST"})
     */
    public function getTeammatesTable(int $id, UserRepository $repository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // check credentials
        if(!$this->checkCredentials($data['user_id'], $data['token'], $repository)) {

            return $this->json(["status"=>"You don't have permission"]);
        }



        $query = $this->getDoctrine()->getManager()
            ->createQuery(
                'SELECT e.id, e.email FROM App\Entity\User e WHERE e.tableProject LIKE :value'
            )->setParameter('value', '%'.$id.'%');

        $users = $query->getResult();

        return $this->json($users);
    }
    /**
     * @Route("/api/table/{id}/user/remove", name="table_remove_user", methods={"POST"})
     */
    public function removeUserToTab(UserRepository $repository, Request $request, int $id): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        // check credentials
        if(!$this->checkCredentials($data['user_id'], $data['token'], $repository)) {

            return $this->json(["status"=>"You don't have permission"]);
        }

        // check owner
        if(!$this->checkOwnerTable($id, $data['user_id'], $data['token'], $repository)) {

            return $this->json(["status"=>"You aren't owner this table"]);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($data["id_del"]);

        if(!$user) return $this->json(["state"=>"I can't find user in DB"]);
        $fromDbTableProject = $user->getTableProject();


        if(in_array($id, $fromDbTableProject)){

            $key = array_search($id, $fromDbTableProject);

            if ($key !== false) {
                unset($fromDbTableProject[$key]);
            }
            $user->setTableProject($fromDbTableProject);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->json(["state"=>'User has been removed']);
        }
        else {
            return $this->json(["state"=>"User doesn't exist in team"]);
        }


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
//        $new_task->setIdColumn($data['column_id']);
        $new_task->setIdColumn($this->getFirstColumnFromTable($id));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($new_task);
        $entityManager->flush();



        return $this->json([
            'status'=> "added",
            'id'=> $new_task->getId()
        ]);
    }

    public function getFirstColumnFromTable($id_tab) {
        $entityManager = $this->getDoctrine()->getManager();
        $cols = $entityManager->getRepository(ColumnFromTable::class)->findBy(["id_table"=>$id_tab]);

        if (!$cols) {
            return false;
        }
        return $cols[0]->getId();
    }

    /**
     * @Route("/api/table/{id}/task/remove", name="task_remove", methods={"POST"})
     */
    public function removeTask(int $id, UserRepository $repository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);



        if(!$this->checkCredentials($data['user_id'], $data['token'], $repository)) {
            return $this->json(["status" => "You don't have permission"]);
        }

        // check owner
        if(!$this->checkOwnerTable($id, $data['user_id'], $data['token'], $repository)) {

            return $this->json(["status"=>"You aren't owner this table"]);
        }


        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Tasks::class)->find($data["task_id"]);

        if (!$task) {
            return $this->json('No project found for id' . $data["task_id"], 404);
        }

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->json([
            "status"=>'Removed task successfully with id '.$data["task_id"],

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
     * @Route("/api/table/{id}/column/remove", name="column_remove", methods={"POST"})
     */
    public function removeColumn(int $id, UserRepository $repository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);



        if(!$this->checkCredentials($data['user_id'], $data['token'], $repository)) {
            return $this->json(["status" => "You don't have permission"]);
        }

        // check owner
        if(!$this->checkOwnerTable($id, $data['user_id'])) {

            return $this->json(["status"=>"You aren't owner this table"]);
        }


        $entityManager = $this->getDoctrine()->getManager();
        $column = $entityManager->getRepository(ColumnFromTable::class)->find($data["col_id"]);

        if (!$column) {
            return $this->json('No column found for id' . $data["col_id"], 404);
        }

        $entityManager->remove($column);
        $entityManager->flush();

        return $this->json([
            "status"=>'Removed column successfully with id '.$data["col_id"],

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
     *      Comments METHODS
     *
     */

    /**
     * @Route("/api/table/{id}/comment/add", name="comment_new", methods={"POST"})
     */
    public function addComment(int $id, UserRepository $repository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);


        if(!$this->checkCredentials($data['user_id'], $data['token'], $repository)) {
            return $this->json(["status" => "You don't have permission"]);
        }

        $commentNew = new Comments();

        $commentNew->setContent(htmlspecialchars($data["content"]));
        $commentNew->setCreatorId($data['user_id']);
        $dateT = new DateTime();

        $commentNew->setCreateData($dateT->setTimestamp(time()));

        $commentNew->setTaskId($data["task_id"]);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($commentNew);
        $entityManager->flush();

        return $this->json([
            "status"=>'Created comment added',

        ]);
    }

    /**
     * @Route("/api/table/{id}/comment/remove", name="comment_remove", methods={"POST"})
     */
    public function removeComment(int $id, UserRepository $repository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);



        if(!$this->checkCredentials($data['user_id'], $data['token'], $repository)) {
            return $this->json(["status" => "You don't have permission"]);
        }

        // check owner
        if(!$this->checkOwnerTable($id, $data['user_id'], $data['token'], $repository)) {

            return $this->json(["status"=>"You aren't owner this table"]);
        }


        $entityManager = $this->getDoctrine()->getManager();
        $comment = $entityManager->getRepository(Comments::class)->find($data["comment_id"]);

        if (!$comment) {
            return $this->json('No comment found for id' . $data["comment_id"], 404);
        }

        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->json([
            "status"=>'Removed comment successfully',

        ]);
    }

    /**
     * @Route("/api/table/{id}/comments", name="comments_get_all", methods={"POST"})
     */
    public function commentsGetAll(int $id, UserRepository $repository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(!$this->checkCredentials($data['user_id'], $data['token'], $repository)) {

            return $this->json(["status"=>"You don't have permission"]);
        }


        $commentsById = $this->getDoctrine()
            ->getRepository(Comments::class)
            ->findBy(["task_id"=>$data["task_id"]]);

        $comments = [];

        foreach ($commentsById as $comment) {
            $comments[] = [
                'id' => $comment->getId(),

                'text' => $comment->getContent(),
                'creator' => $this->getUserNameById($comment->getCreatorId()),
                'date' => $comment->getCreateData()->format('Y-m-d H:i:s'),

            ];
        }


        return $this->json($comments);

    }

    public function getUserNameById($userId){
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);
        return $user->getEmail();
    }
    /**
     *
     *      Public METHODS
     *
     */
    /**
     * @Route("/api/table/{currentTabId}/owner/check", name="check_owner_table", methods={"POST"})
     */
    public function checkOwnerTableAPI(int $currentTabId,  UserRepository $repository,Request $request) : JsonResponse {

        $data = json_decode($request->getContent(), true);
        if(!$data["user_id"]){
            return $this->json(["status"=>false]);
        }
        $tabs = $this->getDoctrine()
            ->getRepository(TaskTables::class)
            ->findBy(["id_owner"=>$data["user_id"]]);
        if ($tabs) {
            foreach ($tabs as $tab){
                $idOwnTable = $tab->getId();
                if($idOwnTable === $currentTabId){
                    return $this->json(["status"=>true]);
                }
            }
            return $this->json(["status"=>false]);
        } else {
            return $this->json(["status"=>false]);
        }
    }

    public function checkOwnerTable(int $currentTabId, $user_id) {
        $tabs = $this->getDoctrine()
            ->getRepository(TaskTables::class)
            ->findBy(["id_owner"=>$user_id]);
        if ($tabs) {
            foreach ($tabs as $tab){
                $idOwnTable = $tab->getId();
                if($idOwnTable === $currentTabId){
                    return true;
                }
            }
            return false;
        } else {
            return false;
        }
    }


    static public function checkCredentials( $user_id, $user_token, UserRepository $repository) {
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
