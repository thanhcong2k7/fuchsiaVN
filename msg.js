import axios from 'axios';
import fs from 'fs';

async function fetchInitialContinuation(videoId) {
  // 1) grab the pop-out chat HTML
  const url = `https://www.youtube.com/live_chat?is_popout=1&v=EXA4rF1lkNE`;
  const resp = await axios.get(url,
{
    headers: {
      // Pretend to be Chrome 135 on Windows
      'User-Agent':
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 ' +
        '(KHTML, like Gecko) Chrome/135.0.7049.116 Safari/537.36',
      'Accept-Language': 'en-US,en;q=0.9',
      // You can also add:
      // 'Cookie': 'VISITOR_INFO1_LIVE=<your visitor token>; …',
      // 'x-youtube-client-name': '1',
      // 'x-youtube-client-version': '2.20250606.01.00',
    },
  });
  const html = resp.data;
  fs.writeFile("livechat.html",html,
	(exception)=>console.error(exception));
//const html = /* the fetched markup as a single string */;
const m = html.match(/"reloadContinuationData":\{"continuation":"([^"]+)"/);
if (!m) throw new Error("continuation token not found");
const token = m[1];
console.log("Next continuation:", token);
  return token;
}

// usage
fetchInitialContinuation("EXA4rF1lkNE")
  .then(token => console.log("Initial continuation:", token))
  .catch(console.error);
