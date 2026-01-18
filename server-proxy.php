<?php
// server-proxy.php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if($action == 'login') {
    $username = 'mochna11';
    $password = '543792';
    $video_url = 'https://course.itminanpublications.com/courses/class-nursery-video-course/lessons/nursari-bangla-01/';
    
    // cURL দিয়ে লগইন
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://course.itminanpublications.com/wp-login.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'log' => $username,
        'pwd' => $password,
        'rememberme' => 'forever'
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
    curl_setopt($ch, CURLOPT_HEADER, true);
    
    $response = curl_exec($ch);
    $cookies = [];
    
    // কুকি এক্সট্রাক্ট
    preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches);
    foreach($matches[1] as $cookie) {
        $cookies[] = $cookie;
    }
    
    echo json_encode([
        'success' => true,
        'cookies' => $cookies,
        'video_url' => $video_url,
        'redirect' => $video_url
    ]);
}
?>