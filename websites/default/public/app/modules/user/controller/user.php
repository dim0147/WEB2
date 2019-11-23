<?php

class user extends Controller{

    function __construct($view){
        $modelFile = dirname(__DIR__) . "/model/user_model.php";
        $modelName = "User_model";
        parent::__construct($view, $modelFile, $modelName);
    }

    public function login(){
        $this->Authenticate();
        $this->render(__DIR__ . "/../view/login.php", ["title" => "User Login"], TRUE, FALSE);
    }

    public function postLogin(){
        $this->Authenticate();
        if(empty($_POST['username']) || empty($_POST['password'])){
            setHTTPCode(400, "Empty field!");
            exit;
        }
        $checkUsr = $this->model->getUsrByUsrName($_POST['username']);
        if(!$checkUsr){
            setHTTPCode(400, "Wrong username or password!");
            exit;
        }
        //  Means has 2 user or don't have any user
        if(count($checkUsr) > 1 || count($checkUsr) !== 1){
            setHTTPCode(400, "Wrong username or password!");
            exit;
        }
        $user = $checkUsr[0];
        //  Means that is admin
        if($user['type'] == "admin"){
            setHTTPCode(400, "Wrong username or password!");
            exit;
        }
        //  if Wrong password
        if(!password_verify($_POST['password'], $user['password'])){
            setHTTPCode(400, "Wrong username or password!");
            exit;
        }
        $_SESSION['username'] =  $user['username'];
        $_SESSION['name'] =  $user['name'];
        setHTTPCode(200, "Login success");
        exit;
    }

    public function register(){
        $this->Authenticate();
        $this->render(__DIR__ . "/../view/register.php", ["title" => "User Register"], TRUE, FALSE);
    }

    public function postRegister(){
        $this->Authenticate();
        if(empty($_POST['username']) || empty($_POST['password']) || empty($_POST['name'])){
            setHTTPCode(400, "Empty field!");
            exit;
        }
        $checkUsr = $this->model->getUsrByUsrName($_POST['username']);
        if($checkUsr){
            setHTTPCode(400, "User exist!");
            exit;
        }
        $createUsr = $this->model->createNewUsr($_POST['username'], $_POST['password'], $_POST['name']);
        if($createUsr === FALSE){
            setHTTPCode(400, "Error while register! Please try again!");
            exit;
        }
        $_SESSION['username'] =  $_POST['username'];
        $_SESSION['name'] =  $_POST['name'];
        setHTTPCode(201, "Create user " . $_POST['username'] . " success!");
        exit;
    }

    public function Authenticate(){
        if(!empty($_SESSION['username'])){
            setHTTPCode(401, "You Login Already!");
            exit();
        }
    }
}