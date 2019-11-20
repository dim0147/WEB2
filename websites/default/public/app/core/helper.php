<?php 

    /**
     *  Render View, optional render header, footer
     *  @param $file (file name to render)
     *  @param $header (true render header)
     *  @param $footer (true render footer)
     */
    function render($file, $header = true, $footer = true){
        if(!file_exists($file))
            die("Can't find file to render!, Error file: " . $file);
        require $file;
    }
    /**
 * Set HTTP Status Header
 *
 * @access	public
 * @param	int 	the status code
 * @param	string	
 * @return	void
 */
function setHTTPCode($code, $strStatus = '')
{
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
    echo $strStatus;
}