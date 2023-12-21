<?php

    //Image Folder Name Configuration
    if (!defined('UPLOAD')) {
        define('UPLOAD', 'upload');
        define('UPLOAD_NOTIFICATION', 'upload/notification/');
        define('UPLOAD_APP_IMAGE', 'upload/app_image/');
        define('FLAG_IMG', 'assets/flags/');
        define('PNG', '.png');
        define('FREE', 'Free Server');
        define('PAID', 'Paid Server');
        define('TYPE_FREE', '0');
        define('TYPE_PAID', '1');
        define('ACTIVE', 'Active');
        define('INACTIVE', 'Inactive');
        define('TRUE_AD', 'True');
        define('FALSE_AD', 'False');
        define('ENABLED', 'Enabled');
        define('DISABLED', 'Disabled');
        define('YES_GRID', 'Yes');
        define('NO_GRID', 'No');
        define('PLAY_STORE', 'Playstore');
        define('SERVER_URL', 'Server URL');
    }

    if(!defined('MSG')){
        define('MSG', "Message");
        define('MSG_NO_METHOD_FOUND', "Oops, no method found!");
        define('MSG_API_KEY_INCORRECT',"Oops, API Key is incorrect!");
        define('MSG_API_KEY_REQUIRED',"Oops, API Key is required!");
        define('MSG_REQUIRED_PARAMS', "Required parameter is missing!");
        
        define('MSG_USERNAME_EMPTY', "Username must be filled!");
        define('MSG_PASSWORD_EMTPY', "Password must be filled!");
        define('MSG_INVALID_USER_PASS', "Invalid username or password!");
        define('MSG_LOGIN_SUCCESS', "Login successfully");
        define('MSG_REGISTER_SUCCESS', "User registered successfully.");
        define('MSG_RECORD_ALREADY_EXIST', "Oops, Record already exist!");
        define('MSG_RECORD_NOT_FOUND', "Oops, Record not found!");
        define('MSG_RECORD_FOUND', "Record found successfully!");
        define('MSG_CHANGE_PASSWORD', "Password changed successfully");
        define('MSG_INVALID_PASSWORD', "Invalid current password");
        
        define('SUCCESS', "0");
        define('FAIL', "1");

        define('APP_NAME','Fly2ray');
        define('COPYRIGHT', '© 2019-<script>document.write(new Date().getFullYear())</script>');
        define('ALL_RIGHTS_RESERVED', 'All rights reserved');
        define('VERSION', 'Version 1.0');
        define('DEVELOPMENT_BY' , 'Developed by: ');
        define('COMPANY_NAME' , 'CodeWithTamim');

        //You can change default username/password
        define('DEF_USERNAME', 'vpn');
        define('DEF_PASSWORD', 'vpn');
        
        //Do not change both direction value, otherwise panel not work properly
        define('LTR_DIRECTION','LTR');
        define('RTL_DIRECTION','RTL');
        

        //-----------------------------------------------------------------------
        define('DELETE_TITLE', 'Cancelled');
        define('DELETE_CANCELLED', 'Your item is safe :)');        
    }
   		
    //LOCAL database configuration
    $host       = "localhost";
    $user       = "root";
    $pass       = "";
    $database   = "codewithtamim_v2ray";
  
    $connect = new mysqli($host, $user, $pass, $database);

    if (!$connect) {
        die ("connection failed: " . mysqli_connect_error());
    } else {
        $connect->set_charset('utf8');
    }

    $sql_query = "SELECT app_direction FROM tbl_settings";
    $value = mysqli_query($connect, $sql_query);
    $value = mysqli_fetch_array($value);
    $valueLTR = $value['app_direction'];


    $setting_qry="SELECT * FROM tbl_settings where id='1'";
    $setting_result=mysqli_query($connect,$setting_qry);
    $settings_details=mysqli_fetch_assoc($setting_result);


    // $settings_details['onesignal_app_id'];
    // $settings_details['onesignal_rest_key'];
    




?>