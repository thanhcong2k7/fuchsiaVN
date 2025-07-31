<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: ' . ($_SERVER['HTTP_ORIGIN'] ?? '*'));

// Kiểm tra đăng nhập
if (empty($_SESSION['user']) || empty($_SESSION['user']['access_token'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized. Please login.']);
    exit;
}

// Nhận dữ liệu POST
$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';

if (empty($userMessage)) {
    http_response_code(400);
    echo json_encode(['error' => 'Message is required.']);
    exit;
}

// Xây dựng system prompt
$currentDate = date('d/m/Y');
$userName = $_SESSION['user']['name'] ?? 'User';
$systemPrompt = "Bạn tên là CayTre. Bạn là một trợ lý thông minh chuyên về lĩnh vực lịch vạn niên, tử vi và văn hóa phương Đông.
Hôm nay là ngày $currentDate, và bạn đang trò chuyện với $userName
[... hệ thống prompt đầy đủ như cũ ...]
Bạn đã sẵn sàng hỗ trợ.
";

// Lịch sử chat
if (!isset($_SESSION['chat_history'])) {
    $_SESSION['chat_history'] = [];
}
$history = $_SESSION['chat_history'];

// Format input theo Gemini
$messages = [['role' => 'user', 'parts' => [$systemPrompt]]];
foreach ($history as $turn) {
    $messages[] = ['role' => 'user', 'parts' => [$turn['user']]];
    $messages[] = ['role' => 'model', 'parts' => [$turn['bot']]];
}
$messages[] = ['role' => 'user', 'parts' => [$userMessage]];

// Tạo truy vấn
$data = json_encode(['contents' => $messages]);

// Gửi request
$accessToken = $_SESSION['user']['access_token'];
$ch = curl_init("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch from Gemini', 'response' => $response]);
    exit;
}

$responseData = json_decode($response, true);
$reply = $responseData['candidates'][0]['content']['parts'][0] ?? 'Xin lỗi, tôi không thể trả lời lúc này.';

// Lưu vào session
$_SESSION['chat_history'][] = [
    'user' => $userMessage,
    'bot' => $reply,
    'timestamp' => time()
];

// Giới hạn 10 tin
if (count($_SESSION['chat_history']) > 10) {
    array_shift($_SESSION['chat_history']);
}

echo json_encode(['reply' => $reply]);
