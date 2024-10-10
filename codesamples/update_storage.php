<body>
    <div>
      <input type="file" id="file" /> <button class="btn btn-primary"  id="upload">UPLOAD</button>
    </div>
    <img id="img" width="50%" />
  </body>
  <script>
    const elInput = document.getElementById("file");
    const img = document.getElementById("img");
    const uploadBtn = document.getElementById("upload");

    uploadBtn.addEventListener("click", () => {
      const file = elInput.files[0];
      if (!file) {
        return elInput.click();
      }
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.addEventListener("load", () => {
        const data = reader.result.split(",")[1];
        const postData = {
          name: file.name,
          type: file.type,
          data: data,
        };
        postFile(postData);
      });
    });
    async function postFile(postData) {
      try {
        const response = await fetch(
          "https://script.google.com/macros/s/AKfycbzDWAED3WIgge7pGPqEnX1qLJFBuzigXDToNWf-jUyQBkUVvYJpIXV6aLA3dPcGjwJv0g/exec",
          {
            method: "POST",
            body: JSON.stringify(postData),
          }
        );
        const data = await response.json();
        console.log(data);
        img.src = data.link + "&sz=s500";
      } catch (error) {
        alert("Vui lòng thử lại");
      }
    }
  </script>
</html>