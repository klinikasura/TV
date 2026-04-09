<div style="margin-top: 100px; text-align: center;">
  <iframe 
    id="ytplayer"
    width="664" 
    height="580" 
    src="https://www.youtube.com/embed/DOOrIxw5xOw?autoplay=1&mute=1" 
    title="YouTube video player" 
    frameborder="0" 
    allow="autoplay; encrypted-media"
    allowfullscreen>
  </iframe>
</div>

<script>
  const iframe = document.getElementById("ytplayer");

  iframe.addEventListener("load", function() {
    // Reload setelah 10 menit
    setTimeout(() => {
      iframe.src = iframe.src;
    }, 600000); // 600000 ms = 10 menit
  });
</script>

<script>
  const iframe = document.getElementById("ytplayer");
  const adzan = document.getElementById("adzanAudio");

  // Kirim perintah ke YouTube iframe
  function postToPlayer(command) {
    iframe.contentWindow.postMessage(JSON.stringify({
      event: "command",
      func: command,
      args: []
    }), "*");
  }

  // Saat adzan mulai
  adzan.addEventListener("play", () => {
    postToPlayer("mute");
    console.log("YouTube dimute karena adzan");
  });

  // Saat adzan selesai
  adzan.addEventListener("ended", () => {
    iframe.src = iframe.src; // refresh iframe
    console.log("Adzan selesai, YouTube di-refresh");
  });

  // Contoh trigger adzan manual
  // adzan.play();
</script>
