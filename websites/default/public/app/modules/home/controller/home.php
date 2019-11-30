<?php 

class home extends Controller{

    function __construct($view){
        $modelFile = dirname(__DIR__) . '/model/Home_model.php';
        $modelName = 'Home_model';;
        parent::__construct($view, $modelFile, $modelName);
    }

    public function index(){
        $category = $this->model->getCate();
        $product = $this->model->getProduct();
        $this->render(__DIR__ . '/../view/home.php', ['category' => $category, 'products' => $product, 'title' => 'ibuy Auctions']);
    }

    public function search(){
        if(empty($_GET['category']) && empty($_GET['string']))
            exit("Please enter category or some string to search!");
        $product = NULL;
        $category = NULL;
        if(!empty($_GET['category'])){
            $product = $this->model->getProductByCategory($_GET['category']);
            $searchStr = "Latest Listings / Search Results / " . $_GET['category'] ."listing";
        }
        else if (!empty($_GET['string'])){
            $product = $this->model->getProductByString($_GET['string']);
            $searchStr = "Latest Listings / Search Results / " . $_GET['string'];
        }
        $category = $this->model->getCate();
        $this->render(__DIR__ . '/../view/search.php', ['category' => $category, 'products' => $product, 'title' => 'Search Auction', 'searchStr' => $searchStr]);
    }

}

