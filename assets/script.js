let canvas = document.getElementById("canvas");
let ctx = canvas.getContext("2d");

let image = new Image();
let logo = new Image();
logo.src = "assets/logo.png";

document.getElementById("uploadImage").addEventListener("change", function (e) {
    let reader = new FileReader();
    reader.onload = function (event) {
        image.src = event.target.result;
    };
    reader.readAsDataURL(e.target.files[0]);
});

function generateImage() {
    if (!image.src) return alert("Please upload an image");

    image.onload = function () {

        canvas.width = image.width;
        canvas.height = image.height;

        ctx.drawImage(image, 0, 0);

        // Date & Time
        let now = new Date();
        let dateTime = now.toLocaleString();

        // Description
        let desc = document.getElementById("description").value;

        // Overlay style
        ctx.fillStyle = "rgba(0,0,0,0.5)";
        ctx.fillRect(0, canvas.height - 120, canvas.width, 120);

        ctx.fillStyle = "white";
        ctx.font = "20px Arial";
        ctx.fillText(dateTime, 20, canvas.height - 80);
        ctx.fillText(desc, 20, canvas.height - 50);

        // Logo watermark
        logo.onload = function () {
            ctx.drawImage(logo, canvas.width - 120, 20, 100, 100);
        };

        ctx.drawImage(logo, canvas.width - 120, 20, 100, 100);
    };

    image.src = image.src;
}

function downloadImage() {
    let link = document.createElement("a");
    link.download = "generated-image.png";
    link.href = canvas.toDataURL();
    link.click();
}