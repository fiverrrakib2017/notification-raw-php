<?php 
    /**
    * Company : Nemosofts
    * Detailed : Software Development Company in Sri Lanka
    * Developer : Thivakaran
    * Contact : info.nemosofts@gmail.com
    * Website : https://nemosofts.com
    */
    


    $page_title="Send Notification";
    require("includes/function.php");
    require("includes/connection.php");

   if(isset($_POST['submit'])){

    if($_FILES['big_picture']['name']!=""){   

        $big_picture=rand(0,99999)."_".$_FILES['big_picture']['name'];
        $tpath2='images/'.$big_picture;
        move_uploaded_file($_FILES["big_picture"]["tmp_name"], $tpath2);

        if( isset($_SERVER['HTTPS'] ) ) {  

          $file_path = 'https://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/images/'.$big_picture;
        }else{
          $file_path = 'http://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/images/'.$big_picture;
        }

        $content = array(
                         "en" => $_POST['notification_msg']                                                 
                         );

        $fields = array(
                        'app_id' => ONESIGNAL_APP_ID,
                        'included_segments' => array('All'),                                            
                        'data' => array("foo" => "bar","cat_id"=>$_POST['cat_id'],"cat_name"=>$cat_name,"artist_id"=>$_POST['artist_id'],"artist_name"=>$artist_name,"album_id"=>$_POST['album_id'],"album_name"=>$album_name,"song_id"=>$_POST['song_id'],"song_name"=>$song_name,"external_link"=>$external_link),
                        'headings'=> array("en" => $_POST['notification_title']),
                        'contents' => $content,
                        'big_picture' =>$file_path                    
                        );

        $fields = json_encode($fields);
        print("\nJSON sent:\n");
        print($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                   'Authorization: Basic '.ONESIGNAL_REST_KEY));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        
    }else{
 
        $content = array(
                         "en" => $_POST['notification_msg']
                          );

        $fields = array(
                        'app_id' => ONESIGNAL_APP_ID,
                        'included_segments' => array('All'),                                      
                        'data' => array("foo" => "bar","cat_id"=>$_POST['cat_id'],"cat_name"=>$cat_name,"artist_id"=>$_POST['artist_id'],"artist_name"=>$artist_name,"album_id"=>$_POST['album_id'],"album_name"=>$album_name,"song_id"=>$_POST['song_id'],"song_name"=>$song_name,"external_link"=>$external_link),
                        'headings'=> array("en" => $_POST['notification_title']),
                        'contents' => $content
                        );

        $fields = json_encode($fields);
        print("\nJSON sent:\n");
        print($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                   'Authorization: Basic '.ONESIGNAL_REST_KEY));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        
        
        
        curl_close($ch);
    }
        
        $_SESSION['msg']="16";
        $_SESSION['class'] = "success";
        header( "Location:new_notification.php");
        exit; 
     
  }
  
  
   else if(isset($_POST['notification_submit'])) {
    
        
         $onesignal_app_id = trim($_POST['onesignal_app_id']);

          $onesignal_rest_key = trim($_POST['onesignal_rest_key']);
      
         $query = "UPDATE tbl_settings SET onesignal_app_id='$onesignal_app_id', onesignal_rest_key='$onesignal_rest_key' WHERE id=1";
        $result = $connect->query($query);
        if ($result==true) {
             $_SESSION['class'] = "success";
            $_SESSION['msg'] = "11";
            header("Location:new_notification.php");
            exit;
        }else{
            echo "something else ";
        }
       
    }
   

?>
<!DOCTYPE html>
<html lang="en" id="myStream">
<script>
  document.getElementById("myStream").dir = 'LTR'; 
</script>

<head>
  <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <title>Fly2ray</title>

    <!-- Favicons-->
    <link rel="icon" href="assets/images/favicon/favicon-32x32.png" sizes="32x32">
    <!-- Favicons-->
    <link rel="apple-touch-icon-precomposed" href="assets/images/favicon/apple-touch-icon-152x152.png">
    <!-- For iPhone -->
    <meta name="msapplication-TileColor" content="#00bcd4">
    <meta name="msapplication-TileImage" content="assets/images/favicon/mstile-144x144.png">
    <!-- For Windows Phone -->

    <!-- Sweet Alert -->
    <link rel="stylesheet" href="assets/sweet-alert/sweetalert.min.css">

    <!-- CORE CSS-->
    <link href="assets/css/app.min.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="assets/css/style.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="assets/css/sticky-footer.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="assets/css/dropify.css" type="text/css" rel="stylesheet" media="screen,projection">

    <!-- Country Selection-->
    <link href="assets/css/countrySelect.css" type="text/css" rel="stylesheet" media="screen,projection">

    <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
    <link href="assets/css/prism.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="assets/js/plugins/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet"
          media="screen,projection">

          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- datatable -->
    <link href="http://cdn.datatables.net/1.10.6/css/jquery.dataTables.min.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="assets/js/plugins/data-tables/css/jquery.dataTables.min.css" type="text/css" rel="stylesheet" media="screen,projection">
 
   

    

</head>

<body>
<!-- Start Page Loading -->
    <!--
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div> 
    -->
<!-- End Page Loading -->
<!-- //////////////////////////////////////////////////////////////////////////// -->


<!-- START HEADER -->

<!-- END HEADER -->

<!-- START MAIN -->
<div id="main">
    <!-- START WRAPPER -->
    <div class="wrapper">

        <!-- START LEFT SIDEBAR NAV-->
        
        <!-- END LEFT SIDEBAR NAV-->

        <!-- //////////////////////////////////////////////////////////////////////////// -->
        <div id="api-key" class="modal modal-fixed-footer">

            <div class="modal-content">
                <h5>Where I have to put my API Key?</h5>
                <hr>
                <ol>
                    <li>For security needed, Update <b>api_key</b> String value.</li>
                    <li>Open Android Studio Project.</li>
                    <li>Click <b>CHANGE API KEY</b> to generate new API Key.</li>
                    <li>Go to Android app > <b>build.gradle</b>, and update with your own API Key. <img src="assets/images/api_key.png" width="640px" height="313px"></li>
                </ol>
            </div>
            <div class="modal-footer">
                <a class="waves-effect waves-red btn-flat modal-action modal-close">OK, I am Understand</a>
            </div>

        </div>

        <div id="server-key" class="modal modal-fixed-footer">

            <div class="modal-content">
                <h5>Obtaining your Firebase Server API Key</h5>
                <hr>
                <p>Firebase provides Server API Key to identify your firebase app. To obtain your Server API Key, go to firebase console, select the project and go to settings, select Cloud Messaging tab and copy your Server key.</p>
                <img src="assets/images/fcm-server-key.jpg" >
            </div>
            <div class="modal-footer">
                <a class="waves-effect waves-red btn-flat modal-action modal-close">OK, I am Understand</a>
            </div>

        </div>

        <div id="modalLogout" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Logout</h5>
                </div>
                <div class="modal-body">
                    <p>Do you want to logout?</p>
                </div>
            </div>
            <div class="modal-footer modal-fixed-footer">
                <a href="#!" class="modal-action modal-close waves-effect waves-red btn-flat ">No</a>
                <a href="logout.php" class="modal-action modal-close waves-effect waves-green btn-flat ">Yes</a>
            </div>
        </div>


    <!-- START CONTENT -->
    <section id="content">

        <!--breadcrumbs start-->
        
        <!--breadcrumbs end-->

        <!--start container-->
        <div class="container">
            <div class="section">
                <div class="row">
                    <div class="col md-12">
                        <div class="card">
                            <div class="card-header">
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                  <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Notification Settings</button>
                                  </li>
                                  <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#send_notification" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Send Notification</button>
                                  </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="pills-tabContent">

                                  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                      <div class="row">
                                          <div class="row">
                                            <div class="col-md-12">
                                              <form action="" name="settings_api" method="post" class="form form-horizontal" enctype="multipart/form-data" id="api_form">
                                                <div class="section">
                                                  <div class="section-body">
                                                    <div class="form-group">
                                                      <label class="col-md-3 control-label">OneSignal App ID</label>
                                                      <div class="col-md-6">
                                                        <input type="text" name="onesignal_app_id" id="onesignal_app_id" value="<?php echo $settings_details['onesignal_app_id']; ?>" class="form-control">
                                                      </div>
                                                    </div>
                                                    <div class="form-group">
                                                      <label class="col-md-3 control-label">OneSignal Rest Key</label>
                                                      <div class="col-md-6">
                                                        <input type="text" name="onesignal_rest_key" id="onesignal_rest_key" value="<?php echo $settings_details['onesignal_rest_key']; ?>" class="form-control">
                                                      </div>
                                                    </div>
                                                    <div class="form-group">
                                                      <div class="col-md-9 col-md-offset-3">
                                                        <button type="submit" name="notification_submit" class="btn btn-primary">Save</button>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                              </form>
                                            </div>
                                          </div>
                                      </div>
                                  </div>

                                  <div class="tab-pane fade" id="send_notification" role="tabpanel" aria-labelledby="pills-profile-tab">
                                      <div class="row">
                <div class="col-md-12">
                     <form action="" name="addeditcategory" method="post" class="form form-horizontal" enctype="multipart/form-data">
               
              <div class="section">
                <div class="section-body">

                  <div class="form-group">
                    <label class="col-md-3 control-label">Title</label>
                    <div class="col-md-6">
                      <input type="text" name="notification_title" id="notification_title" class="form-control" value="" placeholder="" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Message</label>
                    <div class="col-md-6">
                        <textarea name="notification_msg" id="notification_msg" class="form-control" required></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Image<br/><p class="control-label-help"></p></label>

                    <div class="col-md-6">
                      <div class="fileupload_block mb-2">
                         <input type="file" name="big_picture" value="fileupload" accept=".png, .jpg, .JPG .PNG" onchange="fileValidation()" id="fileupload">
                        
                      </div>
                    </div>
                  </div>
                   
                  <div class="form-group mb-2">
                    
                      <button type="submit" name="submit" class="btn btn-primary">Send</button>
                  </div>
                </div>
              </div>
            </form>
                </div>
              </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>

    </section>

</section>
</div>
<!-- END WRAPPER -->

</div>
<!-- END MAIN -->
<!-- START FOOTER -->
<footer class="footer deep-orange page-topbar">
    <div class="container">
        <span class="footer1 span-padding grey-text text-lighten-4">
            Version 1.0        </span>
        
        <span class="footerbottom span-padding grey-text text-lighten-4">
            © 2019-<script>document.write(new Date().getFullYear())</script>            All rights reserved        </span>

        <span class="footer2 span-padding grey-text text-lighten-4">Developed by:  
            <b><a class="grey-text text-lighten-4" href="http://www.bytesbee.com/" target="_blank">CodeWithTamim</a></b>
        </span>
    </div>
</footer>
<!-- END FOOTER -->


<!-- ================================================
Scripts
================================================ -->

<!-- jQuery Library -->
<script type="text/javascript" src="assets/js/jquery-1.11.2.min.js"></script>
<!--materialize js-->
<script type="text/javascript" src="assets/js/app.js"></script>
<!--prism-->
<script type="text/javascript" src="assets/js/prism.js"></script>
<!--scrollbar-->
<script type="text/javascript" src="assets/js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>

<!-- data-tables -->
<script type="text/javascript" src="assets/js/plugins/data-tables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/data-tables/data-tables-script.js"></script>

<!-- chartjs -->
<script type="text/javascript" src="assets/js/plugins/chartjs/charts.min.js"></script>

<!--plugins.js - Some Specific JS codes for Plugin Settings-->
<script type="text/javascript" src="assets/js/plugins.js"></script>
<script type="text/javascript" src="assets/js/dropify.js"></script>

<!-- Sweet Alert -->
<script src="assets/sweet-alert/sweetalert.min.js" ></script>
<script src="assets/js/sweet-alert/sweet-alert-data.js" ></script>

<!-- Coutnry Selection-->
<script src="assets/js/countrySelect.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    /*Show entries on click hide*/
    $(document).ready(function() {
        $(".dropdown-content.select-dropdown li").on( "click", function() {
            var that = this;
            setTimeout(function() {
                if ($(that).parent().hasClass('active')) {
                    $(that).parent().removeClass('active');
                    $(that).parent().hide();
                }
            },0);
        });
    });
</script>


<script type="text/javascript">

  $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
    localStorage.setItem('activeTab', $(e.target).attr('href'));
    document.title = $(this).text()+" | <?=APP_NAME?>";
  });

  var activeTab = localStorage.getItem('activeTab');
  if(activeTab){
    $('.nav-tabs a[href="' + activeTab + '"]').tab('show');
  }
</script>
</body>

</html>