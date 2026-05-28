<?php

// Vercel-compatible authentication check (NO sessions)
if (!isset($_GET['auth']) || $_GET['auth'] !== "1") {
    header("Location: /login");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Image Template Generator</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#f4f6f9; }

.toolbar, .preview-box {
    background:#fff;
    padding:15px;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
}

canvas {
    width:100%;
    border-radius:8px;
}
</style>
</head>

<body>

<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand">Image Template Generator</span>

    <!-- logout just redirects back to login -->
    <a href="/login" class="btn btn-danger btn-sm">Logout</a>
</nav>

<div class="container mt-4">
<div class="row g-3">

<!-- LEFT PANEL -->
<div class="col-md-4">
<div class="toolbar">

<h5>Input Data</h5>

<input type="file" id="uploadImage" class="form-control mb-2">

<label>Date</label>
<input type="date" id="dateInput" class="form-control mb-2">

<label>Time</label>
<input type="time" id="timeInput" class="form-control mb-2">

<label>VSR Number</label>
<input type="text" id="vsr" class="form-control mb-2">

<label>No. of Boxes</label>
<input type="number" id="boxes" class="form-control mb-2">

<label>Address</label>
<select id="addressSelect" class="form-select mb-2" onchange="toggleCustomAddress()">
    <option value="">Select Address</option>
    <option value="317 - Ace Lanang Premier">317 - Ace Lanang Premier</option>
    <option value="custom">Custom Address</option>
</select>

<input type="text" id="customAddress" class="form-control mb-2 d-none">

<label>Counted By</label>
<input type="text" id="countedBy" class="form-control mb-2">

<label>Description</label>
<input type="text" id="description" class="form-control mb-2">

<button class="btn btn-primary w-100 mb-2" onclick="generateImage()">Generate</button>
<button class="btn btn-success w-100 mb-2" onclick="downloadImage()">Download</button>
<button class="btn btn-outline-secondary w-100" onclick="resetCanvas()">Reset</button>

</div>
</div>

<!-- PREVIEW -->
<div class="col-md-8">
<div class="preview-box text-center">

<canvas id="canvas"></canvas>

<div id="status" class="mt-2 text-muted small">
No image loaded
</div>

</div>
</div>

</div>
</div>

<script>
let canvas = document.getElementById("canvas");
let ctx = canvas.getContext("2d");

let image = new Image();
let logo = new Image();
logo.src = "/assets/logo.png";

let status = document.getElementById("status");

/* IMAGE UPLOAD */
document.getElementById("uploadImage").addEventListener("change", function(e){
    let reader = new FileReader();

    reader.onload = function(event){
        image = new Image();

        image.onload = function(){
            canvas.width = image.width;
            canvas.height = image.height;

            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(image,0,0);

            status.innerText = "Image loaded";
        };

        image.src = event.target.result;
    };

    reader.readAsDataURL(e.target.files[0]);
});

/* ADDRESS TOGGLE */
function toggleCustomAddress() {
    let select = document.getElementById("addressSelect");
    let custom = document.getElementById("customAddress");

    if (select.value === "custom") {
        custom.classList.remove("d-none");
    } else {
        custom.classList.add("d-none");
        custom.value = "";
    }
}

/* ADDRESS RESOLVE */
function resolveAddress() {
    let select = document.getElementById("addressSelect").value;
    let custom = document.getElementById("customAddress").value.trim();

    if (select === "custom") return custom !== "" ? custom : "-";
    if (select !== "") return select;
    return "-";
}

/* GENERATE */
function generateImage(){

    if(!image.src){
        alert("Upload image first");
        return;
    }

    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.drawImage(image,0,0);

    let now = new Date();

    let date = document.getElementById("dateInput").value || now.toISOString().split("T")[0];
    let time = document.getElementById("timeInput").value || now.toTimeString().slice(0,5);

    let vsr = document.getElementById("vsr").value || "-";
    let boxes = document.getElementById("boxes").value || "0";
    let address = resolveAddress();
    let countedBy = document.getElementById("countedBy").value || "-";
    let desc = document.getElementById("description").value || "-";

    let baseY = canvas.height - 150;
    let lineHeight = 28;

    ctx.fillStyle = "rgba(0,0,0,0.60)";
    ctx.fillRect(0, canvas.height - 180, canvas.width, 180);

    ctx.fillStyle = "#fff";
    ctx.font = "18px Arial";

    ctx.fillText("Date: " + date + " | Time: " + time, 20, baseY);
    ctx.fillText("VSR No: " + vsr, 20, baseY + lineHeight);
    ctx.fillText("Boxes: " + boxes + " | Counted By: " + countedBy, 20, baseY + lineHeight * 2);
    ctx.fillText("Address: " + address, 20, baseY + lineHeight * 3);
    ctx.fillText("Description: " + desc, 20, baseY + lineHeight * 4);

    if (logo.complete) {
        ctx.drawImage(logo, canvas.width - 100, 20, 80, 80);
    }

    status.innerText = "Generated successfully";
}

/* DOWNLOAD */
function downloadImage(){
    let link = document.createElement("a");
    link.download = "template.png";
    link.href = canvas.toDataURL("image/png");
    link.click();
}

/* RESET */
function resetCanvas(){
    ctx.clearRect(0,0,canvas.width,canvas.height);
    image = new Image();
    status.innerText = "Reset done";
}
</script>

</body>
</html>