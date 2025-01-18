<form id="frm">
    <input type="file" name="filee" id="filee">
    <input type="submit" name="submit" id="submit">
</form>
<span id="msg"></span>
<script>
    document.getElementById("frm").addEventListener("submit", function (e)=> {
        e.preventDefault();
        var fileInput = document.getElementById("filee");
        if(fileInput.files.length === 0) {
            document.getElementById("msg").innerText = "Vui lòng chọn một file!";
            return;
        }
        var formData = new FormData();
        formData.append("filee", fileInput.files[0]);

        // Gửi file đến server PHP bằng Fetch API
        fetch('upload.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                document.getElementById("msg").innerText = "Tệp đã được tải lên thành công! URL: " + data.url;
            } else {
                document.getElementById("msg").innerText = "Lỗi: " + data.message;
            }
        })
        .catch(error => {
            document.getElementById("msg").innerText = "Lỗi kết nối!";
            console.error(error);
        });
    });
</script>