<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Aplikasi RS. Asura</title>
    <link href="http://10.10.20.250/dashboard/download.jpeg" rel="icon" type="image/png" />
    <style>
        body{font-family:sans-serif;background:#222;color:#fff;display:flex;flex-direction:column;align-items:center;justify-content:center;height:100vh}
        .queue{font-size:5rem;margin:1rem 0}
        .prefix{color:#ff0}
        .number{color:#0ff}
        button{font-size:1.2rem;padding:.5rem 1rem;margin:0 .5rem}
        input{font-size:1.2rem;padding:.5rem;width:6rem;text-align:center}
        #activateSound{
            position:fixed; bottom:10px; left:10px;
            font-size:0.8rem; padding:0.3rem 0.6rem;
            background:#4CAF50; color:#fff; border:none; border-radius:4px;
        }
    </style>
</head>
<body>

<center>Poli Umum</center>

<div class="queue">
    <span class="prefix" id="pref">A</span><span class="number" id="num">001</span>
</div>

<div>
    <button onclick="next()">Next</button>
    <input id="resetVal" type="text" placeholder="" />
    <button onclick="reset()">Reset</button>
</div>

<!-- Tombol aktivasi suara (hanya muncul kalau belum di‑init) -->
<button id="activateSound">Aktifkan Suara</button>

<script>
    const letters = ['A','B','C','D','E','F','G','H','I','J'];
    let idx = 0, cnt = 1;
    let synth = window.speechSynthesis;
    let voice = new SpeechSynthesisUtterance();
    voice.lang = 'id-ID';
    let soundReady = false;               // flag: sudah di‑init?

    // -------------------------------------------------
    // Inisialisasi suara setelah user klik tombol
    document.getElementById('activateSound').addEventListener('click', function(){
        soundReady = true;
        this.style.display = 'none';
        speakCode(letters[idx], cnt);     // ucapkan kode pertama otomatis
    });

    function pad(v){ return String(v).padStart(3,'0'); }

    // ucapkan kode lengkap, mis. "A satu"
    function speakCode(letter, number) {
        if(!soundReady) return;           // belum di‑init, keluar saja
        const angka = ['nol','satu','dua','tiga','empat','lima','enam','tujuh','delapan','sembilan'];
        const teks = `${letter} ${angka[number]}`;
        voice.text = teks;
        synth.speak(voice);
    }

    function next(){
        cnt++;
        if(cnt>999){
            cnt=1;
            idx = (idx+1)%letters.length;
        }
        document.getElementById('pref').textContent = letters[idx];
        document.getElementById('num').textContent = pad(cnt);
        speakCode(letters[idx], cnt);
    }

    function reset(){
        const val = document.getElementById('resetVal').value.trim();
        if(val){
            const m = val.match(/^([A-J])(\d{1,3})$/i);
            if(m){
                idx = letters.indexOf(m[1].toUpperCase());
                cnt = Math.max(1, Math.min(999, Number(m[2])));
            }else{
                alert('Format salah! Contoh: C015');
                return;
            }
        }else{
            idx = 0; cnt = 1;
        }
        document.getElementById('pref').textContent = letters[idx];
        document.getElementById('num').textContent = pad(cnt);
        document.getElementById('resetVal').value = '';
        speakCode(letters[idx], cnt);
    }
</script>

</body>
</html>

