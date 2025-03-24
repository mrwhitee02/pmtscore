<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Check CNIC format
if (!isset($_GET['cnic']) || !preg_match('/^\d{13}$/', $_GET['cnic'])) {
    echo json_encode(["status" => false, "error" => "Invalid CNIC format"]);
    exit;
}

$cnic = $_GET['cnic'];
$url = "https://api-schemes.government.com.pk/api/pmt-score/get-score?cnic=$cnic";

// Use cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$data = json_decode($response, true);

// Ensure valid response
if ($http_code == 200 && isset($data['status']) && $data['status'] === true) {
    echo json_encode($data);
} else {
    echo json_encode(["status" => false, "message" => "No record found!"]);
}
?>
