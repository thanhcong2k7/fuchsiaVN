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
    var urll = "https://script.google.com/macros/s/AKfycbxp9SAD9h8Jbn-SUsZych1WNyMqvxlFzS2fWf5sD7auI9R2PF6vCTZ-z898h2n8Ii8c/exec";
    document.getElementById('uploadForm').addEventListener('submit', async function (event) {
      event.preventDefault();

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
              let fileID = getFileIdFromUrl(data.url)
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