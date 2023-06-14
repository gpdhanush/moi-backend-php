<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header(
    "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"
);

require "database.php";

$db_connection = new Database();

$conn = $db_connection->dbConnection();

$data = json_decode(file_get_contents("php://input"));

date_default_timezone_set("asia/kolkata");

$source = $data->date;
$date = new DateTime($source);
$date1= $date->format('Y-m-d');

// Condition Check

if (empty($data->id)) {
    $query = "INSERT INTO camera_amount (cm_um_id, cm_date, cm_name, cm_amount) 

        VALUES ('$data->userId', '$date1', '$data->name','$data->amount')";
} else {
    $query = "UPDATE camera_amount SET 
            cm_um_id='$data->userId',
            cm_date='$date1',
            cm_name='$data->name',
            cm_amount='$data->amount'
    WHERE cm_id='$data->id'";
}

$stmt = $conn->prepare($query);

$stmt->execute();

if ($stmt) {
    http_response_code(200);

    $user_arr = [
        "responseType" => "S",

        "responseValue" => [
            "message" => empty($data->id)
                ? "Saved Successfully."
                : "Updated Successfully.",
        ],
    ];
} else {
    http_response_code(400);

    $user_arr = [
        "responseType" => "F",
        "responseValue" => [
            "message" => empty($data->id) ? "Save Failed!" : "Updated Failed!",
        ],
    ];
}

echo json_encode($user_arr);

?>
