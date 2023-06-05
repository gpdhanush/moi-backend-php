<?php
// SET HEADER
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


if (empty($data->id)) {
$sql = "SELECT * FROM category_master WHERE cm_user_id='$data->userId' ORDER BY cm_title ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
} else {
    $sql = "SELECT * FROM category_master WHERE cm_user_id='$data->userId' and cm_id='$data->id' ORDER BY cm_title ASC";
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
            'id' => $row['cm_id'],
            'value' => $row['cm_title'],
            'userId' => $row['cm_user_id'],
            'type' => $row['cm_type'],
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