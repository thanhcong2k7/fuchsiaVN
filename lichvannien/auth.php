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

// Khi user CHƯA đăng nhập → điều hướng đến Google OAuth
if ($provider === 'google' && !isset($_SESSION['user']) && !$code) {
    $authUrl = "https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query([
        'client_id' => GOOGLE_CLIENT_ID,
        'redirect_uri' => BASE_URL . '/auth.php?provider=google',
        'response_type' => 'code',
        'scope' => implode(' ', [
            'openid',
            'email',
            'profile',
            'https://www.googleapis.com/auth/generative-language.tuned.readonly',
            'https://www.googleapis.com/auth/generative-language.aiservices'
        ]),
        'access_type' => 'offline',
        'prompt' => 'consent' // yêu cầu user luôn cấp lại quyền
    ]);
    header("Location: $authUrl");
    exit;
}

// Khi user quay lại với mã `code` → trao đổi access_token
if ($provider === 'google' && $code) {
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
        // Lấy thông tin user từ OpenID Connect
        $userInfoUrl = 'https://openidconnect.googleapis.com/v1/userinfo';
        $ch = curl_init($userInfoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $tokenData['access_token']
        ]);
        $userInfoResponse = curl_exec($ch);
        curl_close($ch);

        $userInfo = json_decode($userInfoResponse, true);

        // Lưu session
        $_SESSION['user'] = [
            'id' => $userInfo['sub'],
            'name' => $userInfo['name'] ?? '',
            'email' => $userInfo['email'] ?? '',
            'avatar_url' => $userInfo['picture'] ?? '',
            'access_token' => $tokenData["access_token"],
            'refresh_token' => $tokenData["refresh_token"] ?? null,
            'expires_in' => $tokenData["expires_in"] ?? null,
        ];
    }

    header('Location: ' . BASE_URL);
    exit;
}

// Nếu không có hành động rõ ràng
header('Location: ' . BASE_URL);
