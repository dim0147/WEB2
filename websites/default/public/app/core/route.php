<?php 
    $request = new Request();
    $view = new View();
    $controllerHome = 'home';

    $modules = $request->getModule();
    $method = $request->getMethod();
        // Request homepage, eg: v.je, v.je/
    if(!$modules){
        if(!is_dir(MODULE_DIR . '/' . HOME_MODULE) || !file_exists(MODULE_DIR . '/' . HOME_MODULE . '/controller/home.php'))
            die("Can't find Home Page!");
        else{
            require(MODULE_DIR . '/' . HOME_MODULE . '/controller/'. $controllerHome .'.php');
            $HomePage = new $controllerHome($view);
            $HomePage->index();
            return;
        }
    }
    
        // Request homepage, v.je/home/page, redirect to v.je
    if ($modules === 'home' && $method === 'index'){
        header("Location: " . URL_WEB);
        return;
    }

    
    if(!$method){ //  check Controller if empty
        setHTTPCode(404, "URL ERROR: Not found method!");
        exit();
    }
        //  Get controller Name (modules name = controller name)
    $controller = MODULE_DIR . '/' . $modules . '/controller/' . $modules . '.php';
        //  If not found modules or controller of module
    if(!file_exists(MODULE_DIR . '/' . $modules) || !file_exists($controller)){
        setHTTPCode(400, "URL ERROR: Not found modules or controller!");
        exit();
    }
        //  Load controller
    require($controller);
        //  Create new controller (modules name = controller name)
    $controller = new $modules($view);                
    if(!method_exists($controller, $method)){   //  If method not found in controller
        setHTTPCode(404, "URL ERROR: Not found method in controller!");
        exit();
    }
    $controller->$method(); //  Trigger method