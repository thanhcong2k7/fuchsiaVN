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
    function getFileIdFromUrl(url) {
      const match = url.match(/\/d\/(.*?)\//);
      return match ? match[1] : null;
    }

    document.getElementById('uploadForm').addEventListener('submit', async function (event) {
      event.preventDefault();
      var urll = "https://script.google.com/macros/s/AKfycbxN_iQU0-OYm8SOiH0RI_M7oWHIkMI_ZKoWJcRMH7ayyMzygOQXaNZ8GNu0P2ZEba2X/exec";
      var fileInput = document.getElementById("fileInput");
      if (fileInput.files.length === 0) {
        document.getElementById("message").innerText = "Vui lòng chọn một file!";
        return;
      }

      var file = fileInput.files[0];
      const GAS_DEPLOY = urll;

      try {
        const base64String = await encodeFileToBase64(file);

        var formData = new FormData();
        formData.append("name", file.name);
        formData.append("type", file.type);
        formData.append("file", base64String);

        fetch(GAS_DEPLOY, {
          method: 'POST',
          body: formData
        })
          .then(response => response.json())
          .then(data => {
            if (data.status === "success") {
              let fileID = getFileIdFromUrl(data.url);
              document.getElementById("message").innerText = fileID;
              console.log(fileID);
            } else {
              document.getElementById("message").innerText = "Lỗi: " + data.message;
            }
          })
          .catch(error => {
            document.getElementById("message").innerText = "Lỗi kết nối!";
            console.error(error);
          });

      } catch (error) {
        document.getElementById("message").innerText = "Lỗi mã hóa tệp!";
        console.error(error);
      }
    });

    function encodeFileToBase64(file) {
      return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result.split(',')[1]);
        reader.onerror = (error) => reject(error);
        reader.readAsDataURL(file);
      });
    }
  </script>
</body>

</html>