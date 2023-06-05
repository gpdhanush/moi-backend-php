<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

// INCLUDING DATABASE AND MAKING OBJECT

require "database.php";
$db_connection = new Database();
$conn = $db_connection->dbConnection();
$data = json_decode(file_get_contents("php://input"));


if (empty($data->id)) {
$sql = "SELECT * FROM expense_master WHERE em_user_id='1' and em_active='Y' ORDER BY em_create_dt DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
} else {
    $sql = "SELECT * FROM expense_master WHERE em_user_id='1' and em_active='Y' and em_id='$data->id' ORDER BY em_create_dt DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
}

//CHECK WHETHER THERE IS ANY POST IN OUR DATABASE
if($stmt->rowCount() > 0) {
    // CREATE POSTS ARRAY
    $posts_array = [];
    $posts_array1 = [];

    $source = $row['em_date'];
    $date = new DateTime($source);
    $date1= $date->format('d-m-Y');


    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $post_data = [
            'id' => $row['em_id'],
            'userId' => $row['em_user_id'],
            'date' => $date1,
            'amount' => $row['em_amount'],
            'type' => $row['em_type'],
            'title' => $row['em_title'],
            'category' => $row['em_category'],
            'paymentStatus' => $row['em_status'],
            'paymentMode' => $row['em_payment'],
            'remarks' => $row['em_remarks'],
            'fileUrl' => $row['em_file_url'],
            'createDt' => $row['em_create_dt'],
            'updateDt' => $row['em_update_dt'],
            'active' => $row['em_active'],
        ];
        // PUSH POST DATA IN OUR $posts_array ARRAY
        array_push($posts_array1, $post_data);
        $posts_array= [
            "responseType" => 'S',
            "responseValue" => $posts_array1];
    }
} else{
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