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

    public function addAuction(){
        if(empty($_SESSION['username'])){
            setHTTPCode(401, "Require login!");
            exit;
        }

        $category = $this->model->getCate();
        if(!$category){
            exit("Please goto admin page to add at least 1 category to continue!");
        }
        $this->render(__DIR__ . "/../view/add_auction.php", ["category" => $category, "title" => "Add New Auction"], TRUE, FALSE);
    }

    public function postAddAuction(){
        if(empty($_SESSION['username'])){
            setHTTPCode(401, "Require login!");
            exit;
        }
        // Check if empty or not have necessary field avoid error
        if(!$this->checkPostAddRequest()){
            setHTTPCode(400, "Field Error!");
            exit;
        }
        $this->validPrice();
        //  Check if end_Date smaller than current time
        if(strtotime($_POST['end_date']) <= time()){
            setHTTPCode(400, "End date smaller than current date!!");
            exit;
        }
        //  Get user
        $user = $this->model->getUsrByUsrName($_SESSION['username']);
        if(!$user || count($user) !== 1){   //  If not found or result > 1
            setHTTPCode(400, "Error while Authenticate, please logout and login again!");
            exit;
        }   
        //  Get user ID
        $userID = $user[0]['id'];

        //  Upload header image to local storage, return name image
        $nameHeaderImg = $this->uploadLocalHeader();
        if(!$nameHeaderImg){    //  If fail
            setHTTPCode(500, "Error while upload header image to local!");
            exit;
        }

        //  Create new auction
        $idProd = $this->model->createNewAuction($_POST['name'], $_POST['description'], $nameHeaderImg, $_POST['maximum_price'], $_POST['minimum_price'], $_POST['hot_price'], $_POST['end_date'], $_POST['status'], $userID);
        
        //  Upload thumbnail image, check if first name array not null
        if(!empty($_FILES['thumbnail']['name'][0])){
            // Upload thumbnail image to local storage
            $listThumbnail = $this->uploadLocalThumb();
            if($listThumbnail === FALSE){   // If fail
                setHTTPCode(400, "Error while upload thumbnail to local storage!");
                exit;
            }
            //  Upload to database
            $this->model->uploadThumbnailAuction($listThumbnail, $idProd);
        }

        //  Check if have category then create category
        if(!empty($_POST['category'])){
            $this->model->createCategoryProduct($_POST['category'], $idProd);
        }
        setHTTPCode(200, "Create success!");
        exit;
    }

    public function postRemoveAuction(){
        if(empty($_POST['id'])){
            setHTTPCode(400);
            exit;
        }
        if(empty($_SESSION['username'])){
            setHTTPCode(404, "Unauthorized!");
            exit;
        }
        $getProduct = $this->model->getAuctionById($_POST['id']);
        if(!$getProduct){
            setHTTPCode(404);
            exit;
        }
        $own_product = $getProduct['username'];
        if($own_product != $_SESSION['username']){
            setHTTPCode(404, "You are not the owner of this product!");
            exit;
        }
        $this->model->removeAuction($getProduct);
        setHTTPCode(200, "Remove success!");
    }

    public function editAuction(){
        if(empty($_GET['id']))
            exit("Missing field!");
        if(empty($_SESSION['username']))
            exit("Unauthorized!");
        $getUser = $this->model->getUsrByUsrName($_SESSION['username']);
        if(!$getUser)
            exit("Unauthorized!");
        $userID = $getUser[0]['id'];
        $product = $this->model->getAuctionById($_GET['id']);
        if(!$product)
            exit("Not found product!");
        $own_product = $product['user_id'];
        if($userID != $own_product)
            exit("You are not the owner of this auction!");
        $allCate = $this->model->getCateArr();
        if(!$allCate)
            exit("Cannot find any category, please add some category!");
        //  If is array and not empty
        if(is_array($product['category_name']) && !empty($product['category_name']))   
            $allCate = array_diff($allCate, $product['category_name']);
        $this->render(__DIR__. '/../view/edit_auction.php', ['title' => "Edit auction", "product" => $product, "cateOutProduct" => $allCate], TRUE, FALSE);
    }

    public function postEditAuction(){
        $this->checkPostEditRequest();
        if(empty($_SESSION['username']))
            exit("Require login!");
        $this->validPrice();
        //  Check if end_Date smaller than current time
        if(strtotime($_POST['end_date']) <= time()){
            setHTTPCode(400, "End date smaller than current date!!");
            exit;
        }
        //  Get product And user
        $product = $this->model->getProductWithUsr($_POST['id']);
        if(!$product)
            exit("Not found product!");
        $own_product = $product['username'];
        //  Check if not owner of product
        if($own_product != $_SESSION['username'])
            exit("You are not the owner of this auction!");
        //  Upload header
        $nameHeader = $this->uploadLocalHeader();
        $updateData = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'minimum_price' => $_POST['minimum_price'],
            'maximum_price' => $_POST['maximum_price'],
            'hot_price' => $_POST['hot_price'],
            'end_at' => $_POST['end_date'],
            'status' => $_POST['status'],
            'image' => $nameHeader
        ];
        $this->model->editAuction($updateData, $_POST['id']);
        //  If have upload header image, remove old image
        if($nameHeader){
            deleteImage($product['image']);
        }
        //  Upload thumbnail image, check if first name array not null
        if(!empty($_FILES['thumbnail']['name'][0])){
            // Upload thumbnail image to local storage
            $listThumbnail = $this->uploadLocalThumb();
            if($listThumbnail === FALSE)  // If fail
               exit("Error while upload thumbnail to local storage!");
            //  Upload to database
            $this->model->uploadThumbnailAuction($listThumbnail, $_POST['id']);
        }
        //  Check if delete thumbnail is not empty end decode is not fail
        if(!empty($_POST['thumbnail_delete']) && json_decode($_POST['thumbnail_delete'])){
            //  Decode to get array
            $arrDeleteThumb = json_decode($_POST['thumbnail_delete']);
            //  Get list name of thumb to delete
            $listNameThumb = $this->model->getThumbnailAuction($arrDeleteThumb, $_POST['id']);
            if(!$listNameThumb) //  If fail
                exit("Invalid thumbnail to delete!");
            $this->model->deleteThumbnailAuction($arrDeleteThumb, $_POST['id']);
            //  Delete on local storage
            foreach($listNameThumb as $name){
                deleteImage($name);
            }
        }
        //  Delete category
        if(!empty($_POST['category'])){
            //  Get id of category of product
            $IdsCateProd = array_keys($product['category_name']);
            //  Get category need to add
            $addCate = array_diff($_POST['category'], $IdsCateProd);
            //  Get category need to delete
            $delCate = array_diff($IdsCateProd, $_POST['category']);
            //  Check if not empty, perform action
            if(!empty($addCate))
                $this->model->createCategoryProduct($addCate, $_POST['id']);
            if(!empty($delCate))
                $this->model->removeCateProduct($delCate, $_POST['id']);
        }
        echo "Success update!";
    }

    public function validPrice(){
        //  Check if is number and not equal or small than 0
        if((!empty($_POST['maximum_price']) && (!is_numeric($_POST['maximum_price']) || (float)$_POST['maximum_price'] < 0.00 )) || 
           (!empty($_POST['minimum_price']) && (!is_numeric($_POST['minimum_price']) || (float)$_POST['minimum_price'] < 0.00 )) ||
           (!empty($_POST['hot_price']) && (!is_numeric($_POST['hot_price']) || (float)$_POST['hot_price'] < 0.00 )))
            exit("Please enter a valid Number!");

        //  Check if not empty minimum price and maximum price
        if(!empty($_POST['minimum_price']) && !empty($_POST['maximum_price'])  && (float)$_POST['maximum_price'] !== 0.00){
            //  Convert to float
            $minimum = (float)$_POST['minimum_price'];
            $maximum = (float)$_POST['maximum_price'];
            if($minimum >= $maximum)    //  If minimum bigger than maximum price
                exit("Maximum price not valid!");
        }

        //  Check if not empty minimum price and hot price
        if(!empty($_POST['minimum_price']) && !empty($_POST['hot_price']) && (float)$_POST['hot_price'] !== 0.00){
            //  Convert to float
            $minimum = (float)$_POST['minimum_price'];
            $hot = (float)$_POST['hot_price'];
            if($minimum >= $hot)    //  If minimum bigger than maximum price
                exit("Hot price not valid!");
        }

    }

    public function uploadLocalHeader(){
        if ($_FILES['header']['error'] != UPLOAD_ERR_OK || empty($_FILES['header']['name']))
            return FALSE;
        $nameImg = createImgName($_FILES['header']['name']);
        $result = move_uploaded_file($_FILES['header']['tmp_name'], PATH_IMAGE_UPLOAD . "/" . $nameImg);
        if($result === false)
            return FALSE;
        return $nameImg;
    }
    
    public function uploadLocalThumb(){
        //  Check if not an array
        if(!is_array($_FILES['thumbnail']['name']))
            return FALSE;   
        //  Get length of image
        $length = count($_FILES['thumbnail']['name']);
        //  Create empty array hold list name image
        $listImg = [];
        //   loop through list image
        for($i = 0; $i < $length; $i++){
            //  If error
            if($_FILES['thumbnail']['error'][$i] !=  UPLOAD_ERR_OK)
                return FALSE;
            //  Create new name image
            $nameImg = createImgName($_FILES['thumbnail']['name'][$i]);
            //  Upload to local storage
            $result = move_uploaded_file($_FILES['thumbnail']['tmp_name'][$i], PATH_IMAGE_UPLOAD . '/' . $nameImg);
            // If fail while upload to local storage
            if($result === FALSE){
                echo "error here!";
                return FALSE;
            }
                
            //  Push name image which just upload to local storage to listImg array
            array_push($listImg, $nameImg);
        }
        // Return list image, otherwise it will return false
        return $listImg;
    }

    public function checkPostAddRequest(){
        if(empty($_POST['name']) || empty($_POST['description']) || !isset($_POST['minimum_price']) || 
        !isset($_POST['maximum_price']) || !isset($_POST['hot_price']) || empty($_POST['end_date']) ||
        empty($_POST['status']) || !isset($_FILES['header']) || !isset($_FILES['thumbnail']) || 
        (empty($_POST['category']) || !is_array($_POST['category']))){
            return false;
        }
        return true;
    }
    
    public function checkPostEditRequest(){
        if(empty($_POST['name']) || !isset($_POST['id'])|| empty($_POST['description']) || !isset($_POST['minimum_price'])
          || !isset($_POST['maximum_price']) || !isset($_POST['hot_price']) || empty($_POST['end_date'])
          || empty($_POST['status']) || empty($_POST['category']) || !is_array($_POST['category'])
          || !isset($_FILES['header']) || !isset($_FILES['thumbnail']))
            exit("Field error!");
    }

    public function Authenticate(){
        if(!empty($_SESSION['username'])){
            setHTTPCode(401, "You Login Already!");
            exit();
        }
    }
}