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
    // Coba reload jika autoplay gagal
    setTimeout(() => {
      iframe.src = iframe.src;
    }, 240000); // reload setelah 240 detik
  });
</script>
