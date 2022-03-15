<!DOCTYPE html>
<html lang="id" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Create Image</title>
    <style>
      .id_card{
        width: 320px;
        height: 150px;

      }
      .container{
      }
    </style>
  </head>
  <body>
      <div class="container">
        <canvas class="id_card" id="id_card">
          <div id="nama">
            Nama : Boris
          </div>
          <div class="" id="jenis_kelamin">
            Jenis Kelamin : Pria
          </div>
          <div class="" id="tgl_lahir">
            Tanggal Lahir : 26 Januari 1995
          </div>
          <div class="" id="alamat">
            Alamat : Samarinda
          </div>
        </canvas>
      </div>
      <br>
      <div class="dv-btn">
        <button type="button" id="preview" name="button">Preview</button>
      </div>
      <br>
      <div>
        <button type="button" id="download_image" name="button">download</button>
      </div>
  </body>
  <script>
    var preview = document.getElementById('preview');
    var id_card = document.getElementById('id_card');
    var download_image = document.getElementById('download_image');

    // var nama = document.getElementById('nama');
    // var jenis_kelamin = document.getElementById('jenis_kelamin');
    // var tgl_lahir = document.getElementById('tgl_lahir');
    // var alamat = document.getElementById('alamat');

    // preview.addEventListener("click",prev);
    preview.addEventListener("click",loadImage);
    CanvasRenderingContext2D.prototype.roundRect = function (x, y, width, height, radius) {
      if (width < 2 * radius) radius = width / 2;
      if (height < 2 * radius) radius = height / 2;
      this.beginPath();
      this.moveTo(x + radius, y);
      this.arcTo(x + width, y, x + width, y + height, radius);
      this.arcTo(x + width, y + height, x, y + height, radius);
      this.arcTo(x, y + height, x, y, radius);
      this.arcTo(x, y, x + width, y, radius);
      this.closePath();
      return this;
    }
    function loadImage() {

        const p1 = new Path2D();
        p1.fillStyle="#307BFA";
        p1.rect(0,0,300,50);

        const p2 = new Path2D();
        p2.fillStyle= "#2A9DDE";
        p2.rect(0,0,10,300);

        let m = new DOMMatrix();
        m.a = 28; m.b = 0;
        m.c = 0; m.d = 1;
        m.e = 10; m.f = 0;
        p1.addPath(p2, m);

        const ctx = id_card.getContext("2d");
        p2.fillStyle= "#fff";
        ctx.font = "20px Helvetica";
        ctx.rect(0,0,id_card.width,id_card.height);
        ctx.roundRect(0, 0, id_card.width, id_card.height, 8);
        ctx.fill(p1);

        // id_card.height = oldH;
        ctx.font="16px Arial";
        ctx.fillStyle = "#3BE2F5";
        ctx.fillText("PT Alamjaya Bara Pratama",10,25)
        ctx.font="10px Arial";
        ctx.fillStyle = "#123";

        ctx.fillText("Boris Reyson",10,70)
        ctx.fillText("26 Januari 1995",10,80)
        ctx.fillText("Siratiuruk",10,90)
    }

    download_image.addEventListener("click",function(){
        const imageDown = id_card.toDataURL();
        console.log(imageDown);
        const a = document.createElement("a");
        a.href = id_card.toDataURL();
        a.download = "Card.png";
        a.click();
    });
    // loadImage();
  </script>
</html>
