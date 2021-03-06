<?php 

    /**
     *  Render View, optional render header, footer
     *  @param $file (file name to render)
     *  @param $header (true render header)
     *  @param $footer (true render footer)
     */
    function render($fileRender, $header = true, $menu = true, $footer = true, $data = NULL){
		$headerFile = __DIR__ . '/../Views/header.php';
		$menuFile = __DIR__ . '/../Views/menu.php';
		$footerFile = __DIR__ . '/../Views/footer.php';
		
		//	Check if header and footer file is exist
		if(!file_exists($headerFile) || !file_exists($footerFile))
		{
			setHTTPCode(500, "Missing header or footer template!");
			exit();
		}
		//	Check if file render is not exist
		if(!file_exists($fileRender))
			die("File render not exist!, Error file: " . $fileRender);
		
		if($header)
			require($headerFile);
		
		if($menu)
			require($menuFile);

		require($fileRender);

		if($footer)
			require($footerFile);
			
	}
	
		/**
		 * Set HTTP Status Header
		 *
		 * @access	public
		 * @param	int 	the status code
		 * @param	string	
		 * @return	void
		 */
	function setHTTPCode($code, $strStatus = '', $print = TRUE){
		$statusCode = array(
							200	=> 'OK',
							201	=> 'Created',
							202	=> 'Accepted',
							
							400	=> 'Bad Request',
							401	=> 'Unauthorized',
							403	=> 'Forbidden',
							404	=> 'Not Found',
							405	=> 'Method Not Allowed',
							406	=> 'Not Acceptable',
							408	=> 'Request Timeout',
							414	=> 'Request-URI Too Long',
							415	=> 'Unsupported Media Type',

							500	=> 'Internal Server Error',
							501	=> 'Not Implemented',
						);

		if ($code == '' OR !is_numeric($code))
		{
			die('Incorrect status code!');
		}

		if (isset($statusCode[$code]) && $strStatus == ''){				
			$strStatus = $statusCode[$code];
		}
		
		if ($strStatus === NULL){
			die('Please insert stringStatus or use correct status code support!');
		}
		
		http_response_code($code);
		if($print)
			echo $strStatus;
	}

	function getUrlWeb(){
		echo URL_WEB;
	}


	function insertString($strInsert, $strFindToIns, $context){
		$po = strpos($context, $strFindToIns);
		$poToInsert = $po + strlen($strFindToIns);
		$newContext = substr_replace($context, $strInsert, $poToInsert, 0);
		return $newContext;
	}

	//	Get string between two word
	function getStringBetween($string, $start, $end){
		$ini = strpos($string, $start);
		if(!$ini)
			return false;
		$actualPo = strlen($start) + $ini;
		$endP = strpos($string, $end);
		if(!$endP)
			return false;
		$length = $endP - $actualPo;
		return substr($string, $actualPo, $length);
	}

	//	Delete string between two word
	function deleteString($string, $start, $end = NULL){
		if($end === NULL){
			return str_replace($start, '' ,$string);
		}
		$ini = strpos($string, $start);
		if(!$ini)
			return false;
		$content = getStringBetween($string, $start, $end);
		$lengthEnd = strlen($start) + strlen($content) + strlen($end);
		return substr_replace($string, '', $ini, $lengthEnd);
	}

	function createImgName($nameImg, $length = 5){
			$characters = 'tuvwxyzANOlmnopqrsPQRSTU89abcdefghi01234567jkBCDEFGHIJKLMVWXYZ';
			$rand = '';
			$newNameImg = '';
			do{
				for ($i = 0; $i < $length; $i++){
					$lengthSub = strlen($characters) -1;
					$element = rand(0, $lengthSub);
					$rand .= $characters[$element];
				}
				$newNameImg = $rand . '_' . $nameImg;
			}while(file_exists(PATH_IMAGE_UPLOAD . '/' . $newNameImg));
			return $newNameImg;
	}

	function calculateTime($start, $end, $format = '%m months %d days %H hours %i minutes'){
		$start = new DateTime($start);
		$end = new DateTime($end);
		$elapsed = $start->diff($end);
		return $elapsed->format($format);
	}

	function deleteImage($image){
		if(file_exists(__DIR__ . '/../../public/images/' .$image)){
			unlink(__DIR__ . '/../../public/images/' . $image);
			return true;
		}
		return FALSE;
	}

	function getCurrentURL(){
		return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	}

	function goUserLogin(){
		echo ("<br>You will be redirect to user login after 3 seconds!<br> <a href=". URL_WEB ."user/login>Not redirect?Click here for redirect!</a>");
		header("Refresh:3; url=" . URL_WEB . "user/login");
		exit;
	}

	function goAdminLogin(){
		echo ("<br>You will be redirect to admin login after 3 seconds!<br> <a href=". URL_WEB ."admin/login>Not redirect?Click here for redirect!</a>");
		header("Refresh:3; url=" . URL_WEB . "admin/login");
		exit;
	}

	function goOldUrl(){
		if(empty($_SESSION['old_url'])){
			echo ("<br>You will be redirect to home page after 3 seconds!<br> <a href=". URL_WEB .">Not redirect?Click here for redirect!</a>");
			header("Refresh:3; url=" . URL_WEB);
			exit;
		}
			$old_url = $_SESSION['old_url'];
			echo ("<br>You will be redirect after 3 seconds!<br> <a href=". $old_url . ">Not redirect?Click here for redirect!</a>");
			header("Refresh:3; url=" . $old_url);
			$_SESSION['old_url'] = NULL;
			exit;
	}

	function goUrl($url){
		echo ("<br>You will be redirect after 3 seconds!<br> <a href=". URL_WEB . $url .">Not redirect?Click here for redirect!</a>");
		header("Refresh:3; url=" . URL_WEB . $url);
		exit;
	}