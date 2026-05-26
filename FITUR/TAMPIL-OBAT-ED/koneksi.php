<?php
	date_default_timezone_set("Asia/jakarta");
	$conn = mysqli_connect("10.10.20.250", "root", "", "sikdraisyah");
	if (mysqli_connect_errno()){
		echo "Oops! Koneksi database gagal : --> " . mysqli_connect_error();
	}
?>
