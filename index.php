<?php
require ('library/Folder.php');
require ('library/ImageResize.php');
$folder = new Folder();
session_start();
ini_set('memory_limit', '1024M');
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

    <link  href="plugins/cropper/dist/cropper.min.css" rel="stylesheet">
    <script src="plugins/cropper/dist/cropper.min.js"></script>
    <script>
        function searchFunction() {
            // Declare variables
            var input, filter,url_input,url,i;
            input = document.getElementById('search');
            filter = input.value.toUpperCase();
            url_input = document.getElementById('url');
            url = url_input.value.toUpperCase();

           $('.ss').hide();

            if(filter.length > 1){
                $.ajax('search.php', {
                    type: 'POST',  // http method
                    data: { search: filter, url: url },
                    dataType:'json',
                    success: function (data, status, xhr) {
                        if(data.length > 0){
                            for(i=0; i<data.length; i++){
                                $('.ss[data-name="'+data[i]+'"]').show();
                            }
                        }else{
                            alert("Aradığınız dosya veya klasör yok...");
                        }
                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                        alert("Aradığınız dosya veya klasör yok...");
                    }
                });
            }else if(filter.length == 0 || filter.length == 1){
                $('.ss').show();
            }
        }
    </script>

</head>
<body>
<?php $folder->getRenameModal(); ?>
<?php $folder->getFileRenameModal(); ?>
<?php $folder->getAddFileModal();?>
<?php $folder->getAddFolderModal();?>
<?php $folder->getDeleteModel(); ?>
<?php $folder->getOptimizeModel(); ?>
<?php $folder->getResizeModal(); ?>
<input type="hidden" id="server" value="<?php echo 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']; ?>">
<?php $server= 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']; ?>
<?php $path= $folder->get_Path(); ?>
<div class="container-fluid">
    <div class="content">
        <div class="row">
            <?php if(isset($_GET['file'])): ?>
            <div class="col-md-3" id="status">
                <span class="close-button"><i class="fas fa-window-close"></i></span>
                <nav class="nav file-nav">
                    <?php if(isset($_GET['type'])): $type = $_GET['type']; else: $type = NULL; endif; ?>
                    <?php $folder->getStatus($_GET['file'],$type); ?>
                </nav>
            </div>
            <?php endif; ?>
            <div class="<?php if(isset($_GET['file'])): ?>col-md-9<?php else: ?>col-md-12<?php endif; ?> " id="view">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <a class="navbar-brand" href="index.php">Moon Folder Plugin</a>

                    <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                        <ul class="navbar-nav mr-auto mt-3 mt-lg-0">
                            <li class="nav-item active">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add_file" data-whatever="<?php if(isset($_GET['url'])): echo $_GET['url']; endif; ?>">Yeni Dosya Ekle</button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#add_folder" data-whatever="<?php if(isset($_GET['url'])): echo $_GET['url']; endif;?>">Yeni Klasör Ekle</button>
                            </li>
                        </ul>
                        <div class="form-inline my-2 my-lg-0">
                            <input class="form-control mr-sm-2" type="search" placeholder="Arama Yap" id="search" onkeyup="searchFunction()" aria-label="Search">
                        </div>
                    </div>
                </nav>
                <section class="folder-list">
                    <?php if(isset($_SESSION['succes'])): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $_SESSION['succes']; ?>
                    </div>
                    <?php elseif(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_SESSION['error']; ?>
                    </div>
                    <?php endif; ?>
                    <form  action="#" method="post" enctype="multipart/form-data" class="forms">
                        <input type="hidden" name="type" value="deletes" />
                        <div class="deletes"><button type="button" class="btn btn-danger deletes-button confirm">Hepsini Sil</button></div>
                        <div class="spinner-border text-primary loading" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="content" id="files">
                            <div class="row">
                                <?php
                                if(isset($_GET['url']) && isset($_GET['alt_url'])):
                                    ?>
                                <input type="hidden" id="url"  value="<?php echo $_GET['url']; ?>" />
                                <?php
                                    $folder->list($_GET['url'],$_GET['alt_url']);
                                else:
                                ?>
                                    <input type="hidden" id="url"  value="<?php echo $folder->get_Path(); ?>" />
                                <?php
                                    $folder->list();
                                endif;
                                ?>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->


<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>
<?php
if(isset($_GET['delete'])){
    unlink($_GET['delete']);
    echo'<meta http-equiv="refresh" content="0;URL='.$server.'">';
}
if(isset($_GET['delete_folder'])){
    $files = scandir($_GET['delete_folder']);
    foreach ($files as $file){
        if($file != '.' && $file != '..')
            unlink($_GET['delete_folder'].'/'.$file);
    }
    rmdir($_GET['delete_folder']);
    echo'<meta http-equiv="refresh" content="0;URL='.$server.'">';
}
if(isset($_GET['move_to']) && isset($_GET['from']) && isset($_GET['name'])){
    $move_to = $_GET['move_to'];
    $from = $_GET['from'];
    $name = $_GET['name'];
    $loc = explode('/',$from,-1);
    $location = '';
    foreach ($loc as $row){
        $location .= $row . '/';
    }
    $sub = explode('/' , $from, -2);
    $sub_url = '';
    foreach ($sub as $row){
        $sub_url .= $row . '/';
    }
    $sub_url =  trim($sub_url,'/');
    $location =  trim($location,'/');
    if(rename($move_to,$from.'/'.$name) ==  true){
        $_SESSION['succes'] = "Dosya taşıma işlemi başarılı bir şekilde gerçekleşti.";
        echo'<meta http-equiv="refresh" content="0;URL='.$server.'?url='.$location.'&alt_url='.$sub_url.'">';
    }else{
        $_SESSION['error'] = "Dosya taşıma işlemi sırasında bir sorun meydana geldi.";
        echo'<meta http-equiv="refresh" content="0;URL='.$server.'?url='.$location.'&alt_url='.$sub_url.'">';
    }
}
if(isset($_POST['type'])):
    $type = $_POST['type'];
    switch ($type):
        case 'rename':
            $file = $_POST['url'];
            $loc = explode('/',$file,-1);
            $location = '';
            foreach ($loc as $row){
                $location .= $row . '/';
            }
            $sub = explode('/' , $file, -2);
            $sub_url = '';
            foreach ($sub as $row){
                $sub_url .= $row . '/';
            }
            $name = $_POST['name'];
            $type = explode('.',$file);
            rename($file,$location.$name.'.'.$type[1]);
            $url = explode('/',$file,-1);
            $new_folder = '';
            foreach ($url as $row){
                $new_folder .= $row . '/';
            }
            $new = $new_folder.$name.'.'.$type[1];
            $sonuc = file_exists($new);
            $sub_url =  trim($sub_url,'/');
            $location =  trim($location,'/');
            if($sonuc){
                $_SESSION['succes'] = "İsim değiştirme işlemi başarılı bir şekilde gerçekleşmiştir.";
                echo'<meta http-equiv="refresh" content="0;URL='.$server.'?url='.$location.'&alt_url='.$sub_url.'">';
            }else{
                $_SESSION['error'] = "Dosya isim değiştirme işlemi sırasında hata meydana geldi";
                echo'<meta http-equiv="refresh" content="0;URL='.$server.'?url='.$location.'&alt_url='.$sub_url.'">';
            }
            break;
        case 'add_file':
            $size = $_POST['size'];
            $folder_ = $_POST['url'];
            if($folder_ == ''): $folder_= $folder->get_Path(); endif;
            $loc = explode('/',$folder_,-2);
            $location = '';
            foreach ($loc as $row){
                $location .= $row . '/';
            }
            $file_ = $folder_ .'/'.time()."-". basename($_FILES['file']['name']);
            $max_size = 1500000;
            $allowtype = array('docx', 'gif', 'jpg', 'jpeg', 'png','pdf','rar','zip','txt');
            $sepext = explode('.', strtolower($_FILES['file']['name']));
            $type_file = end($sepext);
            $error = '';

            if(!in_array($type_file, $allowtype)) $error .= '<b>'. $_FILES['file']['name']. '</b> isimli dosyanızın türü desteklenmemektedir.';
           // if($_FILES['file']['size'] > $max_size*5000) $error  .= '<br/>Maximum file size must be: '. $max_size. ' KB.';

            switch ($size){
                case 'orjinal':
                    $get_size =  getimagesize($_FILES['file']['tmp_name']);
                    $width = $get_size[0];
                    $height = $get_size[1];
                    $resize = 0;
                    break;
                case '1920x1080':
                    $width = 1920;
                    $height = 1080;
                    $resize = 1;
                    break;
                case '730x400':
                    $width = 730;
                    $height = 400;
                    $resize = 1;
                    break;
                case '640x350':
                    $width = 640;
                    $height = 350;
                    $resize = 1;
                    break;
                case '350x230':
                    $width = 350;
                    $height = 230;
                    $resize = 1;
                    break;
                case 'different':
                    $width  =  $_POST['width'];
                    $height  =  $_POST['height'];
                    $resize = 1;
                    break;
            }

            if (move_uploaded_file($_FILES['file']['tmp_name'], $file_) && !is_null($error))
            {
                $location =  trim($location,'/');
                $folder_ = trim($location,'/');

                if($resize == 1){
                    $image = new Gumlet\ImageResize($file_);
                    $image->resize($width,$height,$allow_enlarge = True);
                    $image->save($file_);
                }

                $_SESSION['succes'] = "Yeni dosya ekleme işlemi başarılı bir şekilde gerçekleşmiştir. ";
                echo'<meta http-equiv="refresh" content="0;URL='.$server.'?url='.$folder_.'&alt_url='.$location.'">';
            } else {
                $_SESSION['error'] = $error;
                echo'<meta http-equiv="refresh" content="0;URL='.$server.'?url='.$folder_.'&alt_url='.$location.'">';
            }

            break;
        case 'optimize':
            $file = $_POST['url'];
            $loc = explode('/',$file,-1);
            $location = '';
            foreach ($loc as $row){
                $location .= $row . '/';
            }
            $sub = explode('/' , $file, -2);
            $sub_url = '';
            foreach ($sub as $row){
                $sub_url .= $row . '/';
            }
            $location =  trim($location,'/');
            $sub_url = trim($sub_url,'/');
            if($_POST['file_type'] == 'png'){
                $image = new Gumlet\ImageResize($file);
                $image->quality_png = $_POST['range'];
                $image->save($file);
            }else{
                $image = new Gumlet\ImageResize($file);
                $image->quality_jpg = $_POST['range'];
                $image->save($file);
            }
            $_SESSION['succes'] = "Optimizasyon işlemi başarı ile gerçekleştirildi...";
            echo'<meta http-equiv="refresh" content="0;URL='.$server.'?url='.$location.'&alt_url='.$sub_url.'&file='.$file.'&type=image">';
            break;
        case 'resize':
            $file = $_POST['url'];
            $size = $_POST['size'];
            $loc = explode('/',$file,-1);
            $location = '';
            foreach ($loc as $row){
                $location .= $row . '/';
            }
            $sub = explode('/' , $file, -2);
            $sub_url = '';
            foreach ($sub as $row){
                $sub_url .= $row . '/';
            }
            switch ($size){
                case '1920x1080':
                    $width = 1920;
                    $height = 1080;
                    $resize = 1;
                    break;
                case '730x400':
                    $width = 730;
                    $height = 400;
                    $resize = 1;
                    break;
                case '640x350':
                    $width = 640;
                    $height = 350;
                    $resize = 1;
                    break;
                case '350x230':
                    $width = 350;
                    $height = 230;
                    $resize = 1;
                    break;
                case 'different':
                    $width  =  $_POST['width'];
                    $height  =  $_POST['height'];
                    $resize = 1;
                    break;
            }

            $image = new Gumlet\ImageResize($file);
            $image->resize($width,$height,$allow_enlarge = True);
            $image->save($file);
            $location =  trim($location,'/');
            $sub_url = trim($sub_url,'/');
            $check =  getimagesize($file);
            if($check[0] == $width  && $check[1] == $height){
                $_SESSION['succes'] = "Boyutlandırma işlemi başarı ile gerçekleştirildi...";
                echo'<meta http-equiv="refresh" content="0;URL='.$server.'?url='.$location.'&alt_url='.$sub_url.'&file='.$file.'&type=image">';
            }else{
                $_SESSION['error'] = "Boyutlandırma işlemi sırasın da bir hata meydanda geldi...";
                echo'<meta http-equiv="refresh" content="0;URL='.$server.'?url='.$location.'&alt_url='.$sub_url.'&file='.$file.'&type=image">';
            }
            break;
        case 'deletes':
            $url = $_POST['url'];
            foreach ($url as $row){
                if(is_dir($row)){
                    rmdir($row);
                }else{
                    unlink($row);
                }
            }
            echo'<meta http-equiv="refresh" content="0;URL='.$server.'">';
            echo "<script type='text/javascript'>window.top.location='".$server."';</script>"; exit;
            break;
        case 'file_rename':
            $file = $_POST['url'];
            $name = $_POST['name'];
            $loc = explode('/',$file,-1);
            $location = '';
            foreach ($loc as $row){
                $location .= $row . '/';
            }
            $sub = explode('/',$file,-2);
            $sub_url = '';
            foreach ($sub as $row){
                $sub_url .= $row . '/';
            }
            if(rename($file,$location.$name) == true){
                $location = trim($location,'/');
                $sub_url = trim($sub_url,'/');
                $_SESSION['succes'] = "İsim Değiştirme işlemi başarılı";
                echo'<meta http-equiv="refresh" content="0;URL='.$server.'?url='.$location.'&alt_url='.$sub_url.'&file='.$file.'&type=folder">';
            }else{
                $_SESSION['error'] = "İsim Değiştirme işlemi sırasında bir sorun oluştu.";
                echo'<meta http-equiv="refresh" content="0;URL='.$server.'?url='.$location.'&alt_url='.$sub_url.'&file='.$file.'&type=folder">';
            }
            break;
        case 'add_folder':
            $file = $_POST['url'];
            $name = $_POST['name'];
            $loc = explode('/',$file);
            $location = '';
            foreach ($loc as $row){
                $location .= $row . '/';
            }
            $sub = explode('/',$file,-1);
            $sub_url = '';
            foreach ($sub as $row){
                $sub_url .= $row . '/';
            }
            $folder = $file.'/'.$name;
            if(mkdir($folder, 0655) == true){
                $location = trim($location,'/');
                $sub_url = trim($sub_url,'/');
                $_SESSION['succes'] = "Klasör ekleme işlemi başarılı";
                echo'<meta http-equiv="refresh" content="0;URL='.$server.'?url='.$location.'&alt_url='.$sub_url.'&file='.$folder.'&type=folder">';
            }else{
                $location = trim($location,'/');
                $sub_url = trim($sub_url,'/');
                $_SESSION['error'] = "Klasör ekleme işlemi sırasında bir sorun oluştu.";
                echo'<meta http-equiv="refresh" content="0;URL='.$server.'?url='.$location.'&alt_url='.$sub_url.'&file='.$folder.'&type=folder">';
            }
            break;
        case 'crop':
            $url = $_POST['url'];
            $data = $_POST['data'];
            $data = json_decode($data);
            $loc = explode('/',$url,-1);
            $location = '';
            foreach ($loc as $row){
                $location .= $row . '/';
            }
            $sub = explode('/',$url,-2);
            $sub_url = '';
            foreach ($sub as $row){
                $sub_url .= $row . '/';
            }

            $location = trim($location,'/');
            $sub_url = trim($sub_url,'/');

            $image = new Gumlet\ImageResize($url);
            $image->freecrop($data->width, $data->height, $x =  $data->x, $y = $data->y);
            $image->save($url);
            $_SESSION['succes'] = "Kırpma işlemi başarılı";
            echo'<meta http-equiv="refresh" content="0;URL='.$server.'?url='.$location.'&alt_url='.$sub_url.'&file='.$url.'&type=image">';
            break;
    endswitch;
endif;
?>
<?php session_destroy(); ?>
