<?php 

 class product extends Controller{

    private $apiContext;
    function __construct($view){
        $modelFile = dirname(__DIR__) . '/model/product_model.php';
        $modelName = 'Product_model';
        parent::__construct($view, $modelFile, $modelName);
        $this->apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                PAYPAL_CLIENT_ID,    
                PAYPAL_SECRET      
            ));
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
        if($product['approve'] == 0 || $product['approve'] == FALSE)
            exit("Product need approve by admin!");
        if($product['status'] !== 'Open')
            exit("Product currently not available!");
        $_SESSION['old_url'] = getCurrentURL();
        $this->render(__DIR__ . '/../view/detail.php', ['title' => "Product detail", 'product' => $product, 'category' => $category]);
    }

    public function showBidAuction(){
        if(!isset($_GET['id']))
            exit("Missing require field!");
        $product = $this->model->getProductById($_GET['id']);
        if(!$product)
            exit("No product found!");
        if($product['status'] !== 'Open')
            exit("Product currently not available!");
        $birds = $this->model->getBirdOfAuction($_GET['id']);
        $winnerBird = NULL;
        if($birds){
            foreach($birds as $bird){
                if(!empty($bird['product_id_win'])){
                    $winnerBird = $bird;
                    break;
                }
            }
        }
        $this->render(__DIR__ . '/../view/show_bird.php', ['winnerBird'=> $winnerBird,'title' => $product['name'] . "'s Bird", 'product' => $product, 'birds' => $birds]);
    }

    public function postPlaceBid(){
        //  Check if user login
        if(empty($_SESSION['username'])){
            echo "You have to login first!";
            goUserLogin();
        }
        //  Check if empty field
        if(empty($_POST['id']) || empty($_POST['amount'])){
            echo "Missing require field!";
            goOldUrl();
        }
            
        // Check if invalid amount
        if(!is_numeric($_POST['amount']) || (float)$_POST['amount'] <= 0.00){
            echo "Please insert valid amount!";
            goOldUrl();
        }
            
        //  Check product, return information about product
        $product = $this->model->checkProductExist($_POST['id']);
        if(!$product){
           echo "Not found product!";
           goOldUrl();
        }

        // Check if product is Open
        if($product[0]['status'] !== 'Open'){
            echo "Product currently not available!";
            goOldUrl();
        }
        //  Check if product not approve by admin
        if($product[0]['approve'] == 0 || $product[0]['approve'] == FALSE){
            echo "Product need approve by admin!";
            goOldUrl();
        }
        //  Check if end_Date smaller than current time
        if(strtotime($product[0]['end_at']) <= time() || $product[0]['finish'] === TRUE || !empty($product[0]['bid_winner_id'])){
            echo "This auction is finish! You cannot place bid!";
            goOldUrl();
        }
        //  Check if bid more than hot_price
        if(!empty($product[0]['hot_price']) && (float)$product[0]['hot_price'] !== 0.00 && ((float)$_POST['amount'] >= (float)$product[0]['hot_price'])){
            echo "Why you don't buy it with hot price?";
            goOldUrl();
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
        goOldUrl();
    }

    public function placeHotBid(){
         //  Check if user login
        if(empty($_SESSION['username'])){
            echo "You have to login first!";
            goUserLogin();
        }

        if(!isset($_GET['id'])){
            echo "Missing require field!";
            goOldUrl();
        }
        
        $product = $this->model->getProductById($_GET['id']);
        if(!$product){
            echo "Not Found product!";
            goOldUrl();
        }

        $this->checkUserValid($product['product_user_id']);

        if($product['status'] !== 'Open'){
            echo "Product currently not available!";
            goOldUrl();
        }

        if($product['finish'] == TRUE){
            echo "This product is end bid!";
            goOldUrl();
        }

        if(empty($product['hot_price']) || (float)$product['hot_price'] === 0.00){
            echo "This product don't have hot price!";
            goOldUrl();
        }
        $this->createPayment($product['id'], $product['name'], 1, $product['hot_price']);
    }

    public function checkRequireBird($current_bird, $bird_max, $bird_minimum, $own_product){
        if((float)$bird_max !== 0.00 && ((float)$_POST['amount'] >= (float)$bird_max)){
            echo "You bid more than accept! Please consider lower! So weird!";
            goOldUrl();
        }
            
        if((float)$bird_minimum !== 0.00 && ((float)$_POST['amount'] <= (float)$bird_minimum)){
            echo "That's a little small, please bid more than £" . $bird_minimum . "!";
            goOldUrl();
        }
            
        if(!empty($current_bird) && ((float)$_POST['amount'] <= (float)$current_bird)){
            echo "You need to place higher than £" . $current_bird. "!";
            goOldUrl();
        }
            
    }

    public function checkUserValid($own_product){
        //  Get user
        $user = $this->model->getUserByUsrName($_SESSION['username']);
        if(!$user){  //  If not found
            echo "Unauthorized, Please logout!";
            goOldUrl();
        }
        // Get ID of user 
        $userID = $user[0]['id']; 

        // If user is the own of product
        if($userID == $own_product){
            echo "You can't place bid on your product!";
            goOldUrl();
        }
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
        if(empty($_POST['id']) || empty($_POST['review_text'])){
            echo "Missing field!";
            goOldUrl();
        }
            
        if(empty($_SESSION['username'])){
            echo "You need login first!!";
            goUserLogin();
        }
            
        $checkProd = $this->model->checkProductExist($_POST['id']);
        if(!$checkProd){
            echo "Product not exist!";
            goOldUrl();
        }

        if($checkProd[0]['status'] !== 'Open'){
            echo "Product currently not available!";
            goOldUrl();
        }

        //  Check if product not approve by admin
        if($checkProd[0]['approve'] == 0 || $checkProd[0]['approve'] == FALSE){
            echo "Product need approve by admin!";
            goOldUrl();
        }
            
        $getUser = $this->model->getUserByUsrName($_SESSION['username']);
        if(!$getUser){
            echo "Unauthorized!, please logout and login again";
            goOldUrl();
        }
            
        $userID = $getUser[0]['id'];
        $this->model->createReview($_POST['id'], $userID, $_POST['review_text']);
        echo "Create review successful!";
        goOldUrl();
    }

    public function createPayment($idProduct, $name, $quantity, $price){
        $payerInfo = new  PayPal\Api\PayerInfo();
        $payerInfo->setFirstName($_SESSION['username']);
        

        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod("paypal")
              ->setPayerInfo($payerInfo);

        $item = new \PayPal\Api\Item();
        $item->setName($name)
        ->setCurrency('SGD')
        ->setQuantity($quantity)
        ->setSku($idProduct) // Similar to `item_number` in Classic API
        ->setPrice($price);  
        $itemList = new \PayPal\Api\ItemList();
        $itemList->setItems(array($item));

        $amount = new \PayPal\Api\Amount();
        $amount->setCurrency("SGD")
               ->setTotal($price * $quantity);

        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount)
                    ->setItemList($itemList)
                    ->setDescription("Buy ".$name." with hot price: ".$price);

        $redirectUrls = new \PayPal\Api\RedirectUrls();
        $redirectUrls->setReturnUrl(URL_WEB . 'product/completeCheckout?success=true&username=' . $_SESSION['username'])
                     ->setCancelUrl(URL_WEB . 'product/completeCheckout?success=false&username=' . $_SESSION['username']);

        $payment = new \PayPal\Api\Payment();
        $payment->setIntent("sale")
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions(array($transaction));

        try{
            $payment->create($this->apiContext);
        }
        catch (PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode(); // Prints the Error Code
            echo $ex->getData(); // Prints the detailed error message 
            die($ex);
        } catch (Exception $ex) {
            die($ex);
        }
        $urlPayment = $payment->getApprovalLink();
        header("Location: " . $urlPayment);
        exit;
    }

    public function completeCheckout(){
        if(empty($_GET['success']) || !isset($_GET['paymentId']) || empty($_GET['username'])){
            exit("Missing require field! <br><a href=".URL_WEB.">Click here to go back home!</a>");
        }
        if($_GET['success'] !== 'true'){
            goOldUrl();
        }
        try{
            $payment = \PayPal\Api\Payment::get($_GET['paymentId'], $this->apiContext);
        }
        catch (PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getData(); 
            die($ex);
        } catch (Exception $ex) {
            die($ex);
        }
        $result = $payment->toArray();
        if(!isset($result['state'], $result['transactions']['0']['item_list']['items'][0]['sku'], $result['transactions']['0']['item_list']['items'][0]['price'])){
            exit("Cannot get require information! <br><a href=".URL_WEB.">Click here to go back home!</a>");
        }
        $status = $result['state'];
        $idProduct = $result['transactions']['0']['item_list']['items'][0]['sku'];
        $hotPrice = $result['transactions']['0']['item_list']['items'][0]['price'];  
        if($status != "created" && $status != "approved")
            exit("Transaction fail! <br><a href=".URL_WEB.">Click here to go back home!</a>");
        $product = $this->model->getProductById($idProduct);
        if(!$product)
            exit("Product not found! <br><a href=".URL_WEB.">Click here to go back home!</a>");
        if($product['finish'] === TRUE || !empty($product['bid_winner_id']))
            exit("This product is finish bid! <br><a href=".URL_WEB.">Click here to go back home!</a>");
        $user = $this->model->getUserByUsrName($_SESSION['username']);
        if(!$user)
            exit("User not exist! <br><a href=".URL_WEB.">Click here to go back home!</a>");
        $userId = $user[0]['id'];
        if($product['product_user_id'] == $userId){
            exit("You cannot bid on your product! <br><a href=".URL_WEB.">Click here to go back home!</a>");
        }
        $idNewBid = $this->model->createHotBidProduct($idProduct, $userId, $hotPrice);
        $this->model->updateBirdPriceProd($idProduct, $hotPrice);
        $this->model->finishAuctionWithBid($idProduct, $idNewBid);
        echo "Congratulation, you have won this auction! <br><a href=".URL_WEB.">Click here to go back home!</a>";
    }

 }