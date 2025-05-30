document.addEventListener("DOMContentLoaded", async function () {
    var fileProcessed;
    var fName;
    var duration;
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
        if (!ffmpeg.isLoaded()){
            alert('Please wait until FFmpeg is loaded!');
            return;
        }
        document.getElementById("texttt").innerHTML =
            '<i class="zmdi zmdi-file-plus"></i> '
            + document.getElementById("filee").files[0].name;
        document.getElementById("uploadBtn").disabled = true;
        const name = document.getElementById("filee").files[0].name;
        message.innerHTML = 'Start transcoding';
        await ffmpeg.FS('writeFile', name, await fetchFile(document.getElementById("filee").files[0]));
        await ffmpeg.run('-i', name, 'output.mp3');
        message.innerHTML = 'Complete transcoding!';
        document.getElementById("notiSound").play();
        document.getElementById("uploadBtn").disabled = false;
        const data = await ffmpeg.FS('readFile', 'output.mp3');
        fileProcessed = data.buffer;
        const video = document.getElementById('output-video');
        video.src = URL.createObjectURL(new Blob([data.buffer], { type: 'audio/mpeg' }));
        // Helper function to get duration from audio buffer
        const getAudioDuration = (arrayBuffer) => {
            return new Promise((resolve, reject) => {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                audioContext.decodeAudioData(arrayBuffer, (buffer) => {
                    resolve(buffer.duration);
                }, reject);
            });
        };

        // Fetch and decode the audio to get duration
        const response = await fetch(video.src);
        const arrayBuffer = await response.arrayBuffer();
        duration = await getAudioDuration(arrayBuffer); // Now holds duration in seconds
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

            fetch(GAS_DEPLOY, {
                method: 'POST',
                body: formData
            }).then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        let fileID = getFileIdFromUrl(data.url);
                        var updateDB = new FormData();
                        updateDB.append("fName", file.name);
                        updateDB.append("gID", fileID);
                        updateDB.append("duration", duration);

                        fetch("insert.php", {
                            method: "POST",
                            body: updateDB,
                            credentials: "same-origin"
                        }).then(response => response.json())
                            .then(data => {
                                if (data.status === 1)
                                    console.log(data.message);
                                else console.log(data.message);
                            }).catch(error2 => {
                                console.error(error2);
                            });
                        document.getElementById("status").innerText = "Finished uploading.";
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
            //
        } catch (error) {
            document.getElementById("status").innerText = "Error encoding!";
            console.error(error);
        }
    }
    document.getElementById("uploadBtn").addEventListener('click', upload);
    async function doUntil(conditionFn, actionFn, interval = 500) {
        while (!conditionFn()) {
            actionFn();
            await new Promise(res => setTimeout(res, interval));
        }
    }
    async function loadFfmpegWithStatus(ffmpeg) {
        // initial message
        message.innerHTML = 'Loading ffmpeg-core.js';

        // kick off the actual loading
        const loadPromise = ffmpeg.load();

        // every 500ms, append a dot until .isLoaded() goes true
        await doUntil(
            () => ffmpeg.isLoaded(),
            () => {
                //
            },
            500
        );

        // now await the original load promise in case it’s still running
        await loadPromise;

        // finally update UI
        message.innerHTML = '✓ ffmpeg-core.js loaded!';
    }
    loadFfmpegWithStatus(ffmpeg);



    $('#editTrackModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const trackID = button.data('trackid');
        const modal = $(this);

        // Show loading state
        modal.find('.modal-loading').show();
        modal.find('#modalFormContent').hide();

        // Load form content
        fetch(`edit-track-form.php?trackID=${trackID}`)
            .then(response => response.text())
            .then(html => {
                modal.find('#modalFormContent').html(html).show();
                modal.find('.modal-loading').hide();
            })
            .catch(error => {
                modal.find('.modal-loading').html(
                    `<div class="alert alert-danger">Error loading form: ${error.message}</div>`
                );
            });
    });

    // Add loading spinner HTML to modal body
    const modalBody = `
<div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
  <div id="modalLoading" class="text-center py-4">
    <div class="spinner-border text-primary" role="status">
      <span class="sr-only">Loading...</span>
    </div>
    <p class="mt-2">Loading track data...</p>
  </div>
  <div id="modalContent" style="display: none;">
    <!-- Your existing form content here -->
  </div>
</div>`;

    // Modal show handler
    $('#editTrackModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const trackID = button.data('trackid');
        const modal = $(this);

        // Show loading state
        $('#modalLoading').show();
        $('#modalContent').hide();

        // Fetch track data
        fetch(`api/get_track_data.php?trackID=${trackID}`)
            .then(response => {
                if (!response.ok) throw new Error('Network error');
                return response.json();
            })
            .then(data => {
                populateModalForm(data);
                $('#modalLoading').hide();
                $('#modalContent').show();
            })
            .catch(error => {
                $('#modalLoading').html(`
                <div class="alert alert-danger">
                    Error loading track data: ${error.message}
                </div>
            `);
            });
    });

    // Form population function
    function populateModalForm(data) {
        const { track, release, artists } = data;

        // Populate basic fields
        document.querySelector('input[name="tracktitle"]').value = track.name;
        document.querySelector('input[name="trackversion"]').value = track.version;
        document.querySelector('input[name="pyear"]').value = track.pyear;

        // Populate artist datalist
        const artistList = document.getElementById('artist-list');
        artistList.innerHTML = artists.map(artist =>
            `<option value="${artist.id}">${artist.name}</option>`
        ).join('');

        // Populate existing artists in table
        const tbody = document.querySelector('#selected-artists-table tbody');
        tbody.innerHTML = track.artists.map((artist, index) => `
        <tr data-id="${artist.id}" id="${index}">
            <td>${artist.name}</td>
            <td>
                <select class="role-selector" multiple>
                    ${artist.roles.map(role =>
            `<option value="${role}" selected>${role}</option>`
        ).join('')}
                </select>
            </td>
            <td>
                <button class="btn btn-link text-danger p-0" onclick="removeArtist(this)">
                    Remove
                </button>
            </td>
        </tr>
    `).join('');

        // Initialize selectize
        $(".role-selector").selectize({ /* ... */ });
    }
});
