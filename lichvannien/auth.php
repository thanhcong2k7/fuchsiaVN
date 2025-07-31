<?php
session_start();
require_once __DIR__ . '/.env.php';

// Xử lý logout
if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    session_destroy();
    header('Location: ' . BASE_URL);
    exit;
}

$provider = $_GET['provider'] ?? null;
$code = $_GET['code'] ?? null;

// Xử lý OAuth cho Google
if ($provider === 'google' && $code) {
    /*
    $authUrl = "https://accounts.google.com/o/oauth2/auth?" . http_build_query([
        'client_id' => GOOGLE_CLIENT_ID,
        'redirect_uri' => BASE_URL . '/auth.php?provider=google',
        'response_type' => 'code',
        'scope' => 'profile email',
        'access_type' => 'online',
    ]);
    header("Location: $authUrl");
    exit;*/

    $tokenUrl = 'https://oauth2.googleapis.com/token';
    $tokenParams = [
        'code' => $code,
        'client_id' => GOOGLE_CLIENT_ID,
        'client_secret' => GOOGLE_CLIENT_SECRET,
        'redirect_uri' => BASE_URL . '/auth.php?provider=google',
        'grant_type' => 'authorization_code',
    ];

    $ch = curl_init($tokenUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenParams));
    $response = curl_exec($ch);
    curl_close($ch);

    $tokenData = json_decode($response, true);

    if (isset($tokenData['access_token'])) {
        $userInfoUrl = 'https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $tokenData['access_token'];
        $userInfo = json_decode(file_get_contents($userInfoUrl), true);

        $_SESSION['user'] = [
            'id' => $userInfo['id'],
            'name' => $userInfo['name'],
            'email' => $userInfo['email'],
            'avatar_url' => $userInfo['picture'] ?? '',
            'access_token' => $tokenData["access_token"]
        ];
    }

    header('Location: ' . BASE_URL);
    exit;
}
if ($provider === 'google' && !isset($_SESSION['user'])) {
    $authUrl = "https://accounts.google.com/o/oauth2/auth?" . http_build_query([
        'client_id' => GOOGLE_CLIENT_ID,
        'redirect_uri' => BASE_URL . '/auth.php?provider=google',
        'response_type' => 'code',
        'scope' => 'profile email https://www.googleapis.com/auth/generative-language.tuned.readonly https://www.googleapis.com/auth/generative-language.aiservices',
        'access_type' => 'online',
    ]);
    header("Location: $authUrl");
    exit;
}
/*
// Xử lý callback từ Google
if ($provider === 'google' && isset($_GET['code'])) {
    $tokenUrl = 'https://accounts.google.com/o/oauth2/token';
    $tokenParams = [
        'code' => $_GET['code'],
        'client_id' => GOOGLE_CLIENT_ID,
        'client_secret' => GOOGLE_CLIENT_SECRET,
        'redirect_uri' => BASE_URL . '/auth.php?provider=google',
        'grant_type' => 'authorization_code',
    ];

    $ch = curl_init($tokenUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenParams));
    $response = curl_exec($ch);
    curl_close($ch);

    $tokenData = json_decode($response, true);

    if (isset($tokenData['access_token'])) {
        $userInfoUrl = 'https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $tokenData['access_token'];
        $userInfo = json_decode(file_get_contents($userInfoUrl), true);

        $_SESSION['user'] = [
            'id' => $userInfo['id'],
            'name' => $userInfo['name'],
            'email' => $userInfo['email'],
            'avatar_url' => $userInfo['picture'] ?? '',
        ];
    }

    header('Location: ' . BASE_URL);
    exit;
}*/

// Xử lý Facebook login (tương tự Google)
// ...

header('Location: ' . BASE_URL);
