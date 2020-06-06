<?php
include ('library/Folder.php');
$folder = new Folder();
?>
<!doctype html>
<html lang="tr">
<head>
    <link rel="stylesheet" href="assets/css/main.css"/>
</head>
<body style="margin: 0px; overflow: hidden">
    <?php
        $folder->loader();
    ?>
</body>
</html>