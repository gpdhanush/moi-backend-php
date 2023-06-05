<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Origin, Authorization, X-Requested-With, Content-Type, Accept, Cache-Control");

 
require 'database.php';

$db_connection = new Database();

$conn = $db_connection->dbConnection();

$data = json_decode(file_get_contents("php://input"));



$query = "SELECT * FROM user_master WHERE um_username='" . $data->username . "' AND um_password='" .$data->password . "' AND um_active = 'Y'";

$stmt = $conn-> prepare($query);

$stmt->execute();



if($stmt->rowCount() > 0){

    // get retrieved row

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // create array

    $user_arr=array(

        "responseType" => 'S',

            "responseValue" => [ 

                    "id" => $row['um_id'],

                    "name" => $row['um_name'],

                    "username" => $row['um_username'],

                    "email" => $row['um_email'],

                    "mobile" => $row['um_mobile'],

                    "last_login" => $row['um_last_login'],

                    "profile" => $row['um_profile_url'],

                    "create_dt" => $row['um_create_dt'],

                    "update_dt" => $row['um_update_dt'],

                    "active" => $row['um_active']
                ]);

}

else{

    http_response_code(400);

    $user_arr=array(

       "responseType" => 'F',

            "responseValue" => [ 

                    "message" => "Invalid Details",

                    ]

    );

}

echo (json_encode($user_arr));

?>