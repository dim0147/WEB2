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
                echo "You login already!";
                goOldUrl();
            }
            $this->render(__DIR__ . '/../view/login.php', ['title' => 'Login Admin'], TRUE, FALSE, FALSE);
        }

        public function postLogin(){
            //  Check if not empty
            if(empty($_POST['username']) || empty(['password']) || !empty($_SESSION['username'])){
                echo "Have some missing field!";
                goAdminLogin();
            }
            //  Check if user exist
            $user = $this->model->checkAdminExist($_POST['username']);
            if(!$user){
                setHTTPCode(401, "Wrong username or password!");
                goAdminLogin();
            }
            //  Get first user
            $user = $user[0];
            if(!password_verify($_POST['password'], $user['password'])){ //  Login fail
                setHTTPCode(401, "Wrong username or password!");
                goAdminLogin();
            }
            //  Login success
            $_SESSION['username'] =  $user['username'];
            $_SESSION['name'] =  $user['name'];
            setHTTPCode(200, "Login success!");
            goOldUrl();
        }

        public function register(){
            // Login already
            $this->render(__DIR__ . '/../view/register.php', ['title' => 'Register Admin'], TRUE, FALSE, FALSE);
        }

        public function postRegister(){
            if(!empty($_SESSION['username']) || empty($_POST['name']) || empty($_POST['username']) || empty(['password'])){
                setHTTPCode(406, "Missing some field!");
                goAdminLogin();
            }
            $checkExist = $this->model->checkAdminExist($_POST['username']);
            if ($checkExist){
                setHTTPCode(401, 'Admin have exist!');
                goAdminLogin();
            }
            $createAdmin = $this->model->createAdmin($_POST['username'], $_POST['password'], $_POST['name']);
            if($createAdmin){
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['name'] = $_POST['name'];
                setHTTPCode(201, 'Create new admin success!!');
                goOldUrl();
            }
            else{
                setHTTPCode(406, 'Failed create new admin!');
                goOldUrl();
            }
        }

        public function dashboard(){
            $this->render(__DIR__ . "/../view/dashboard.php", ['title' => 'Admin Dashboard'], FALSE, FALSE, FALSE);
        }

        public function showCategory(){
            $_SESSION['old_url'] = getCurrentURL();
            $this->Authenticate();
            $categorys = $this->model->getCate();
            $this->render(__DIR__ . '/../view/show_category.php', ['title' => 'All Category', 'categorys' => $categorys], FALSE, FALSE, FALSE);
        }

        public function addCate(){
            $_SESSION['old_url'] = getCurrentURL();
            $this->Authenticate();
            $this->render(__DIR__ . '/../view/add-category.php', ['title' => 'Add Category'], TRUE, FALSE);
        }

        public function postAddCate(){
            $this->Authenticate();
            if(empty($_POST['name'])){
                setHTTPCode(400, "Missing field!");
                goUrl("admin/addCate");
            }
            $checkExist = $this->model->checkCateExist($_POST['name']);
            if($checkExist){
                setHTTPCode(406, "Category already exist!");
                goUrl("admin/addCate");
            }
            $createCate = $this->model->addCate($_POST['name']);
            if($createCate){
                setHTTPCode(200, "Create new category successful!");
                goUrl("admin/addCate");
            }
            else{
                setHTTPCode(406, "Error while create new category!");
                goUrl("admin/addCate");
            }
        }

        public function editCate(){
            $_SESSION['old_url'] = getCurrentURL();
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
                goOldUrl();
            }
            if($_POST['name'] == $_POST['oldName']){
                setHTTPCode(400, "Same value");
                goOldUrl();
            }
            $checkExist = $this->model->checkCateExist($_POST['oldName']);
            if(!$checkExist){
                setHTTPCode(406, "Not found category");
                goOldUrl();
            }
            $editCate = $this->model->editCate($_POST['oldName'], $_POST['name']);
            if($editCate){
                setHTTPCode(200, "Edit category successful!");
                goUrl("admin/dashboard");
            }
            else{
                setHTTPCode(400, "Error while edit category");
                goOldUrl();
            }
        }

        public function removeCate(){
            $_SESSION['old_url'] = getCurrentURL();
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
                goOldUrl();
            }
            $checkExist = $this->model->checkCateExist($_POST['name']);
            if(!$checkExist){
                setHTTPCode(400, "Not found category to remove!");
                goOldUrl();
            }
            $delCate = $this->model->removeCate($_POST['name']);
            if($delCate){
                setHTTPCode(200, 'Delete success!');
                goUrl('admin/dashboard');
            }
            else{
                setHTTPCode('Fail while delete category!');
                goOldUrl();
            }
        }

        public function showAccount(){
            $_SESSION['old_url'] = getCurrentURL();
            $this->Authenticate();
            $accounts = $this->model->getAccount();
            $this->render(__DIR__ . '/../view/show_account.php', ['title' => 'All Account', 'accounts' => $accounts], FALSE, FALSE, FALSE);
        }

        public function removeUser(){
            $this->Authenticate();
            if(!isset($_GET['id'])){
                echo "Missing require field!";
                goOldUrl();
            }
            $this->model->removeAccount($_GET['id']);
            echo "Remove done!";
            goOldUrl();
        }
        
        public function showAuction(){
            $_SESSION['old_url'] = getCurrentURL();
            $this->Authenticate();
            $auctions = $this->model->getAuction();
            $this->render(__DIR__ . '/../view/show_auction.php', ['title' => 'All Account', 'auctions' => $auctions], FALSE, FALSE, FALSE);
        }

        public function Authenticate(){
            if(empty($_SESSION['username'])){
                setHTTPCode(404, "You need login first!");
                goAdminLogin();
            }
            $getAdmin = $this->model->checkAdminExist($_SESSION['username']);
                //  If not found or result more than 1
            if(!$getAdmin || count($getAdmin) !== 1){
                setHTTPCode(404, "You don't have permission to access this site!");
                goUrl('');
            }
                //  If not Admin
            if($getAdmin[0]['type'] !== 'admin'){
                setHTTPCode(404, "You don't have permission to access this site!");
                goUrl('');
            }
        }
        
    }
