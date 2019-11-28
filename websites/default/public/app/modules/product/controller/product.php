<?php 
 class product extends Controller{

    function __construct($view){
        $modelFile = dirname(__DIR__) . '/model/product_model.php';
        $modelName = 'Product_model';
        parent::__construct($view, $modelFile, $modelName);
    }

    public function detail(){
        if(!isset($_GET['id'])){
            setHTTPCode(400);
            exit;
        }
        $category = $this->model->getCate();
        $product = $this->model->getProductById($_GET['id']);
        if(!$product)
            exit("Cannot find product!");
        $_SESSION['old_url'] = getCurrentURL();
        $this->render(__DIR__ . '/../view/detail.php', ['title' => "Product detail", 'product' => $product, 'category' => $category]);
    }

    public function postPlaceBid(){
        //  Check if user login
        if(empty($_SESSION['username'])){
            echo "You have to login first!";
            goUserLogin();
        }
        //  Check if empty field
        if(empty($_POST['id']) || empty($_POST['amount']))
            exit("Missing require field!");
        // Check if invalid amount
        if(!is_numeric($_POST['amount']) || (float)$_POST['amount'] <= 0)
            exit("Please insert valid amount!");
        //  Check product, return information about product
        $product = $this->model->checkProductExist($_POST['id']);
        if(!$product)
            exit("Not found product!");
        //  Check if end_Date smaller than current time
        if(strtotime($product[0]['end_at']) <= time()){
            exit("This auction is finish! You cannot place bird!");
        }
        //  Get id, current_bird, bird max, bird minimum, the owner of product
        $idProd = $product[0]['id'];
        $current_bird = $product[0]['current_bird_price'];
        $bird_max = $product[0]['bird_max_price'];
        $bird_minimum = $product[0]['bird_minimum_price'];
        $own_product = $product[0]['user_id'];
        //  Validate amount
        $this->checkRequireBird($current_bird, $bird_max, $bird_minimum, $own_product);
        //  Check if user place bird is valid, if valid return user id
        $userID = $this->checkUserValid($own_product);
        //  Create bird, after that update current bird
        $this->model->createProductBird($idProd, (float)$_POST['amount'], $userID);
        $this->model->updateBirdPriceProd($idProd, (float)$_POST['amount']);
        setHTTPCode(200, "Place your bid success!");
        exit;
    }

    public function checkRequireBird($current_bird, $bird_max, $bird_minimum, $own_product){
        if((int)$bird_max !== 0 && ((float)$_POST['amount'] >= (float)$bird_max))
            exit("You bird more than accept! Please consider lower! So weird!");
        if((int)$bird_minimum !== 0 && ((float)$_POST['amount'] <= (float)$bird_minimum))
            exit("That's a little small, please bird more than £" . $bird_minimum . "!");
        if(!empty($current_bird) && ((float)$_POST['amount'] <= (float)$current_bird))
            exit("You need to place higher than £" . $current_bird. "!");
    }

    public function checkUserValid($own_product){
        //  Get user
        $user = $this->model->getUserByUsrName($_SESSION['username']);
        if(!$user)  //  If not found
            exit("Unauthorized, Please logout!");
        // Get ID of user 
        $userID = $user[0]['id']; 
        // If user is the own of product
        if($userID == $own_product) 
            exit("You can't place bid on your product!");
        return $userID;
    }

    public function getReview(){
        if(empty($_GET['id'])){
            setHTTPCode(400, "ID Field empty!");
            exit;
        }
        $reviews = $this->model->getReview($_GET['id']);
        if(!$reviews){
            setHTTPCode(404);
            exit;
        }
        setHTTPCode(200, "", FALSE);
        echo json_encode($reviews);
    }

    public function postReview(){
        if(empty($_POST['id']) || empty($_POST['review_text']))
            exit("Missing field!");
        if(empty($_SESSION['username']))
            exit("Unauthorized!");
        $checkProd = $this->model->checkProductExist($_POST['id']);
        if(!$checkProd)
            exit("Product not exist!");
        
        $getUser = $this->model->getUserByUsrName($_SESSION['username']);
        if(!$getUser)
            exit("Unauthorized!");
        $userID = $getUser[0]['id'];
        $this->model->createReview($_POST['id'], $userID, $_POST['review_text']);
        echo "Create successful!";
    }

 }