<?php
require_once 'source/models/UserModel.php';
require_once 'source/models/Auth.php';

$user = new UserModel();
// $data = ['name' => 'Quang Tran', 'password' => 'quang1', 'email' => 'quang12gfsdfmail.com'];

// $user->createUser($data);
$auth = new Auth();

// Ví dụ kiểm thử cho hàm login
$result1 = $auth->login('quang@gmail.com', 'quang1'); // Mong đợi thành công
$result2 = $auth->login('quang1@gmail.com', 'wrongPassword'); // Mong đợi mật khẩu không chính xác
$result3 = $auth->login('invalid@example.com', 'password'); // Mong đợi email không hợp lệ

var_dump($result1,$result2,$result3);

var_dump($auth->logout('c25422ed36fed8ac55c967ee61952878'));



