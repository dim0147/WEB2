<?php 
    class Controller{
        protected $model;
        protected $view;

        function __construct($view, $modelFile, $modelName){
            $this->view = $view;
            $this->model = $this->loadModel($modelFile, $modelName);
        }

        protected function loadModel($model, $newModel){
            if(!file_exists($model)){
                setHTTPCode(500, "Cannot load model");
                exit();
            }
            require($model);
            return new $newModel();
        }

        protected function render($renderFile, $data = [], $header = TRUE, $menu = TRUE, $footer = TRUE){
            $this->view->render($renderFile, $data, $header, $menu, $footer);
        }
    }