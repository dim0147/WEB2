<?php 
 class product extends Controller{

    function __construct(){
        $this->model = $this->loadModel(dirname(__DIR__) . '/model/product_model.php', 'Product_model');
    }

    public function detail(){
        render(__DIR__ . '/../view/detail.php');
    }

 }