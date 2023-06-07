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



$query = "SELECT * FROM user_master WHERE user_name='" . $data->username . "' AND user_pass='" .$data->password . "' AND user_status = 'Y'";

$stmt = $conn-> prepare($query);

$stmt->execute();



if($stmt->rowCount() > 0){

    // get retrieved row

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // create array

    $user_arr=array(

        "responseType" => 'S',

            "responseValue" => [ 

                    "id" => $row['user_id'],

                    "name" => $row['user_name'],

                    "username" => $row['user_pass'],

                    "email" => $row['user_email'],

                    "mobile" => $row['user_mobile'],

                    "create_dt" => $row['user_created_dt'],

                    "update_dt" => $row['user_update_dt'],

                    "active" => $row['user_status']
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