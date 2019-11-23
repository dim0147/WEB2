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
            //  COMPILE content
            $content = $this->compileContent($content);
            if(!$content){  //  If compile fail
                setHTTPCode(500, "Error while compile view, File path: $renderFile");
                exit;
            }
            echo $content;
        }

        public function compileContent($content){
            //  Validate  css
            if(strpos($content, "<@yield css>") !== FALSE)
                $content = $this->cssCompiler($content);
            if(strpos($content, "[css]") !== FALSE || strpos($content, "[\css]") !== FALSE)
                die("Require <@yield css> to use [css] tag!");

            //  Validate  js script
            if(strpos($content, "<@yield js>") !== FALSE)
                $content = $this->scriptCompiler($content);
            if(strpos($content, "[js]") !== FALSE || strpos($content, "[\js]") !== FALSE)
                die("Require <@yield js> to use [js] tag!");
            return $content;
        }


        public function cssCompiler($content){
            $linkCss = getStringBetween($content, '[css]', '[/css]');
            if($linkCss){ //  If have addition css
                $content = $this->compileCss($content, $linkCss); //  Compile css
            }
            else{ //  If don't addition css
                $content = deleteString($content, '<@yield css>');  //  Remove Hold place field
                if(!$content)    //  If error while remove hold place field
                    die("Error while remove Hold place field <@yield css> reference!");
            }
            return $content;
        }

        public function compileCss($content, $linkCss){
                //  Hold place field
            $holdPlaced = "<@yield css>";
                //  regexp to get link css
            $regexp = '/<link rel[^\r\n]*>/m';
            preg_match_all($regexp, $linkCss, $matches, PREG_SET_ORDER, 0);
                //  If don't have any link css
            if(count($matches) < 1)
                die("Error while regexp link css, Matches below 1 while deracle [css][/css]!");
                
                foreach($matches as $value){    //  Loop through links css and insert it on hold place field
                    $content = insertString($value[0], $holdPlaced, $content);
                }
                $content = deleteString($content, '[css]', '[/css]');   //  Remove reference
                if($content === false) 
                    die("Error while Remove [css][/css] reference");

                $content = deleteString($content, '<@yield css>');  //  Remove Hold place field
                if($content === false)  
                    die("Error while Remove Hold place field <@yield css> reference!");
                // Success compile css
                return $content;

        }

        public function scriptCompiler($content){
            $linkScript = getStringBetween($content, '[js]', '[/js]');
            if($linkScript)     //  If have js tag
                $content = $this->compileScript($content, $linkScript);
            else{   //  If not have js tag
                $content = deleteString($content, "<@yield js>");
                if($content === FALSE)
                    die("Error while remove hold field <@yield js> reference!");
            }
            return $content;
        }

        public function compileScript($content, $linkScript){
            $holdPlaced = "<@yield js>";
            $regexp = '/<script[^\r\n]*<\/script>/m';
            preg_match_all($regexp, $linkScript, $matches,PREG_SET_ORDER, 0);
            if(count($matches) < 1)
                die("Error while regexp script, Matches below 1 while deracled [js][/js]!");
            foreach($matches as $val){
                $content = insertString($val[0], $holdPlaced, $content);
            }
            $content = deleteString($content, '[js]', '[/js]');
            if($content === FALSE)
                die("Error while remove [js][/js] tag!");
            $content = deleteString($content, $holdPlaced);
            if($content === FALSE)
                die("Error while remove field hold place <@yield js> reference!");
            return $content;

        }
        


    }