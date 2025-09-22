<?php
$host = "localhost";
$db = "crop_care";
$user = "root";   // change if needed
$pass = "";       // change if needed

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(['status'=>false,'message'=>'Database Connection Failed']));
}

header('Content-Type: application/json');
$action = $_POST['action'] ?? '';

switch($action) {
    
    case 'save_farmer':
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $land_size = $_POST['land_size'];
        $location = $_POST['location'];
        $stmt = $conn->prepare("INSERT INTO farmers(name,email,phone,land_size,location) VALUES(?,?,?,?,?)");
        $stmt->bind_param("sssds",$name,$email,$phone,$land_size,$location);
        if($stmt->execute()){
            echo json_encode(['status'=>true,'farmer_id'=>$stmt->insert_id]);
        } else echo json_encode(['status'=>false,'message'=>'Save failed']);
        break;

    case 'submit_feedback':
        $farmer_id = $_POST['farmer_id'];
        $message = $_POST['message'];
        $stmt = $conn->prepare("INSERT INTO feedback(farmer_id,message) VALUES(?,?)");
        $stmt->bind_param("is",$farmer_id,$message);
        if($stmt->execute()){
            echo json_encode(['status'=>true]);
        } else echo json_encode(['status'=>false,'message'=>'Feedback failed']);
        break;

    case 'save_recommendation':
        $farmer_id = $_POST['farmer_id'];
        $soil_type = $_POST['soil_type'];
        $water_level = $_POST['water_level'];
        $season = $_POST['season'];
        $crop = $_POST['recommended_crop'];
        $fert = $_POST['fertilizer'];
        $pest = $_POST['pesticide'];
        $stmt = $conn->prepare("INSERT INTO recommendations(farmer_id,soil_type,water_level,season,recommended_crop,fertilizer,pesticide) VALUES(?,?,?,?,?,?,?)");
        $stmt->bind_param("issssss",$farmer_id,$soil_type,$water_level,$season,$crop,$fert,$pest);
        if($stmt->execute()){
            echo json_encode(['status'=>true]);
        } else echo json_encode(['status'=>false,'message'=>'Save failed']);
        break;

    case 'get_farmer':
        $farmer_id = $_POST['farmer_id'];
        $stmt = $conn->prepare("SELECT * FROM farmers WHERE id=?");
        $stmt->bind_param("i",$farmer_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res->fetch_assoc();
        echo json_encode($data);
        break;

    default:
        echo json_encode(['status'=>false,'message'=>'Invalid action']);
}

$conn->close();
?>
