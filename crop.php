<?php
require ('library/Folder.php');
require ('library/ImageResize.php');
$folder = new Folder();
session_start();
if(!isset($_GET['url'])){ return 'Dosya seçmelisiniz...';}else{ $url = $_GET['url'];}
//ini_set('memory_limit', '1024M');
?>
<!doctype html>
<html lang="tr">
<head>
    <!-- Required meta tags -->
    <title>Folder Menager</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="http://www.oguzhanayyildiz.com/assets/public/plugins/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/main.css"/>
    <link rel="stylesheet" href="plugins/fontawesome/css/all.min.css"/>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body>

<div class="container">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <img id="image" src="<?php echo $url; ?>">
                </div>
                <div class="form-group">
                    <button type="button" id="button" class="btn btn-info btn-sm">Crop</button>
                </div>
                <div class="row">
                    <div id="result"></div>
                    <div class="message">Değişikleriniz tamamlandıysa lütfen Onaylayınız...</div>
                </div>
                <form action="index.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="url" class="url" value="<?php echo $url; ?>">
                        <input type="hidden" name="type" value="crop">
                        <input type="hidden" name="data" id="data" required/>
                    </div>
                    <div class="modal-footer">
                        <a href="index.php?file=<?php echo $url; ?>&type=image" class="btn btn-secondary">İptal</a>
                        <button type="submit" class="btn btn-primary">Onayla</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>
<link  href="plugins/cropper/dist/cropper.min.css" rel="stylesheet">
<script src="plugins/cropper/dist/cropper.min.js"></script>
<script>
    window.addEventListener('DOMContentLoaded', function () {
        var image = document.querySelector('#image');
        var data = document.querySelector('#data');
        var cropBoxData = document.querySelector('#cropBoxData');
        var button = document.getElementById('button');
        var result = document.getElementById('result');
        var cropper = new Cropper(image, {
            ready: function (event) {
                // Zoom the image to its natural size
                cropper.zoomTo(1);
            },

            crop: function (event) {
                $("#data").val(JSON.stringify(cropper.getData()));
                /*data.textContent = JSON.stringify(cropper.getData());
                cropBoxData.textContent = JSON.stringify(cropper.getCropBoxData());*/
            },

            zoom: function (event) {
                // Keep the image in its natural size
                if (event.detail.oldRatio === 1) {
                    event.preventDefault();
                }
            },
        });

        button.onclick = function () {
            result.innerHTML = '';
            result.appendChild(cropper.getCroppedCanvas());
        };
    });
</script>