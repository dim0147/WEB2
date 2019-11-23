<?php 
    class Product_model extends Database{

        function __construct(){
            parent::__construct();
        }

        public function index(){
            echo "Model work";
        }
    }
