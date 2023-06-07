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
$sql = "SELECT * FROM location_master WHERE lm_um_id='$data->userId' ORDER BY lm_desc ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
} else {
    $sql = "SELECT * FROM location_master WHERE lm_id='$data->id' ORDER BY lm_desc ASC";
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
            'id' => $row['lm_id'],
            'userId' => $row['lm_um_id'],
            'location' => $row['lm_desc'],
            'remarks' => $row['lm_remarks'],
            'createDt' => $row['lm_created_dt'],
            'updateDt' => $row['lm_updated_dt'],
            'active' => $row['lm_active']
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