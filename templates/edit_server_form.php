<?php

	include_once('functions.php'); 
	$new_error = false;
    $error = array();

    if(isset($_GET['id'])){
        $ID = $_GET['id'];
    }else{
        $ID = "";
    }

	if(isset($_POST['btnEdit'])) {
        echo  
        "<script> 
            $(document).ready(function () {
                $('#btnEdit').attr('disabled', true);		
            });
        </script>";
        
		$serverName     = $_POST['serverName'];
		$country_code   = $_POST['country_selector_code'];
		$ovpnConfig     = $_POST['ovpnConfig'];
        $isPaid         = $_POST['isPaid']; //0=Free, 1=Paid
        $active         = $_POST['status']; //1=Active, 0=InActive

        // Remove Comment that starts with # sign
        $exploded =  explode("\n", $ovpnConfig);
        
        $ovpnConfig = '';
        for($i = 0; $i < sizeof($exploded)-1; $i++) {
            if(strcmp($exploded[$i][0], "#") != 0) {
                $ovpnConfig .= $exploded[$i] . "\n";
            }
        }

		if (empty($error)) {
            $flagURL = $country_code ;//. ".png";

            $date = date('Y-m-d H:i:s', time());

            $sql = "UPDATE tbl_servers SET serverName = ?, flagURL = ?, ovpnConfig = ?, isPaid = ?, active = ?, updatedAt = ?
                        WHERE id = ?";

            $stmt = $connect->stmt_init();
            $stmt->prepare($sql);
            $stmt->bind_param('sssssss', $serverName, $flagURL, $ovpnConfig, $isPaid, $active, $date, $ID);
            $stmt->execute();

            $stmt_result = $stmt->store_result();
            $stmt->close();
            
            if($stmt_result) {
                $error['add_form'] = "<div class='card-panel green lighten-4'>
                                                <span class='green-text text-darken-2'>
                                                        Update added successfully.
                                                </span>
                                            </div>";
            } else {
                $error['add_form'] = "<div class='card-panel red lighten-4'>
                                                <span class='red-text text-darken-2'>
                                                    Insertion failed.
                                                </span>
                                            </div>";
            }
			
		}
        echo "<script> 
				$(document).ready(function () {
					$('#btnEdit').attr('disabled', false);		
				});
			</script>";
	}
    $data = array();
    
    $sql_query = "SELECT * FROM tbl_servers where id = $ID";
    $res = mysqli_query($connect, $sql_query);
    $data = mysqli_fetch_array($res);
    $selectedContryCode = $data['flagURL'];// str_replace(".png", "",$data['flagURL']);
    //echo $selectedContryCode;die;
?>

<!-- START CONTENT -->
<section id="content">

    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class=" grey lighten-3">
        <div class="container">
            <div class="row">
                <div class="col s12 m12 l12">
                    <h5 class="breadcrumbs-title">Edit server</h5>
                    <ol class="breadcrumb">
                        <li><a href="dashboard.php" class="deep-orange-text">Dashboard</a></li>
                        <!-- <li><a href="users.php" class="deep-orange-text">Manage Server</a></li> -->
                        <li><a class="active">Edit Server</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs end-->

   <!--start container-->
    <div class="container">
        <div class="section">
            <div class="row">
                <div class="col s12 m12 l12">
                    <div class="card-panel borderTop">
                        <div class="row">
                            <form method="post" class="col s12" id="form-validation">
                                <div class="row">
                                    <div class="input-field col s12">

                                        <?php echo $new_error ? '<div class="card-panel red lighten-4" role="alert"><span class="red-text text-darken-2">'. implode('<br>', $new_error) . '</span></div>' : '';?>

                                        <?php echo isset($error['add_form']) ? $error['add_form'] : ''; ?>

                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input type="text" name="serverName" id="serverName"  value="<?php echo $data['serverName']; ?>" required maxlength="20"/>
                                                <label for="serverName">Server Name</label>
                                                <?php echo isset($error['serverName']) ? $error['serverName'] : '';?>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="input-field col s12">
                                                <label for="country">Choose Flag (or Type Country)</label>
                                                <input type="text" id="country_selector" name="country_selector">        
                                                <input type="text" id="country_selector_code" name="country_selector_code" style="display:none;" data-countrycodeinput="1" readonly="readonly" />
                                            </div>
                                        </div><br/>

                                        <div class="row">
                                            <div class="input-field col s12">
                                                <textarea name="ovpnConfig" id="ovpnConfig" class="materialize-textarea" required><?php echo $data['ovpnConfig']; ?></textarea>
                                                <label for="ovpnConfig">V2ray Configuration</label>
                                                <?php echo isset($error['ovpnConfig']) ? $error['ovpnConfig'] : '';?>
                                            </div>
                                        </div>

                                        <div class="row">
											<div class="input-field col s12 m6">
                                                <select name="isPaid">
                                                    <option value="0" <?php if($data['isPaid'] == 0) echo "selected"; else echo ""; ?> ><?php echo FREE; ?></option>
												    <option value="1" <?php if($data['isPaid'] == 1) echo "selected"; else echo ""; ?> ><?php echo PAID; ?></option>
                                                </select>
                                                <label>Server Type</label>
                                            </div>
                                            <div class="input-field col s12 m6">
                                                <select name="status">
                                                <option value="1" <?php if($data['active'] == 1) echo "selected"; else echo ""; ?> ><?php echo ACTIVE; ?></option>
												<option value="0" <?php if($data['active'] == 0) echo "selected"; else echo ""; ?> ><?php echo INACTIVE; ?></option>
                                                </select>
                                                <label>Status</label>
                                            </div>
										</div>

                                        <div class="row">
                                            <div class="input-field col s12 m12 l12">
                                                <button class="btn deep-orange waves-effect waves-light left" type="submit" name="btnEdit" id="btnEdit">Update
                                                    <i class="mdi-content-send right"></i>
                                                </button>
                                            </div>
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
</section>
