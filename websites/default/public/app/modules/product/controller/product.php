<?php 
 class product extends Controller{

    function __construct($view){
        $modelFile = dirname(__DIR__) . '/model/product_model.php';
        $modelName = 'Product_model';
        parent::__construct($view, $modelFile, $modelName);
    }

    public function detail(){
        $this->render(__DIR__ . '/../view/detail.php', ['title' => "Product detail"]);
    }

 }