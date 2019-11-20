<?php 

    class Request {

        public function getModule(){
            $URI = ltrim($_SERVER['REQUEST_URI'], '/');
            $parameter = explode('/', $URI);
                //  If don't have anything
            if(count($parameter) < 1){
                return false;
            }
            $modules = $parameter[0];
                //  If module have query search
            if($modules !== $this->removeQuery($modules)){
                setHTTPCode(400);
                exit();
            }
            return $modules;

        }
        
        public function getMethod(){
            $URI = ltrim($_SERVER['REQUEST_URI'], '/');
            $parameter = explode('/', $URI);
                //  If don't have method
            if(count($parameter) !== 2){
                return false;
            }
            $method = $this->removeQuery($parameter[1]);
            return $method;
        }

        private function removeQuery($url){
            $getQueryP = strpos($url, '?');  //  check if have param
            if($getQueryP)
                $url = substr($url, 0, $getQueryP);    //  remove params
            return $url;
        }

    }
