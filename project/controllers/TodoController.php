<?php

	namespace Project\Controllers;
	use \Core\Controller;
    use \Project\Models\User;
    use \Project\Models\Post;

	class TodoController extends Controller
	{

        public $notesOnPage = 3;


        private function filter($filterArr, $filterVal){
                  
            if(in_array($filterVal, $filterArr)){
                $filteredVal = $filterVal;
            }
            return $filteredVal;    
        }

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


            $sortArr = ['name', 'email', 'state'];
            $sort = 'name';
            if(isset($_GET['sort'])){
                $sort = $this->filter($sortArr, $_GET['sort']);
            }

            $orderArr = ['ASC', 'DESC'];
            $order = 'ASC';
            if(isset($_GET['order'])){
                $order = $this->filter($orderArr, $_GET['order']);
            }
            
            $countPostsQuery = new Post;
            $countPosts = $countPostsQuery->count();
            $pagesCount = ceil($countPosts/$this->notesOnPage);

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


            $existReq = '';
            $reqArr = ['sort', 'order'];

            foreach($reqArr as $req){
                if (isset($_GET[$req])){
                    $existReq .= "&$req=".$_GET[$req];
                }
            }
                        
            $token = $this->isAuth();
            $uri = preg_replace('#\?.+#','', $_SERVER['REQUEST_URI']); 
            if($uri == '/'){
                return $this->render('todo/index',
                    ['posts' => $posts,
                    'pagesCount' => $pagesCount,
                    'page' => $page,
                    'token' => $token,
                    'order' => $order,
                    'sort' => $sort,
                    'message' => $message,
                    'existReq' => $existReq,
                ]);
            } else if ($uri == '/admin'){
                return $this->render('todo/admin',
                    ['posts' => $posts,
                    'pagesCount' => $pagesCount,
                    'page' => $page,
                    'token' => $token,
                    'order' => $order,
                    'sort' => $sort,
                    'existReq' => $existReq,
                    ]);
            }

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
						return $this->render('todo/login',['token' => $token, 'message' => '']);
        }

        //AUTHORIZATION
        public function auth() {
						$this->title = 'Страница входа';
            if (isset($_POST['login']) AND isset($_POST['password'])){

            $userSearch = new User;

            $login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
            $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
            $password = md5($password."salt12345");

            $user = $userSearch->tryAuth($login, $password);

            if($user == false){
					return $this->render('todo/login',['token' => false, 'message' => 'Вы ввели неправильный логин или пароль']);
                exit;
            }

            $_SESSION['admin'] = true;

            header("location: admin");
					} else {
						header("location: login");
					}
        }

        //EXIT
        public function exit(){
            session_destroy();
            header("location: /");
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
