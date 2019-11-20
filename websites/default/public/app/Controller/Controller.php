<?php 
    class Controller{
        protected $model;

        function __construct(){

        }

        protected function loadModel($model, $newModel){
            if(!file_exists($model)){
                setHTTPCode(500, "Cannot load model");
                exit();
            }
            require($model);
            return new $newModel();
        }
    }