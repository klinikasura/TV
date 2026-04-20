<?php
 session_start();
 require_once('conf/conf.php');
 header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
 header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); 
 header("Cache-Control: no-store, no-cache, must-revalidate"); 
 header("Cache-Control: post-check=0, pre-check=0", false);
 header("Pragma: no-cache"); // HTTP/1.0
 $tanggal= mktime(date("m"),date("d"),date("Y"));
 date_default_timezone_set('Asia/Jakarta');
 $jam=date("H:i");
?>


<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="css/default.css" rel="stylesheet" type="text/css" />
<link href="http://10.10.20.250/dashboard/download.jpeg" rel="icon" type="image/png" />
    <script type="text/javascript" src="conf/validator.js"></script>
    <meta http-equiv="refresh" content="20"/>
   <title>Aplikasi RS. Asura</title>
    <script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
    <script src="Scripts/AC_ActiveX.js" type="text/javascript"></script>
	<style type="text/css">
	
	</style>



</head>
<body>


	
   
	<table width='100%' bgcolor='FFFFFF' border='0' align='center' cellpadding='0' cellspacing='0'>
	     <tr class='head4'>
              <td width='10%'><div align='center'><font size='2'><b>NAMA</b></font></div></td>
              <td width='10%'><div align='center'><font size='2'><b>POLIKLINIK</b></font></div></td>
              
              <td width='10%'><div align='center'><font size='2'><b>REGISTER</b></font></div></td>
         </tr>

	<?php  
	    $hari=getOne("select DAYNAME(current_date())");
	    $namahari="";
	    if($hari=="Sunday"){
			$namahari="AKHAD";
		}else if($hari=="Monday"){
			$namahari="SENIN";
		}else if($hari=="Tuesday"){
			$namahari="SELASA";
		}else if($hari=="Wednesday"){
			$namahari="RABU";
		}else if($hari=="Thursday"){
			$namahari="KAMIS";
		}else if($hari=="Friday"){
			$namahari="JUMAT";
		}else if($hari=="Saturday"){
			$namahari="SABTU";
		}
		$_sql="Select dokter.nm_dokter,poliklinik.nm_poli,jadwal.jam_mulai,jadwal.jam_selesai,poliklinik.kd_poli, 
		       dokter.kd_dokter from jadwal inner join dokter inner join poliklinik on dokter.kd_dokter=jadwal.kd_dokter 
		       and jadwal.kd_poli=poliklinik.kd_poli where jadwal.hari_kerja='$namahari'" ;  
		$hasil=bukaquery($_sql);

		while ($data = mysqli_fetch_array ($hasil)){
			echo "<tr class='isi7' >
                                <td align='left'><font size='3' color='#BB00BB' face='Tahoma'><a href='antrian-github.php?iyem=".encrypt_decrypt("{\"kd_poli\":\"".$data['kd_poli']."\",\"kd_dokter\":\"".$data['kd_dokter']."\"}","e")."'>".$data['nm_dokter']." </a></font></td>
                                <td align='center'><font size='3' color='gray' face='Tahoma'>".$data['nm_poli']."</font></td>
                               
                                <td align='center'><font color='#555555' size='3'  face='Tahoma'>". getOne("select count(*) from reg_periksa where kd_poli='".$data['kd_poli']."' and kd_dokter='".$data['kd_dokter']."' and tgl_registrasi='".date("Y-m-d", $tanggal)."'")."</font></td>
			    </tr> ";
		}
	?>
	</table>
	

