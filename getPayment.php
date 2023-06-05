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

if (empty($data->id)) {
$sql = "SELECT * FROM payment_master WHERE (pm_user_id=0 or pm_user_id='$data->userId') ORDER BY pm_name ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
} else {
    $sql = "SELECT * FROM payment_master WHERE pm_id='$data->id' ORDER BY pm_name ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
}
//CHECK WHETHER THERE IS ANY POST IN OUR DATABASE
if($stmt->rowCount() > 0){
    // CREATE POSTS ARRAY
    $posts_array = [];
    $posts_array1 = [];

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $post_data = [
            'id' => $row['pm_id'],
            'value' => $row['pm_name'],
            'userId' => $row['pm_user_id'],
            'createDt' => $row['pm_create_dt'],
            'updateDt' => $row['pm_update_dt'],
            'active' => $row['pm_active']
        ];
        // PUSH POST DATA IN OUR $posts_array ARRAY
        array_push($posts_array1, $post_data);
        $posts_array= [
            "responseType" => 'S',
            "responseValue" => $posts_array1];
    }
    //SHOW POST/POSTS IN JSON FORMAT
}
else{
    //IF THERE IS NO POST IN OUR DATABASE
    http_response_code(400);
    $posts_array = array(
        "responseType" => 'F',
        "responseValue" => [
            "message" => "No data found!",
        ]
    );
}
echo json_encode($posts_array);
?>