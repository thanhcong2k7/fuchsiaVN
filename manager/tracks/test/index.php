<!DOCTYPE html>
<?php
header("Cross-Origin-Embedder-Policy: require-corp");
header("Cross-Origin-Resource-Policy: cross-origin");
?>
<style>
html, body {
  margin: 0;
  width: 100%;
  height: 100%
}
body {
  display: flex;
  flex-direction: column;
  align-items: center;
}
</style>
<script src="/assets/js/ffmpeg.min.js"></script>
<h3>Upload a video to transcode to mp4 (x264) and play!</h3>
<audio id="output-video" controls></audio><br/>
<input type="file" id="uploader">
<p id="message" />
<script>
const message = document.getElementById('message');
const { createFFmpeg, fetchFile } = FFmpeg;
const ffmpeg = createFFmpeg({
  log: true,
  progress: ({ ratio }) => {
    message.innerHTML = `Complete: ${(ratio * 100.0).toFixed(2)}%`;
  },
});

const transcode = async ({ target: { files }  }) => {
  const { name } = files[0];
  message.innerHTML = 'Loading ffmpeg-core.js';
  await ffmpeg.load();
  message.innerHTML = 'Start transcoding';
  ffmpeg.FS('writeFile', name, await fetchFile(files[0]));
  await ffmpeg.run('-i', name,  'output.mp3');
  message.innerHTML = 'Complete transcoding';
  const data = ffmpeg.FS('readFile', 'output.mp3');
 
  const video = document.getElementById('output-video');
  video.src = URL.createObjectURL(new Blob([data.buffer], { type: 'audio/mpeg' }));
}
document.getElementById('uploader').addEventListener('change', transcode);
</script>