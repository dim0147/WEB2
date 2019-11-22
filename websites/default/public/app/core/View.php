<?php 

    class View {

        public function render($renderFile, $data = ['title' => 'ibuy Auction'], $header = TRUE, $menu = TRUE, $footer = TRUE){
            $headerFile = __DIR__ . '/../Views/header.php';
            $menuFile = __DIR__ . '/../Views/menu.php';
            $footerFile = __DIR__ . '/../Views/footer.php';
            //	Check if header and footer file is exist
            if(!file_exists($headerFile) || !file_exists($menuFile) || !file_exists($footerFile))
            {
                setHTTPCode(500, "Missing header or footer template!");
                exit();
            }
            //	Check if file render is not exist
            if(!file_exists($renderFile))
                die("File render not exist!, Error file: " . $renderFile);
            ob_start();
                extract($data);
                if($header) //  If header is require
                    require($headerFile);

                if($menu) //  If menu is require
                    require($menuFile);

                require($renderFile);   //  render file

                if($footer) //  If footer is require
                    require($footerFile);
                $content = ob_get_contents();
            ob_end_clean();
            $content = $this->compileContent($content);
            if(!$content){
                setHTTPCode(500, "Error while compile view, File path: $renderFile");
                exit;
            }
            echo $content;
        }

        public function compileContent($content){
            $linkCss = getStringBetween($content, '[css]', '[/css]');
            if($linkCss){ //  If have addition css
                $content = $this->compileCss($content, $linkCss); //  Compile css
                if(!$content)    //  If error
                    return FALSE;
            }
            else{ //  If don't addition css
                $content = deleteString($content, '<@yield css>');  //  Remove Hold place field
                if(!$content)    //  If error
                    return FALSE;
            }
            return $content;
        }

        public function compileCss($content, $linkCss){
                //  Hold place field
            $holdPlaced = "<@yield css>";
                //  regexp to get link css
            $regexp = '/(<link rel[^\n]*\/>)/m';
            preg_match_all($regexp, $linkCss, $matches, PREG_SET_ORDER, 0);
                //  If don't have any link css
            if(count($matches) < 1)
                return false;
                
                foreach($matches as $value){    //  Loop through links css and insert it on hold place field
                    $content = insertString($value[0], $holdPlaced, $content);
                }
                $content = deleteString($content, '[css]', '[/css]');   //  Remove reference
                if($content === false) 
                    return false;

                $content = deleteString($content, '<@yield css>');  //  Remove Hold place field
                if($content === false)  
                    return false;
                // Success compile css
                return $content;

        }
        


    }