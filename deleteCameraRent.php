<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

// INCLUDING DATABASE AND MAKING OBJECT
require "database.php";
$db_connection = new Database();
$conn = $db_connection->dbConnection();
$data = json_decode(file_get_contents("php://input"));

$sql = "DELETE FROM camera_amount WHERE cm_id='$data->id' AND cm_um_id='$data->userId' ";
$stmt = $conn->prepare($sql);
$stmt->execute();

//CHECK WHETHER THERE IS ANY POST IN OUR DATABASE
if($stmt->rowCount() > 0){
    http_response_code(200);
    $posts_array = array(
        "responseType" => 'S',
        "responseValue" => [
            "message" => "Data deleted successfully.",
        ]
    );
}
else{
    //IF THERE IS NO POST IN OUR DATABASE
    http_response_code(400);
    $posts_array = array(
        "responseType" => 'F',
        "responseValue" => [
            "message" => "Data deleted not successfully.",
        ]
    );
}
echo json_encode($posts_array);
?>