<?php

require_once("Rest.inc.php");

class API extends REST {

    public $data = "";
    const demo_version = false;

    private $db = NULL;
    private $mysqli = NULL;

    public function __construct() {
        // Init parent contructor
        parent::__construct();
        // Initiate Database connection
        $this->dbConnect();
        date_default_timezone_set('Asia/Kolkata');
    }

    /*
     *  Connect to Database
     */
    private function dbConnect() {
        include "../includes/config.php";
        $this->mysqli = new mysqli($host, $user, $pass, $database);
        $this->mysqli->query('SET CHARACTER SET utf8');
    }

    /*
     * Dynmically call the method based on the query string
     */
    public function processApi() {
        $func = strtolower(trim(str_replace("/", "", $_REQUEST['x'])));
        if ((int) method_exists($this, $func) > 0)
            $this->$func();
        else {
            $response = array('status' => FAIL, 'message' => MSG_NO_METHOD_FOUND);
            $this->response($this->json($response), 404);    // If the method not exist with in this class "Page not found".
        }
    }

    /* Api Checker */
    private function checkConnection() {
        if (mysqli_ping($this->mysqli)) {
            $response = array('status' => SUCCESS, 'database' => 'connected');
            $this->response($this->json($response), 200);
        } else {
            $response = array('status' => FAIL, 'database' => 'not connected');
            $this->response($this->json($response), 404);
        }
    }

    private function getServers() {
    include "../includes/config.php";
    if ($this->get_request_method() != "POST") $this->response('', 406);
    $jsonData = json_decode(file_get_contents('php://input'));

    $isPaid = !empty($jsonData->{'isPaid'}) ? $jsonData->{'isPaid'} : 0; // 0=Free, 1=Paid
    $limit = !empty($jsonData->{'count'}) ? ((int)$jsonData->{'count'}) : 10;
    $page = !empty($jsonData->{'page'}) ? ((int)$jsonData->{'page'}) : 1;

    if ($page == 0) {
        $page = 1;
    }

    $offset = ($page * $limit) - $limit;
    $count_total = $this->get_count_result("SELECT COUNT(isPaid) FROM  tbl_servers WHERE isPaid = $isPaid AND active = 1");
    $FLAG_IMG = FLAG_IMG;
   $query = "SELECT id, serverName, 
                    concat('$FLAG_IMG', flagURL, '.png') as flagURL, 
                    TO_BASE64(ovpnConfig) v2rayConfig,  -- Rename ovpnConfig to v2rayConfig
                    isPaid, active, createdAt, updatedAt
                    FROM tbl_servers WHERE isPaid = $isPaid AND active = 1 ORDER BY serverName LIMIT $limit OFFSET $offset";
        $posts = $this->get_list_result($query);

        // Remove username and password from the response
        foreach ($posts as &$post) {
            unset($post['username']);
            unset($post['password']);

            // Decode v2rayConfig if it's encoded
            if (isset($post['v2rayConfig'])) {
                $post['v2rayConfig'] = base64_decode($post['v2rayConfig']);
            }
        }

        $count = count($posts);

        $response = array(
            'status' => SUCCESS, 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'posts' => $posts
        );
        $this->response($this->json($response), 200);
    }

    private function getAppSettings() {
        include "../includes/config.php";
        if ($this->get_request_method() != "POST") $this->response('', 406);
        $jsonData = json_decode(file_get_contents('php://input'));

        $query = "SELECT *, CONCAT(app_version, ' ')  as app_version FROM tbl_settings where id = '1'";
        $data = $this->get_object_result($query);
        $response = array('status' => SUCCESS, 'message' => MSG_RECORD_FOUND, 'data' => $data);
        $this->response($this->json($response), 200);
    }

    private function getPaidServers() {
        include "../includes/config.php";
        if ($this->get_request_method() != "POST") $this->response('', 406);
        $query = "SELECT * FROM tbl_servers WHERE isPaid = 1";
        $post = $this->get_list_result($query);
        $response = array(
            'status' => SUCCESS, 'posts' => $post
        );
        $this->response($this->json($response), 200);
    }

    private function insertSubscription() {
        include "../includes/config.php";
        if ($this->get_request_method() != "POST") $this->response('', 406);
        $jsonData = json_decode(file_get_contents('php://input'));

        $orderId = !empty($jsonData->{'orderId'}) ? $jsonData->{'orderId'} : 'GPA';
        $productId = !empty($jsonData->{'productId'}) ? $jsonData->{'productId'} : 'onemonth';
        $purchaseToken = !empty($jsonData->{'purchaseToken'}) ? $jsonData->{'purchaseToken'} : 'token';
        $purchaseTime = !empty($jsonData->{'purchaseTime'}) ? $jsonData->{'purchaseTime'} : 123;
        $priceCurrencyCode = !empty($jsonData->{'priceCurrencyCode'}) ? $jsonData->{'priceCurrencyCode'} : 'INR';
        $formattedPrice = !empty($jsonData->{'formattedPrice'}) ? $jsonData->{'formattedPrice'} : '₹1';
        $priceAmountMicros = !empty($jsonData->{'priceAmountMicros'}) ? $jsonData->{'priceAmountMicros'} : 100;
        $active = 1;
        $purchaseToken = base64_decode($purchaseToken);

        $date = date('Y-m-d H:i:s', time());

        $query = "SELECT * from tbl_subscription  WHERE orderId = '$orderId'";
        $lstObj = $this->get_list_result($query);
        $count = count($lstObj);
        if ($count > 0) {
            // Inactive older order if found
            $sql_query = "UPDATE tbl_subscription SET active=?, updatedAt=?  WHERE orderId=?";
            $stmt = $connect->stmt_init();
            if ($stmt->prepare($sql_query)) {
                $stmt->bind_param('iss', 0, $date, $orderId);
                $stmt->execute();
                $update_result = $stmt->store_result();
                $stmt->close();
                $message = "Record updated successfully.";
            }
        } else {
            $sql = "INSERT INTO tbl_subscription (orderId, productId, purchaseToken, purchaseTime, priceCurrencyCode, formattedPrice, priceAmountMicros, active, createdAt, updatedAt) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insert = $connect->prepare($sql);
            $insert->bind_param('sssisssiss', $orderId, $productId, $purchaseToken, $purchaseTime, $priceCurrencyCode, $formattedPrice, $priceAmountMicros, $active, $date, $date);
            $insert->execute();
            $message = "Record insert successfully.";
        }
        $response = array('status' => SUCCESS, 'message' => $message);
        $this->response($this->json($response), 200);
    }

    private function getDonutChartData() {
        include "../includes/config.php";

        if ($this->get_request_method() != "GET") $this->response('', 406);

        $query = "SELECT 'Total' AS title, COUNT(isPaid) AS 'value' FROM tbl_servers
                    UNION
                    SELECT 'Paid', COUNT(isPaid) FROM tbl_servers
                    WHERE isPaid = 1
                    UNION
                    SELECT 'Free', COUNT(isPaid) FROM tbl_servers
                    WHERE isPaid = 0
                    ";

        $result = ($this->get_list_result($query));

        $response = array('status' => SUCCESS, 'data' => $result);

        $this->response($this->json($response), 200);
    }

    /**
     * =========================================================================================================
     * COMMON METHODS  
     * =========================================================================================================
     */
    private function getCurrentDate() {
        return date('Y-m-d H:i:s', time());
    }

    private function getISTDateTime() {
        return $this->getCurrentDate();
    }

    private function getISTDate() {
        return date('Y-m-d', time());
    }

    private function getISTTime() {
        return date('H:i:s', time());
    }

    // Don't edit all the code below
    private function get_list($query) {
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
        if ($r->num_rows > 0) {
            $result = array();
            while ($row = $r->fetch_assoc()) {
                $result[] = $row;
            }
            $this->response($this->json($result), 200); // send user details
        }
        $this->response('', 204);    // If no records "No Content" status
    }

    private function get_list_result($query) {
        $result = array();
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
        if ($r->num_rows > 0) {
            while ($row = $r->fetch_assoc()) {
                $result[] = $row;
            }
        }
        return $result;
    }

    private function get_object_result($query) {
        $result = array();
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
        if ($r->num_rows > 0) {
            while ($row = $r->fetch_assoc()) {
                $result = $row;
            }
        }
        return $result;
    }

    private function get_one($query) {
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
        if ($r->num_rows > 0) {
            $result = $r->fetch_assoc();
            $this->response($this->json($result), 200); // send user details
        }
        $this->response('', 204);    // If no records "No Content" status
    }

    private function get_count($query) {
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
        if ($r->num_rows > 0) {
            $result = $r->fetch_row();
            $this->response($result[0], 200);
        }
        $this->response('', 204);    // If no records "No Content" status
    }

    private function get_count_result($query) {
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
        if ($r->num_rows > 0) {
            $result = $r->fetch_row();
            return $result[0];
        }
        return 0;
    }

    private function post_one($obj, $column_names, $table_name) {
        $keys = array_keys($obj);
        $columns = '';
        $values = '';
        foreach ($column_names as $desired_key) {
            if (!in_array($desired_key, $keys)) {
                $$desired_key = '';
            } else {
                $$desired_key = $obj[$desired_key];
            }
            $columns = $columns . $desired_key . ',';
            $values = $values . "'" . $this->real_escape($$desired_key) . "',";
        }
        $query = "INSERT INTO " . $table_name . "(" . trim($columns, ',') . ") VALUES(" . trim($values, ',') . ")";

        if (!empty($obj)) {

            if ($this->mysqli->query($query)) {
                $status = "success";
                $msg = $table_name . " created successfully";
            } else {
                $status = "failed";
                $msg = $this->mysqli->error . __LINE__;
            }
            $resp = array('status' => $status, "msg" => $msg, "data" => $obj);
            $this->response($this->json($resp), 200);
        } else {
            $this->response('', 204);    //"No Content" status
        }
    }

    private function post_update($id, $obj, $column_names, $table_name) {
        $keys = array_keys($obj[$table_name]);
        $columns = '';
        $values = '';
        foreach ($column_names as $desired_key) {
            if (!in_array($desired_key, $keys)) {
                $$desired_key = '';
            } else {
                $$desired_key = $obj[$table_name][$desired_key];
            }
            $columns = $columns . $desired_key . "='" . $this->real_escape($$desired_key) . "',";
        }

        $query = "UPDATE " . $table_name . " SET " . trim($columns, ',') . " WHERE id=$id";
        if (!empty($obj)) {
            if ($this->mysqli->query($query)) {
                $status = "success";
                $msg = $table_name . " update successfully";
            } else {
                $status = "failed";
                $msg = $this->mysqli->error . __LINE__;
            }
            $resp = array('status' => $status, "msg" => $msg, "data" => $obj);
            $this->response($this->json($resp), 200);
        } else {
            $this->response('', 204);    // "No Content" status
        }
    }

    private function delete_one($id, $table_name) {
        if ($id > 0) {
            $query = "DELETE FROM " . $table_name . " WHERE id = $id";
            if ($this->mysqli->query($query)) {
                $status = "success";
                $msg = "One record " . $table_name . " successfully deleted";
            } else {
                $status = "failed";
                $msg = $this->mysqli->error . __LINE__;
            }
            $resp = array('status' => $status, "msg" => $msg);
            $this->response($this->json($resp), 200);
        } else {
            $this->response('', 204);    // If no records "No Content" status
        }
    }

    private function responseInvalidParam() {
        $resp = array("status" => 'Failed', "msg" => 'Invalid Parameter');
        $this->response($this->json($resp), 200);
    }

    /* ==================================== End of API utilities ==========================================
     * ====================================================================================================
     */

  /* Encode array into JSON */
    private function json($data) {
        if (is_array($data)) {
            return json_encode($data, JSON_NUMERIC_CHECK);
        }
    }

    /* String mysqli_real_escape_string */
    private function real_escape($s) {
        return mysqli_real_escape_string($this->mysqli, $s);
    }
}

// Initiate Library
$api = new API;
$api->processApi();
?>