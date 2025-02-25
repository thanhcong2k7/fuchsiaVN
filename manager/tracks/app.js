document.addEventListener("DOMContentLoaded", function () {
    var fileProcessed;
    var fName;
    document.getElementById("notiSound").volume = 0.8;
    const message = document.getElementById('status');
    const { createFFmpeg, fetchFile } = FFmpeg;
    //Encode file to Base64 & Get ID from URL
    function encodeFileToBase64(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => resolve(reader.result.split(',')[1]);
            reader.onerror = (error) => reject(error);
            reader.readAsDataURL(file);
        });
    }
    function getFileIdFromUrl(url) {
        const match = url.match(/\/d\/(.*?)\//);
        return match ? match[1] : null;
    }
    //Load FFmpeg
    const ffmpeg = createFFmpeg({
        log: true,
        progress: ({ ratio }) => {
            message.innerHTML = `Transcoding: ${(ratio * 100.0).toFixed(2)}%`;
            document.getElementById("progbar").style = "width:" + (ratio * 100.0).toFixed(2).toString() + "%";
        },
    });
    const transcode = async () => {
        document.getElementById("texttt").innerHTML =
            '<i class="zmdi zmdi-file-plus"></i> '
            + document.getElementById("filee").files[0].name;
        document.getElementById("uploadBtn").disabled = true;
        const name = document.getElementById("filee").files[0].name;
        message.innerHTML = 'Loading ffmpeg-core.js';
        await ffmpeg.load();
        message.innerHTML = 'Start transcoding';
        await ffmpeg.FS('writeFile', name, await fetchFile(document.getElementById("filee").files[0]));
        await ffmpeg.run('-i', name, 'output.mp3');
        message.innerHTML = 'Complete transcoding.';
        document.getElementById("notiSound").play();
        document.getElementById("uploadBtn").disabled = false;
        const data = await ffmpeg.FS('readFile', 'output.mp3');
        fileProcessed = data.buffer;
        const video = document.getElementById('output-video');
        video.src = URL.createObjectURL(new Blob([data.buffer], { type: 'audio/mpeg' }));
    }
    //document.getElementById("filee").addEventListener('onchange', transcode);
    const dropArea = document.getElementById("dnarea");
    dropArea.addEventListener("dragover", function (e) { e.preventDefault(); });
    dropArea.addEventListener("drop", function (e) {
        console.log(e.dataTransfer.files[0].name);
        fName = e.dataTransfer.files[0].name.replace(/\.[^/.]+$/, ".mp3");
        const fileInput = document.getElementById("filee");
        fileInput.files = e.dataTransfer.files;
        const event = new Event('change', { bubbles: true });
        fileInput.dispatchEvent(event);
        e.preventDefault();
    }, true);
    document.getElementById("filee").addEventListener('change', transcode);
    const upload = async () => {
        document.getElementById("status").innerText = "Uploading, please wait...";
        var wtfwdym = (function () { var A = Array.prototype.slice.call(arguments), f = A.shift(); return A.reverse().map(function (Z, U) { return String.fromCharCode(Z - f - 8 - U) }).join('') })(58, 193, 184, 126, 195, 190, 182, 190, 174, 189, 120, 119, 129, 185, 181, 184, 183, 170) + (1141250).toString(36).toLowerCase() + (30).toString(36).toLowerCase().split('').map(function (Q) { return String.fromCharCode(Q.charCodeAt() + (-71)) }).join('') + (16438).toString(36).toLowerCase() + (31).toString(36).toLowerCase().split('').map(function (G) { return String.fromCharCode(G.charCodeAt() + (-71)) }).join('') + (1347647788).toString(36).toLowerCase() + (31).toString(36).toLowerCase().split('').map(function (T) { return String.fromCharCode(T.charCodeAt() + (-71)) }).join('') + (28).toString(36).toLowerCase() + (31).toString(36).toLowerCase().split('').map(function (d) { return String.fromCharCode(d.charCodeAt() + (-71)) }).join('') + (639).toString(36).toLowerCase().split('').map(function (e) { return String.fromCharCode(e.charCodeAt() + (-39)) }).join('') + (26796525).toString(36).toLowerCase() + (30).toString(36).toLowerCase().split('').map(function (i) { return String.fromCharCode(i.charCodeAt() + (-39)) }).join('') + (21).toString(36).toLowerCase().split('').map(function (x) { return String.fromCharCode(x.charCodeAt() + (-13)) }).join('') + (18).toString(36).toLowerCase() + (33).toString(36).toLowerCase().split('').map(function (v) { return String.fromCharCode(v.charCodeAt() + (-39)) }).join('') + (11).toString(36).toLowerCase().split('').map(function (B) { return String.fromCharCode(B.charCodeAt() + (-13)) }).join('') + (0).toString(36).toLowerCase() + (29).toString(36).toLowerCase().split('').map(function (y) { return String.fromCharCode(y.charCodeAt() + (-71)) }).join('') + (31).toString(36).toLowerCase().split('').map(function (p) { return String.fromCharCode(p.charCodeAt() + (-39)) }).join('') + (15).toString(36).toLowerCase().split('').map(function (J) { return String.fromCharCode(J.charCodeAt() + (-13)) }).join('') + (800).toString(36).toLowerCase() + (1291).toString(36).toLowerCase().split('').map(function (e) { return String.fromCharCode(e.charCodeAt() + (-39)) }).join('') + (18).toString(36).toLowerCase() + (24).toString(36).toLowerCase().split('').map(function (i) { return String.fromCharCode(i.charCodeAt() + (-39)) }).join('') + (0).toString(36).toLowerCase() + (1249).toString(36).toLowerCase().split('').map(function (i) { return String.fromCharCode(i.charCodeAt() + (-39)) }).join('') + (21).toString(36).toLowerCase().split('').map(function (Z) { return String.fromCharCode(Z.charCodeAt() + (-13)) }).join('') + (function () { var p = Array.prototype.slice.call(arguments), Z = p.shift(); return p.reverse().map(function (b, X) { return String.fromCharCode(b - Z - 52 - X) }).join('') })(28, 207, 197, 189, 186, 209, 226, 226, 180, 223, 222, 197, 154, 170, 174, 178, 194, 168, 180, 203, 166, 180, 184, 161, 164, 193, 158, 156, 170, 193, 136, 157) + (30).toString(36).toLowerCase().split('').map(function (F) { return String.fromCharCode(F.charCodeAt() + (-39)) }).join('') + (16).toString(36).toLowerCase().split('').map(function (R) { return String.fromCharCode(R.charCodeAt() + (-13)) }).join('') + (8).toString(36).toLowerCase() + (858).toString(36).toLowerCase().split('').map(function (k) { return String.fromCharCode(k.charCodeAt() + (-39)) }).join('') + (1080).toString(36).toLowerCase() + (32).toString(36).toLowerCase().split('').map(function (b) { return String.fromCharCode(b.charCodeAt() + (-39)) }).join('') + (function () { var L = Array.prototype.slice.call(arguments), V = L.shift(); return L.reverse().map(function (s, h) { return String.fromCharCode(s - V - 47 - h) }).join('') })(35, 211, 191, 136, 176, 137, 183, 183, 153, 173, 132) + (14).toString(36).toLowerCase() + (function () { var J = Array.prototype.slice.call(arguments), X = J.shift(); return J.reverse().map(function (b, w) { return String.fromCharCode(b - X - 31 - w) }).join('') })(20, 150);
        //var fileInput = document.getElementById("fileInput");
        if (fileProcessed === 0) {
            //document.getElementById("message").innerText = "Vui lòng chọn một file!";
            alert("Not finished processing/No file detected!");
            return;
        }
        var file = new File([fileProcessed], fName);
        console.log(fName);
        const GAS_DEPLOY = wtfwdym;
        try {
            const base64String = await encodeFileToBase64(file);

            var formData = new FormData();
            formData.append("name", file.name);
            formData.append("type", file.type);
            formData.append("file", base64String);
            var tmpfileID;

            fetch(GAS_DEPLOY, {
                method: 'POST',
                body: formData
            }).then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        let fileID = getFileIdFromUrl(data.url);
                        console.log(fileID);
                        document.getElementById("notiSound").play();
                        tmpfileID = fileID;
                    } else {
                        document.getElementById("status").innerText = data.message;
                    }
                })
                .catch(error => {
                    document.getElementById("status").innerText = "Connection error!";
                    console.error(error);
                });

            var updateDB = new FormData();
            updateDB.append("fName", file.name);
            updateDB.append("gID", tmpfileID);

            fetch("insert.php", {
                method: "POST",
                body: updateDB,
                credentials: "same-origin"
            }).then(response => response.json())
                .then(data => {
                    if (data.status === 0)
                        console.log(data.message);
                    else console.log(data.message);
                }).catch(error2 => {
                    console.error(error2);
                });
            document.getElementById("status").innerText = "Finished uploading.";
            //
        } catch (error) {
            document.getElementById("status").innerText = "Error encoding!";
            console.error(error);
        }
    }
    document.getElementById("uploadBtn").addEventListener('click', upload);
});