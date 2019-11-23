<?php 
    class admin extends Controller{
        function __construct($view){
            $modelFile = dirname(__DIR__) . '/model/admin_model.php';
            $modelName = 'Admin_model';
            parent::__construct($view, $modelFile, $modelName);
        }

        public function login(){
            // Login already
            if(!empty($_SESSION['username'])){
                header('Location: ' . URL_WEB);
                exit();
            }
            $this->render(__DIR__ . '/../view/login.php', ['title' => 'Login Admin'], true, false, false);
        }

        public function postLogin(){
            //  Check if not empty
            if(empty($_POST['username']) || empty(['password']) || !empty($_SESSION['username'])){
                setHTTPCode(406);
                exit();
            }
            //  Check if user exist
            $user = $this->model->checkAdminExist($_POST['username']);
            if(!$user){
                setHTTPCode(401, "Wrong username or password!");
                exit();
            }
            //  Get first user
            $user = $user[0];
            if(!password_verify($_POST['password'], $user['password'])){ //  Login fail
                setHTTPCode(401, "Wrong username or password!");
                exit();
            }
            //  Login success
            $_SESSION['username'] =  $user['username'];
            $_SESSION['name'] =  $user['name'];
                setHTTPCode(200, "Login success!");
                exit();

                
        }

        public function register(){
            // Login already
            $this->render(__DIR__ . '/../view/register.php', ['title' => 'Register Admin'], TRUE, FALSE);
        }

        public function postRegister(){
            if(!empty($_SESSION['username']) || empty($_POST['name']) || empty($_POST['username']) || empty(['password'])){
                setHTTPCode(406);
                exit();
            }
            $checkExist = $this->model->checkAdminExist($_POST['username']);
            if ($checkExist){
                setHTTPCode(401, 'Admin have exist!');
                exit();
            }
            $createAdmin = $this->model->createAdmin($_POST['username'], $_POST['password'], $_POST['name']);
            if($createAdmin)
                setHTTPCode(201, 'Create new admin success!!');
            else
                setHTTPCode(406, 'Failed create new admin!');
        }

        public function addCate(){
            $this->Authenticate();
            $this->render(__DIR__ . '/../view/add-category.php', ['title' => 'Add Category'], TRUE, FALSE);
        }

        public function postAddCate(){
            $this->Authenticate();
            if(empty($_POST['name'])){
                setHTTPCode(400);
                exit();
            }
            $checkExist = $this->model->checkCateExist($_POST['name']);
            if($checkExist){
                setHTTPCode(406, "Category already exist!");
                exit();
            }
            $createCate = $this->model->addCate($_POST['name']);
            if($createCate)
                setHTTPCode(200, "Create new category successful!");
            else
                setHTTPCode(406, "Error while create new category!");
        }

        public function editCate(){
            $this->Authenticate();
            if(empty($_GET['name'])){
                setHTTPCode(400);
                exit();
            }
            $checkExist = $this->model->checkCateExist($_GET['name']);
            if(!$checkExist){
                setHTTPCode(400, "Not found category to edit!");
                exit();
            }
            $category = $checkExist[0];
            $this->render(__DIR__ . '/../view/edit-category.php', ['title' => 'Edit Category', 'category' => $category], TRUE, FALSE, TRUE);
        }

        public function postEditCate(){
            $this->Authenticate();
            if(empty($_POST['name']) || empty($_POST['oldName'])){
                setHTTPCode(400, "Empty field!");
                exit();
            }
            if($_POST['name'] == $_POST['oldName']){
                setHTTPCode(400, "Same value");
                exit();
            }
            $checkExist = $this->model->checkCateExist($_POST['oldName']);
            if(!$checkExist){
                setHTTPCode(406, "Not found category");
                exit();
            }
            $editCate = $this->model->editCate($_POST['oldName'], $_POST['name']);
            if($editCate)
                setHTTPCode(200, "Edit category successful!");
            else
                setHTTPCode(400, "Error while edit category");
        }

        public function removeCate(){
            $this->Authenticate();
            if(empty($_GET['name'])){
                setHTTPCode(400);
                exit();
            }
            $checkExist = $this->model->checkCateExist($_GET['name']);
            if(!$checkExist){
                setHTTPCode(400, "Not found category to remove!");
                exit();
            }
            $category = $checkExist[0];
            $this->render(__DIR__ . '/../view/remove-category.php', ['title' => 'Remove Category', 'category' => $category], TRUE, FALSE, TRUE);
        }

        public function postRemoveCate(){
            $this->Authenticate();
            if(empty($_POST['name'])){
                setHTTPCode(400, "Empty name!");
                exit();
            }
            $checkExist = $this->model->checkCateExist($_POST['name']);
            if(!$checkExist){
                setHTTPCode(400, "Not found category to remove!");
                exit();
            }
            $delCate = $this->model->removeCate($_POST['name']);
            if($delCate)
                setHTTPCode(200, 'Delete success!');
            else
                setHTTPCode('Fail while delete category!');
        }

        public function Authenticate(){
            if(empty($_SESSION['username'])){
                setHTTPCode(404, "You don't have permission to access this site!");
                exit();
            }
            $getAdmin = $this->model->checkAdminExist($_SESSION['username']);
                //  If not found or result more than 1
            if(!$getAdmin || count($getAdmin) !== 1){
                setHTTPCode(404, "You don't have permission to access this site!");
                exit();
            }
                //  If not Admin
            if($getAdmin[0]['type'] !== 'admin'){
                setHTTPCode(404, "You don't have permission to access this site!");
                exit();
            }
        }
    }
