<?php 
/**
* Company : AzaCodes.com +923030839837
* Detailed : Software Development Company in Sri Lanka
* Developer : Thivakaran
* Contact : thivakaran829@gmail.com
* Contact : nemosofts@gmail.com
* Website : https://nemosofts.com
*/

    function adminUser($username, $password){
        global $mysqli;
    
        $sql = "SELECT id,username FROM tbl_admin where username = '".$username."' and password = '".md5($password)."'";       
        $result = mysqli_query($mysqli,$sql);
        $num_rows = mysqli_num_rows($result);
         
        if ($num_rows > 0){
            while ($row = mysqli_fetch_array($result)){
                echo $_SESSION['ADMIN_ID'] = $row['id'];
                            echo $_SESSION['ADMIN_USERNAME'] = $row['username'];
                                          
            return true; 
            }
        }
    }

    # Insert Data 
    function Insert($table, $data){
        global $mysqli;
        //print_r($data);
    
        $fields = array_keys($data);
        $values = array_map(array($mysqli, 'real_escape_string'), array_values($data));
    
        //echo "INSERT INTO $table(".implode(",",$fields).") VALUES ('".implode("','", $values )."');";
        //exit;  
        mysqli_query($mysqli, "INSERT INTO $table(" . implode(",", $fields) . ") VALUES ('" . implode("','", $values) . "');") or die(mysqli_error($mysqli));
    }

    // Update Data, Where clause is left optional
    function Update($table_name, $form_data, $where_clause = ''){
        global $mysqli;
        // check for optional where clause
        $whereSQL = '';
        if (!empty($where_clause)) {
            // check to see if the 'where' keyword exists
            if (substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE') {
                // not found, add key word
                $whereSQL = " WHERE " . $where_clause;
            } else {
                $whereSQL = " " . trim($where_clause);
            }
        }
        // start the actual SQL statement
        $sql = "UPDATE " . $table_name . " SET ";
    
        // loop and build the column /
        $sets = array();
        foreach ($form_data as $column => $value) {
            $sets[] = "`" . $column . "` = '" . $value . "'";
        }
        $sql .= implode(', ', $sets);
    
        // append the where statement
        $sql .= $whereSQL;
    
        // run and return the query result
        return mysqli_query($mysqli, $sql);
    }

    //Delete Data, the where clause is left optional incase the user wants to delete every row!
    function Delete($table_name, $where_clause = ''){
        global $mysqli;
        // check for optional where clause
        $whereSQL = '';
        if (!empty($where_clause)) {
            // check to see if the 'where' keyword exists
            if (substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE') {
                // not found, add keyword
                $whereSQL = " WHERE " . $where_clause;
            } else {
                $whereSQL = " " . trim($where_clause);
            }
        }
        // build the query
        $sql = "DELETE FROM " . $table_name . $whereSQL;
    
        // run and return the query result resource
        return mysqli_query($mysqli, $sql);
    }

    function calculate_time_span($post_time, $flag = false){
        if ($post_time != '') {
            $seconds = time() - $post_time;
            $year = floor($seconds / 31556926);
            $months = floor($seconds / 2629743);
            $week = floor($seconds / 604800);
            $day = floor($seconds / 86400);
            $hours = floor($seconds / 3600);
            $mins = floor(($seconds - ($hours * 3600)) / 60);
            $secs = floor($seconds % 60);
    
            if ($seconds < 60) $time = $secs . " sec ago";
            else if ($seconds < 3600) $time = ($mins == 1) ? $mins . " min ago" : $mins . " mins ago";
            else if ($seconds < 86400) $time = ($hours == 1) ? $hours . " hour ago" : $hours . " hours ago";
            else if ($seconds < 604800) $time = ($day == 1) ? $day . " day ago" : $day . " days ago";
            else if ($seconds < 2629743) $time = ($week == 1) ? $week . " week ago" : $week . " weeks ago";
            else if ($seconds < 31556926) $time = ($months == 1) ? $months . " month ago" : $months . " months ago";
            else $time = ($year == 1) ? $year . " year ago" : $year . " years ago";
    
            if ($flag) {
                if ($day > 1) {
                    $time = date('d-m-Y', $post_time);
                }
            }
            return $time;
        } else {
            return 'not available';
        }
    }
    
    function calculate_end_days($days, $endDay){
        
        $date_plus_days = new DateTime($days);
    
        $date_plus_days->modify("+$endDay days");
        
        return $date_plus_days->format("Y-m-d");
    
    }

    function verify_envato_purchase_code($product_code){
        $url = "https://api.envato.com/v3/market/author/sale?code=" . $product_code;
        $curl = curl_init($url);
        $personal_token = "1Mi77qTS4nSI0kBlkwDD9ljTFY4srKzR";
        $header = array();
        $header[] = 'Authorization: Bearer ' . $personal_token;
        $header[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:41.0) Gecko/20100101 Firefox/41.0';
        $header[] = 'timeout: 20';
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $envatoRes = curl_exec($curl);
        curl_close($curl);
        $envatoRes = json_decode($envatoRes);
        return $envatoRes;
    }
    
    function verify_data_on_server($envato_type,$envato_product_id,$envato_buyer_name,$envato_purchase_code,$nemosofts_key,$envato_license_type,$buyer_admin_url,$package_name){  
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.thivapro.com/verified_user.php");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query(array('envato_type' => $envato_type,'envato_product_id' => $envato_product_id,'envato_buyer_name' => $envato_buyer_name,'envato_purchase_code' => $envato_purchase_code,'nemosofts_key' => $nemosofts_key,'envato_license_type' => $envato_license_type,'buyer_admin_url' => $buyer_admin_url,'package_name' => $package_name)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close ($ch);
       if ($server_output == "success") { 
           echo "done1"; 
       } else {
             echo "fail!";
       }     
    }

    function cleanInput($inputText){
        return htmlentities(addslashes(trim($inputText)));
    }


    //Image compress
    function compress_image($source_url, $destination_url, $quality){

        $info = getimagesize($source_url);
        $exif = exif_read_data($source_url);
        
        if ($info['mime'] == 'image/jpeg'){
            $imageResource = imagecreatefromjpeg($source_url);
        }else if ($info['mime'] == 'image/gif'){
            $imageResource = imagecreatefromgif($source_url);
        }else if ($info['mime'] == 'image/png'){
            $imageResource = imagecreatefrompng($source_url);
        }else{
            $imageResource = imagecreatefromjpeg($source_url);
        }
            
        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                $image = imagerotate($imageResource, 180, 0);
                break;
                case 6:
                $image = imagerotate($imageResource, -90, 0);
                break;
                case 8:
                $image = imagerotate($imageResource, 90, 0);
                break;
                default:
                $image = $imageResource;
            }
        }else{
            $image = $imageResource;
        }
        
        imagejpeg($image, $destination_url, $quality);
        return $destination_url;
    }
       

    
    //Create Thumb Image
    function create_thumb_image($target_folder = '', $thumb_folder = '', $thumb_width = '', $thumb_height = ''){
        //folder path setup
        $target_path = $target_folder;
        $thumb_path = $thumb_folder;
    
        $thumbnail = $thumb_path;
        $upload_image = $target_path;
    
        list($width, $height) = getimagesize($upload_image);
        $thumb_create = imagecreatetruecolor($thumb_width, $thumb_height);
        switch ($file_ext) {
            case 'jpg':
                $source = imagecreatefromjpeg($upload_image);
                break;
            case 'jpeg':
                $source = imagecreatefromjpeg($upload_image);
                break;
            case 'png':
                $source = imagecreatefrompng($upload_image);
                break;
            case 'gif':
                $source = imagecreatefromgif($upload_image);
                break;
            default:
                $source = imagecreatefromjpeg($upload_image);
        }
        imagecopyresized($thumb_create, $source, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
        switch ($file_ext) {
            case 'jpg' || 'jpeg':
                imagejpeg($thumb_create, $thumbnail, 80);
                break;
            case 'png':
                imagepng($thumb_create, $thumbnail, 80);
                break;
            case 'gif':
                imagegif($thumb_create, $thumbnail, 80);
                break;
            default:
                imagejpeg($thumb_create, $thumbnail, 80);
        }
    }

       function checkSignSalt($data_info){
        $key="nemosofts";
        $data_json = $data_info;
        $client_msg = array();
	    $client_msg['1'] = "Envato username or purchase code is wrong!";
	    $client_msg['2'] ="Api File Missing!";
	    $client_msg['3'] ="Invalid Package Name";
        $data_arr = json_decode(urldecode(base64_decode($data_json)), true);
        if($data_arr['package_name']==PACKAGE_NAME){
            if (!file_exists('speed_api.php')){
                $set['AzaCodes.com +923030839837'][] = array("success" => -1, "MSG" => $client_msg['2']);   
                header( 'Content-Type: application/json; charset=utf-8' );
                echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                exit(); 
            }else if (APP_STATUS == 0){
                $set['AzaCodes.com +923030839837'][] = array("success" => -1, "MSG" => $client_msg['1']);   
                header( 'Content-Type: application/json; charset=utf-8' );
                echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                exit(); 
            }else if (file_exists('.lic')){
                $set['AzaCodes.com +923030839837'][] = array("success" => -1, "MSG" => $client_msg['1']);   
                header( 'Content-Type: application/json; charset=utf-8' );
                echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                exit(); 
            }
        } else{
            $set['AzaCodes.com +923030839837'][] = array("success" => -1, "MSG" => $client_msg['3']);   
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            exit();  
        }
        return $data_arr;
    }
 
    // Get base url
    function getBaseUrl($array = false){
        $protocol = "http";
        $host = "";
        $port = "";
        $dir = "";
    
        // Get protocol
        if (array_key_exists("HTTPS", $_SERVER) && $_SERVER["HTTPS"] != "") {
            if ($_SERVER["HTTPS"] == "on") {
                $protocol = "https";
            } else {
                $protocol = "http";
            }
        } elseif (array_key_exists("REQUEST_SCHEME", $_SERVER) && $_SERVER["REQUEST_SCHEME"] != "") {
            $protocol = $_SERVER["REQUEST_SCHEME"];
        }
    
        // Get host
        if (array_key_exists("HTTP_X_FORWARDED_HOST", $_SERVER) && $_SERVER["HTTP_X_FORWARDED_HOST"] != "") {
            $host = trim(end(explode(',', $_SERVER["HTTP_X_FORWARDED_HOST"])));
        } elseif (array_key_exists("SERVER_NAME", $_SERVER) && $_SERVER["SERVER_NAME"] != "") {
            $host = $_SERVER["SERVER_NAME"];
        } elseif (array_key_exists("HTTP_HOST", $_SERVER) && $_SERVER["HTTP_HOST"] != "") {
            $host = $_SERVER["HTTP_HOST"];
        } elseif (array_key_exists("SERVER_ADDR", $_SERVER) && $_SERVER["SERVER_ADDR"] != "") {
            $host = $_SERVER["SERVER_ADDR"];
        }
        //elseif(array_key_exists("SSL_TLS_SNI", $_SERVER) && $_SERVER["SSL_TLS_SNI"] != "") { $host = $_SERVER["SSL_TLS_SNI"]; }
    
        // Get port
        if (array_key_exists("SERVER_PORT", $_SERVER) && $_SERVER["SERVER_PORT"] != "") {
            $port = $_SERVER["SERVER_PORT"];
        } elseif (stripos($host, ":") !== false) {
            $port = substr($host, (stripos($host, ":") + 1));
        }
        // Remove port from host
        $host = preg_replace("/:\d+$/", "", $host);
    
        // Get dir
        if (array_key_exists("SCRIPT_NAME", $_SERVER) && $_SERVER["SCRIPT_NAME"] != "") {
            $dir = $_SERVER["SCRIPT_NAME"];
        } elseif (array_key_exists("PHP_SELF", $_SERVER) && $_SERVER["PHP_SELF"] != "") {
            $dir = $_SERVER["PHP_SELF"];
        } elseif (array_key_exists("REQUEST_URI", $_SERVER) && $_SERVER["REQUEST_URI"] != "") {
            $dir = $_SERVER["REQUEST_URI"];
        }
        // Shorten to main dir
        if (stripos($dir, "/") !== false) {
            $dir = substr($dir, 0, (strripos($dir, "/") + 1));
        }
    
        // Create return value
        if (!$array) {
            if ($port == "80" || $port == "443" || $port == "") {
                $port = "";
            } else {
                $port = ":" . $port;
            }
            return htmlspecialchars($protocol . "://" . $host . $port . $dir, ENT_QUOTES);
        } else {
            return ["protocol" => $protocol, "host" => $host, "port" => $port, "dir" => $dir];
        }
       
    }

?>