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
  
</body>

</html>