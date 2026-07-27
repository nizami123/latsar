<!doctype html>
<html lang="id">

<head>

<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Pengaduan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{

background:#eef2f7;

}

.card{

border-radius:15px;
border:none;

}

.preview{

display:none;
width:100%;
margin-top:10px;
border-radius:10px;

}

.loading{

display:none;
position:fixed;
left:0;
top:0;
width:100%;
height:100%;
background:rgba(255,255,255,.8);
justify-content:center;
align-items:center;
z-index:999;

}

</style>

</head>

<body>

<div class="loading" id="loading">

<div class="spinner-border text-danger"></div>

</div>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-md-8">

<div class="card shadow">

<div class="card-header bg-danger text-white">

<h3 class="mb-0">Form Pengaduan</h3>

</div>

<div class="card-body">

<form
action="<?=site_url('pengaduan/simpan');?>"
method="post"
enctype="multipart/form-data"
id="form">

<div class="mb-3">

<label>Nama</label>

<input
type="text"
name="nama"
class="form-control"
required>

</div>

<div class="mb-3">

<label>No HP</label>

<input
type="text"
name="no_hp"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Lokasi</label>

<input
type="text"
name="lokasi"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Isi Pengaduan</label>

<textarea
name="pengaduan"
class="form-control"
rows="5"
required></textarea>

</div>

<div class="mb-3">

<label>Foto Bukti</label>

<input
type="file"
name="foto"
class="form-control"
accept="image/*"
capture="environment"
required
onchange="preview(this)">

<img id="img" class="preview">

</div>

<button class="btn btn-danger w-100">

Kirim Pengaduan

</button>

</form>

</div>

</div>

</div>

</div>

</div>

<script>

function preview(input){

let file=input.files[0];

if(file){

let reader=new FileReader();

reader.onload=function(e){

let img=document.getElementById('img');

img.src=e.target.result;

img.style.display='block';

}

reader.readAsDataURL(file);

}

}

document.getElementById("form").onsubmit=function(){

document.getElementById("loading").style.display="flex";

}

</script>

</body>

</html>