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
    $query = "INSERT INTO expense_master (em_user_id, em_date, em_amount, em_type, em_title, em_category, em_status, em_payment, em_remarks, em_file_url) 

        VALUES ('$data->userId', '$date1', '$data->amount','$data->type','$data->title','$data->category','$data->paymentStatus','$data->paymentMode','$data->remarks','$data->fileUrl')";
} else {
    $query = "UPDATE expense_master SET 
            em_date='$date1',
            em_amount='$data->amount',
            em_type='$data->type',
            em_title='$data->title', 
            em_category='$data->category',
            em_status='$data->paymentStatus',
            em_payment='$data->paymentMode',
            em_remarks='$data->remarks',
            em_file_url='$data->fileUrl'
    WHERE em_id='$data->id'";
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
