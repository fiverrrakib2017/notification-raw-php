<?php

	include_once('functions.php'); 

	$new_error = false;
    $error = array();

    $vpnType = TYPE_FREE;

    if(isset($_POST['vpnType'])){
        $vpnType = $_POST['vpnType'];
    }
	if (isset($_POST['btnAdd'])) {

		$serverName     = $_POST['serverName'];
		$country_code   = $_POST['country_selector_code'];
		$ovpnConfig     = $_POST['ovpnConfig'];
        $isPaid         = $_POST['isPaid']; //0=Free, 1=Paid
        $active         = $_POST['status']; //1=Active, 0=InActive

        //Remove Comment that starts with # sign
        $exploded =  explode("\n", $ovpnConfig);
        
        $ovpnConfig = '';
        for($i = 0; $i < sizeof($exploded)-1; $i++) {
            if(strcmp($exploded[$i][0], "#") != 0) {
                $ovpnConfig .= $exploded[$i] . "\n";
            }
        }

		if (empty($error)) {
            $flagURL = $country_code;// . ".png";

            $date = date('Y-m-d H:i:s', time());

            $sql = "INSERT INTO tbl_servers (serverName, flagURL, ovpnConfig, isPaid, active, createdAt, updatedAt)
                             VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = $connect->stmt_init();
            $stmt->prepare($sql);
            $stmt->bind_param('sssssss', $serverName, $flagURL, $ovpnConfig, $isPaid, $active, $date, $date);
            $stmt->execute();

            $stmt_result = $stmt->store_result();
            $stmt->close();

            if($stmt_result) {
                $error['add_form'] = "<div class='card-panel green lighten-4'>
                                                <span class='green-text text-darken-2'>
                                                        Server added successfully.
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
	}

?>

<!-- START CONTENT -->
<section id="content">

    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class=" grey lighten-3">
        <div class="container">
            <div class="row">
                <div class="col s12 m12 l12">
                    <h5 class="breadcrumbs-title">Add Server</h5>
                    <ol class="breadcrumb">
                        <li><a href="dashboard.php" class="deep-orange-text">Dashboard</a></li>
                        <li><a class="active">Add Server</a></li>
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
                                                <input type="text" name="serverName" id="serverName" required maxlength="20"/>
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
                                                <textarea name="ovpnConfig" id="ovpnConfig" class="materialize-textarea" required></textarea>
                                                <label for="ovpnConfig">V2ray Configuration</label>
                                                <?php echo isset($error['ovpnConfig']) ? $error['ovpnConfig'] : '';?>
                                            </div>
                                        </div>

                                        <div class="row">
											<div class="input-field col s12 m6">
                                                <select name="isPaid">
                                                    <option value="<?php echo TYPE_FREE;?>" <?php if($vpnType == TYPE_FREE) echo "selected"; else echo ""; ?> ><?php echo FREE; ?></option>
												    <option value="<?php echo TYPE_PAID;?>" <?php if($vpnType == TYPE_PAID) echo "selected"; else echo ""; ?> ><?php echo PAID; ?></option>
                                                </select>
                                                <label>Server Type</label>
                                            </div>
                                            <div class="input-field col s12 m6">
                                                <select name="status">
                                                    <option value="1" selected><?php echo ACTIVE; ?></option>
                                                    <option value="0"><?php echo INACTIVE; ?></option>
                                                </select>
                                                <label>Status</label><?php echo isset($error['status']) ? $error['status'] : '';?>
                                            </div>
										</div>

                                        <div class="row">
											
										</div>

                                        <button class="btn deep-orange waves-effect waves-light"
                                                type="submit" name="btnAdd">Submit
                                            <i class="mdi-content-send right"></i>
                                        </button>

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
