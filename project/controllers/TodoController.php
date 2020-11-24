<?php

	namespace Project\Controllers;
	use \Core\Controller;
    use \Project\Models\User;
    use \Project\Models\Post;

	class TodoController extends Controller
	{

        public $notesOnPage = 3;

        //INDEX PAGE
		public function index() {

            $this->title = 'Задачник';

            $message = '';
            if(isset($_POST['userName']) AND isset($_POST['email']) AND isset($_POST['task'])){
                $userName = filter_var(trim($_POST['userName']), FILTER_SANITIZE_STRING);
                $userEmail = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
                $userTask = filter_var(trim($_POST['task']), FILTER_SANITIZE_STRING);
                if ($userName === false OR $userEmail === false OR $userTask === false){

                    header("location: ".$_SERVER['REQUEST_URI']);
                    exit;

                } else {
                $addTask = new Post;
                $addTask->add(['userName' => $userName, 'userEmail' => $userEmail, 'userTask' => $userTask, 'taskState' => '0']);
                $message = "Post added successfully!";
                }
            }

            $sort = 'name';

            if(isset($_GET['sort'])){
                if($_GET['sort'] == 'email'){
                    $sort = 'email';
                } else if($_GET['sort'] == 'state'){
                    $sort = 'state';
                }
            }

            $order = 'ASC';
            if(isset($_GET['order'])){
                if($_GET['order'] == 'ASC'){
                    $order = 'ASC';
                } else if($_GET['order'] == 'DESC'){
                    $order = 'DESC';
                }
            }

            $countQuery = new Post;
            $count = $countQuery->count()['count'];
            $pagesCount = ceil($count/$this->notesOnPage);

            if(isset($_GET['page'])){
                $page = intval($_GET['page']);
                if($page < 1){
                    $page = 1;
                } else if ($page > $pagesCount){
                    $page = $pagesCount;
                }
            } else {
                $page = 1;
            }

            $postSearch = new Post;
            $from = ($page - 1) * $this->notesOnPage;

            $posts = $postSearch->getAll($sort, $order, $from, $this->notesOnPage);

            $pageReq = isset($_GET['page']) ? "&page=".$_GET['page'] : "";
            $sortReq = isset($_GET['sort']) ? "&sort=".$_GET['sort'] : "";
            $orderReq = isset($_GET['order']) ? "&order=".$_GET['order'] : "";
            $token = $this->isAuth();

            return $this->render('todo/index',
            ['posts' => $posts,
             'pagesCount' => $pagesCount,
             'page' => $page,
             'token' => $token,
             'order' => $order,
             'sort' => $sort,
             'pageReq' => $pageReq,
             'sortReq' => $sortReq,
             'orderReq' => $orderReq,
             'message' => $message,
            ]);
        }

        //IS AUTH
        private function isAuth(){
            if(isset($_SESSION['admin'])){
                return true;
            } else {
                return false;
            }
        }


        //LOGIN
        public function login() {

            $this->title = 'Страница входа';
            $token = $this->isAuth();
			return $this->render('todo/login',['token' => $token]);
        }

        //AUTHORIZATION
        public function auth() {
            if (isset($_POST['login']) AND isset($_POST['password'])){

            $userSearch = new User;

            $login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
            $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
            $password = md5($password."salt12345");

            $user = $userSearch->tryAuth($login, $password);

            if($user == false){
                header("location: login");
                exit;
            }

            $_SESSION['admin'] = true;

            header("location: admin");
            }
        }

        //EXIT
        public function exit(){
            session_destroy();
            header("location: /");
        }

        //ADMIN
        public function admin($token = '') {
            $this->title = 'Администрирование';

            $countQuery = new Post;
            $count = $countQuery->count()['count'];

            $postSearch = new Post;
            $sort = 'name';

            if(isset($_GET['sort'])){
                if($_GET['sort'] == 'email'){
                    $sort = 'email';
                } else if($_GET['sort'] == 'state'){
                    $sort = 'state';
                }
            }

            $order = 'ASC';
            if(isset($_GET['order'])){
                if($_GET['order'] == 'ASC'){
                    $order = 'ASC';
                } else if($_GET['order'] == 'DESC'){
                    $order = 'DESC';
                }
            }

            $pagesCount = ceil($count/$this->notesOnPage);

            if(isset($_GET['page'])){
                $page = intval($_GET['page']);
                if($page < 1){
                    $page = 1;
                } else if ($page > $pagesCount){
                    $page = $pagesCount;
                }
            } else {
                $page = 1;
            }

            $from = ($page - 1) * $this->notesOnPage;
            $posts = $postSearch->getAll($sort,$order, $from, $this->notesOnPage);

            $pageReq = isset($_GET['page']) ? "&page=".$_GET['page'] : "";
            $sortReq = isset($_GET['sort']) ? "&sort=".$_GET['sort'] : "";
            $orderReq = isset($_GET['order']) ? "&order=".$_GET['order'] : "";
            $token = $this->isAuth();

            return $this->render('todo/admin',
            ['posts' => $posts,
            'pagesCount' => $pagesCount,
            'page' => $page,
            'token' => $token,
            'order' => $order,
            'sort' => $sort,
            'pageReq' => $pageReq,
            'sortReq' => $sortReq,
            'orderReq' => $orderReq,
            ]);
        }

        //EDIT POST
        public function edit() {
            $this->title = 'Редактирование';

            if(isset($_GET['id'])){
                $id = $_GET['id'];
            } else {
                header("location: /admin");
            }

            $postSearch = new Post;
            $post = $postSearch->getOne($id);

            $prevTask = $post['task'];
            $token = $this->isAuth();

            if(isset($_POST['id']) AND $token){
                $postUpdate = new Post;
                $id =  $_POST['id'];
                $task =  $_POST['task'];
                $edited = '0';
                if($task != $prevTask){
                    $edited = '1';
                }
                $state =  $_POST['state'];
                $post = $postUpdate->update(['id' => $id, 'task' => $task, 'progress' => $state, 'edited' => $edited]);
                header("location: /admin");
            }


            return $this->render('todo/edit',['post' => $post, 'token' => $token]);
        }

        //DELETE POST
        public function delete() {
            $this->title = 'Удаление';
            if ($this->isAuth()){
                    if(isset($_GET['id'])){
                        $id = $_GET['id'];

                        $postDelete = new Post;
                        $postDelete->delete(['id' => $id]);
                        header("location: /admin");
                    } else {
                        header("location: /admin");
                    }
            } else {
                header("location: /");
            }
        }

	}
