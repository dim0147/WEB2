<?php 
class unitTest{
    function __construct($view){
    }


    public function testPostPlaceBid(){
        //  Set up 
        $product = $this->setUp();
        //  Assign require field
        $_SESSION['username'] = 'dinh111';
        $_POST['id'] = 71;
        $_POST['amount'] = 200;

        //  Test function
        $product->postPlaceBid();
    }

    public function testRegisterAdmin(){
        //  Set up 
        $admin = $this->setUp();
        //  Assign require field
        $_SESSION['username'] = null;
        $_POST['username'] = 'Andy';
        $_POST['name'] = 'Andy123';
        $_POST['password'] = 'AndyHandSome';

        //  Test function
        $admin->postRegister();
    }

    public function setUp(){
        require(MODULE_DIR . '/admin/controller/admin.php');
        $model = new admin(null);
        return $model;
    }

    public function assertEquals($resultExpect, $function){
        $resultExecute = $function();
        if($resultExpect !== $resultExecute){
            echo "Fail while testing, value not expected, result expect: $resultExpect | result return: $resultExecute";
            exit;
        }
        if($resultExpect == $resultExecute){
            echo "Success testing, the test was success full, result expect: $resultExpect | result return: $resultExecute";
            exit;
        }
    }
}