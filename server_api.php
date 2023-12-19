<?php
// PHP script to provide server details via API

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Define server details
    $serverDetails = [
        'serverName' => 'Example Server',
        'serverFlag' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/48/Flag_of_Singapore.svg/2560px-Flag_of_Singapore.svg.png',
        'serverConfig' => 'Sample Config'
    ];

    // Send the server details as a JSON response
    header('Content-Type: application/json');
    echo json_encode($serverDetails);
} else {
    // Handle other request methods
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed']);
}
?>
