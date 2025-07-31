<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: ' . ($_SERVER['HTTP_ORIGIN'] ?? '*'));
require_once __DIR__ . '/../.env.php';

$date = $_GET['date'] ?? date('Y-m-d');
// Validate date format and range
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid date format. Use YYYY-MM-DD.']);
    exit;
}

$today = new DateTime();
$selectedDate = DateTime::createFromFormat('Y-m-d', $date);
if (!$selectedDate) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid date.']);
    exit;
}

$minDate = (clone $today)->modify('-1 year');
$maxDate = (clone $today)->modify('+1 year');

if ($selectedDate < $minDate || $selectedDate > $maxDate) {
    http_response_code(400);
    echo json_encode(['error' => 'Date out of range. Only dates within one year from today are allowed.']);
    exit;
}

$cacheDir = __DIR__ . '/../cache';
$cacheFile = $cacheDir . '/' . $date . '.json';

// Return cached data if exists
if (file_exists($cacheFile)) {
    readfile($cacheFile);
    exit;
}

// Generate prompt for OpenRouter
$prompt = "Phân tích lịch vạn niên cho ngày $date (dương lịch). Hãy cung cấp thông tin: 
- Âm lịch (ngày, tháng, năm âm lịch)
- Mệnh ngũ hành
- Giờ hoàng đạo (cho cả ngày, nếu có thể)
- Tuổi xung (đầy đủ danh sách)
- Đánh giá ngày tốt/xấu, việc nên làm/kiêng kỵ
- Các thông tin phong thủy khác liên quan.

Hãy trả lời *bằng JSON hợp lệ duy nhất*, không có chú thích, không có giải thích, không có đoạn văn bản bên ngoài. Đảm bảo JSON trả về không chứa lỗi cú pháp.
Cấu trúc như sau:
{
  'solar_date': 'Thứ ..., ngày ... tháng ... năm ...',
  'lunar_date': 'Ngày ... tháng ... năm ...',
  'chinese_zodiac': 'Can chi',
  'element': 'Tên mệnh ngũ hành',
  'auspicious_hours': 'Các giờ hoàng đạo (ví dụ: Bính Tí (23h-1h), Đinh Sửu (1h-3h), Kỷ Mão (5h-7h), Nhâm Ngọ (11h-13h))',
  'day_quality': 'Tốt/Xấu/Bình thường',
  'day_reason': 'Lý do ngắn giải thích cho day_quality',
  'recommended_activities': 'Các việc nên làm',
  'avoid_activities': 'Các việc nên tránh',
  'conflicting_ages': 'Các tuổi xung (ví dụ: Tuổi Tỵ, Hợi)',
  'additional_info': 'Thông tin bổ sung'
}
Chỉ trả về JSON hợp lệ như trên. Không thêm bất kỳ mô tả, markdown, tiêu đề, hoặc văn bản nào khác.
";

$apiKey = GOOGLE_AI_STUDIO_API;
$apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemma-3n-e4b-it:generateContent?key=' . $apiKey;
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n
    \"contents\":
        [{\n
            \"parts\":[
                {
                    \"text\":\"" . $prompt . "\"
                }
            ]\n
        }],
    \"generationConfig\":{
      \"temperature\": 0.8,
      \"topP\": 0.95
    }\n
}");

$headers = array();
$headers[] = 'Content-Type: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$res = curl_exec($ch);
$response = str_replace('```', '', str_replace('```json', '', $res));

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

if ($httpCode !== 200) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch data from Google, ' . $res]);
    exit;
}

$responseData = json_decode($response, true);
$content = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? '';

// Extract JSON from response
$jsonStart = strpos($content, '{');
$jsonEnd = strrpos($content, '}');
if ($jsonStart !== false && $jsonEnd !== false) {
    $jsonString = substr($content, $jsonStart, $jsonEnd - $jsonStart + 1);
    $calendarData = json_decode($jsonString, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        // Save to cache
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        file_put_contents($cacheFile, json_encode($calendarData, JSON_UNESCAPED_UNICODE));
        echo json_encode($calendarData);
        exit;
    }
}

// Fallback if JSON parsing fails
http_response_code(500);
echo json_encode(['error' => 'Failed to parse response from Google, ' . $content]);