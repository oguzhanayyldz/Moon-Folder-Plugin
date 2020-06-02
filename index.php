<?php
require ('library/Folder.php');
$folder = new Folder();
session_start();
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
</head>
<body>
<?php $folder->getRenameModal(); ?>
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
                    <div class="spinner-border text-primary loading" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="content" id="files">
                        <div class="row">
                            <?php
                            if(isset($_GET['url']) && isset($_GET['alt_url'])):
                                $folder->list($_GET['url'],$_GET['alt_url']);
                            else:
                                $folder->list();
                            endif;
                            ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="assets/js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>
<?php
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
            if($sonuc){
                $_SESSION['succes'] = "İsim değiştirme işlemi başarılı bir şekilde gerçekleşmiştir.";
                echo "<script type='text/javascript'>window.top.location='http://localhost/folderplugin';</script>"; exit;
            }else{
                header('Location : ../index.php');
                $_SESSION['error'] = "Dosya isim değiştirme işlemi sırasında hata meydana geldi";
            }
            break;
    endswitch;
endif;
?>
<?php session_destroy(); ?>
