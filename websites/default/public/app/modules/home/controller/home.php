<?php 

class home extends Controller{

    function __construct($view){
        $modelFile = dirname(__DIR__) . '/model/Home_model.php';
        $modelName = 'Home_model';;
        parent::__construct($view, $modelFile, $modelName);
    }

    public function index(){
        $category = $this->model->getCate();
        $this->render(__DIR__ . '/../view/home.php', ['category' => $category, 'title' => 'ibuy Auctions']);
    }

    public function search(){
        echo "SearchPage";
    }

}

