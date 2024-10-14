<header>
<base target="_top">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.js"></script>
</header>
<form id="form">
  <input name="file" id="uploadfile" type="file">
  <input name="filename" id="filename" type="text">
  <input id="submit" type="submit">
</form>
<script>
const form = document.getElementById('form');
form.addEventListener('submit', e => {
  e.preventDefault();
  const file = form.file.files[0];
  const fr = new FileReader();
  fr.readAsArrayBuffer(file);
  fr.onload = f => {
    
    const url = "https://script.google.com/macros/s/AKfycbw2twWhP2t8orw5JF5xS4a6OgcMKMFLBlX3Zzinusta-oZMFlYC5lRHRZWyCk13jEU/exec";  // <--- Please set the URL of Web Apps.
    
    const qs = new URLSearchParams({filename: form.filename.value || file.name, mimeType: file.type});
    fetch(`${url}?${qs}`, {method: "POST", body: JSON.stringify([...new Int8Array(f.target.result)])})
    .then(res => res.json())
    .then(e => console.log(e))  // <--- You can retrieve the returned value here.
    .catch(err => console.log(err));
  }
});
</script>
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
          "https://script.google.com/macros/s/AKfycbw2twWhP2t8orw5JF5xS4a6OgcMKMFLBlX3Zzinusta-oZMFlYC5lRHRZWyCk13jEU/exec",
          {
            method: "POST",
            mode:"no-cors",
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
</body>