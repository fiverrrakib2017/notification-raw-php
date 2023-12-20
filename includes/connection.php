<?php
    /**
     * Company : Nemosofts
     * Detailed : Software Development Company in Sri Lanka
     * Developer : Thivakaran
     * Contact : thivakaran829@gmail.com
     * Contact : nemosofts@gmail.com
     * Website : https://nemosofts.com
     */

    error_reporting(0);
 		 ob_start();
    session_start();
 
 	header("Content-Type: text/html;charset=UTF-8");
	
    if($_SERVER['HTTP_HOST']=="localhost"){

		//local  
		DEFINE ('DB_USER', 'root');
		DEFINE ('DB_PASSWORD', '');
		DEFINE ('DB_HOST', 'localhost'); //host name depends on server
		DEFINE ('DB_NAME', 'azacodes_BoomVPN');
	
	}else{
	
		//local live 
		DEFINE ('DB_USER', 'azacodes_root');
		DEFINE ('DB_PASSWORD', 'Aws045045');
		DEFINE ('DB_HOST', 'localhost:3306'); //host name depends on server
		DEFINE ('DB_NAME', 'azacodes_BoomVPN');
	}
	
	$mysqli =mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

	if ($mysqli->connect_errno) {
    	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}

	mysqli_query($mysqli,"SET NAMES 'utf8'");	 

	$setting_qry="SELECT * FROM tbl_settings where id='1'";
    $setting_result=mysqli_query($mysqli,$setting_qry);
    $settings_details=mysqli_fetch_assoc($setting_result);
    
    $setting_qry2="SELECT * FROM payment_settings where id='1'";
    $setting_result2=mysqli_query($mysqli,$setting_qry2);
    $settings_details2=mysqli_fetch_assoc($setting_result2);

    define("APP_NAME",$settings_details['app_name']);
    define("APP_LOGO",$settings_details['app_logo']);
    define("DARK",$settings_details['status']);
    define("AUTO_POST",$settings_details['auto_post']);
    define("ONESIGNAL_APP_ID",$settings_details['onesignal_app_id']);
    define("ONESIGNAL_REST_KEY",$settings_details['onesignal_rest_key']);
    define("APP_STATUS",$settings_details['envato_purchased_status']);
    define("CURRENCY",$settings_details['currency_code']);
    define("CURRENCY_PAY",$settings_details2['currency_code']);
    define("STRIPE_SECRET_KEY",$settings_details2['stripe_secret_key']);

    if(isset($_SESSION['id'])){
    	$profile_qry="SELECT * FROM tbl_admin where id='".$_SESSION['id']."'";
	    $profile_result=mysqli_query($mysqli,$profile_qry);
	    $profile_details=mysqli_fetch_assoc($profile_result);
	    define("PROFILE_IMG",$profile_details['image']);
    }
    
    $license_filename="includes/.lic";
    $config_file_default= "dist/function.lic";
	$activate="activate/index.php";
	$install="install/index.php";
	$filename_data="includes/.lic";
	$Item_ID="30287878";
    $config_file_name= "speed_api.php"; 
?> 
	 
 