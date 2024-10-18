<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Allow-Headers: Content-Type, Authorization");
require_once '../../source/models/Auth.php';
$auth = new Auth();
$data = json_decode(file_get_contents("php://input"), true);
// $data = ['token' => 'fb2acf6d3efa438db3adcb67cc9ffd4c'];
$response = $auth->logout($data['token']);
echo json_encode($response);
