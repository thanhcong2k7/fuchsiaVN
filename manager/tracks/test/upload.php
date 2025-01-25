<?php
    include '../../../assets/variables/var.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $urll = $gas;
        if (empty($_FILES['fileInput']['name'])) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng chọn một file!']);
            exit;
        }
        $file = $_FILES['fileInput'];
        $GAS_DEPLOY = $urll;

        try {
            $base64String = base64_encode(file_get_contents($file['tmp_name']));
            $formData = [
                'name' => $file['name'],
                'type' => $file['type'],
                'file' => $base64String
            ];

            $ch = curl_init($GAS_DEPLOY);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);

            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);
            if ($data['status'] === 'success') {
                $fileID = $data['url'];
                echo json_encode(['status' => 'success', 'fileID' => $fileID]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi: ' . $data['message']]);
            }
        } catch (Exception $error) {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi mã hóa tệp!']);
        }
    }
?>