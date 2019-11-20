<?php 
    class Product_model extends Database{

        function __construct($table = 'product'){
            parent::__construct($table);
        }

        public function index(){
            echo "Model work";
        }
    }
