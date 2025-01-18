<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <title>Upload File</title>
</head>

<body>
  <h3>Upload File</h3>
  <form id="uploadForm">
    <input type="file" id="fileInput" name="file" />
    <button type="submit">Tải lên</button>
  </form>

  <div id="message"></div>

  <script>
    document.getElementById('uploadForm').addEventListener('submit', function(event) {
      event.preventDefault(); // Ngừng việc gửi form thông thường

      var fileInput = document.getElementById("fileInput");
      if (fileInput.files.length === 0) {
        document.getElementById("message").innerText = "Vui lòng chọn một file!";
        return;
      }

      var formData = new FormData();
      formData.append("file", fileInput.files[0]);

      // Gửi file đến server PHP bằng Fetch API
      fetch('upload.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.status === "success") {
            document.getElementById("message").innerText = "Tệp đã được tải lên thành công! URL: " + data.url;
          } else {
            document.getElementById("message").innerText = "Lỗi: " + data.message;
          }
        })
        .catch(error => {
          document.getElementById("message").innerText = "Lỗi kết nối!";
          console.error(error);
        });
    });
  </script>
  <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
        $file = $_FILES['file'];
        
        // Đọc file và chuyển sang base64
        $fileData = base64_encode(file_get_contents($file['tmp_name']));
        $fileName = $file['name'];
        $fileType = $file['type'];
    
        // URL của Apps Script đã triển khai
        $url = "YOUR_DEPLOYED_WEB_APP_URL"; // Thay bằng URL của Apps Script đã deploy
    
        // Chuẩn bị dữ liệu POST
        $postData = [
            'file' => $fileData,
            'name' => $fileName,
            'type' => $fileType
        ];
    
        // Cấu hình cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        // Gửi request
        $response = curl_exec($ch);
        curl_close($ch);
    
        // Xử lý kết quả trả về từ Apps Script
        $result = json_decode($response, true);
        if ($result['status'] === 'success') {
            echo json_encode([
                'status' => 'success',
                'url' => $result['url']
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => $result['message']
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'No file uploaded.'
        ]);
    }
  ?>
</body>

</html>
<?php
/*
    $file = $_FILES["filee"];
    $url = "https://script.google.com/macros/s/AKfycbw9VYsNCyvp8lw-E1nLnN70DvYf8urtIC2QKwWZxKb3pmPQ7z096uHNjDRQAjA5rWoG/exec";
    $ch = curl_init();
    //curl_setopt($ch, CURLOPT_URL,$url);
    //curl_setopt($ch, CURL,1);
    //$localFile = $_FILES["artworkup"]['tmp_name'];
    //$fp = fopen($localFile, 'r');
    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => http_build_query(array(
            'file' => base64_encode(file_get_contents($file["tmp_name"])),
            'type' => $file["type"],
            'name' => $file["name"]
        ))
    ));
    $res = curl_exec($ch);
    curl_close($ch);
    //echo $res;
    $result = json_decode($res, true);
    if ($result['status'] === 'success') {
        echo json_encode([
            'status' => 'success',
            'url' => $result['url']
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => $result['message']
        ]);
    }*/
?>
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
        $file = $_FILES['file'];
        
        // Đọc file và chuyển sang base64
        $fileData = base64_encode(file_get_contents($file['tmp_name']));
        $fileName = $file['name'];
        $fileType = $file['type'];
    
        // URL của Apps Script đã triển khai
        $url = "https://script.google.com/macros/s/AKfycbzmkzng-ANV5eCreV3VAtBrG9lEcSqhOho-T6FBP7LQnDaiGyoQdhZ5C5kteIMAYhuW/exec"; // Thay bằng URL của Apps Script đã deploy
    
        // Chuẩn bị dữ liệu POST
        $postData = [
            'file' => $fileData,
            'name' => $fileName,
            'type' => $fileType
        ];
    
        // Cấu hình cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        // Gửi request
        $response = curl_exec($ch);
        curl_close($ch);
    
        // Xử lý kết quả trả về từ Apps Script
        $result = json_decode($response, true);
        if ($result['status'] === 'success') {
            echo json_encode([
                'status' => 'success',
                'url' => $result['url']
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => $result['message']
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'No file uploaded.'
        ]);
    }
  ?>