<?php


class Folder
{

    private $folder_path ='uploaded'; // Config this line by you can use how folder show

    public function  __construct()
    {

    }

    public function loader(){
        $loc = explode('/',$_SERVER['PHP_SELF'],-1);
        $location = '';
        foreach ($loc as $row){
            $location .= $row;
        }
        $server= 'http://'.$_SERVER['SERVER_NAME'].'/'.$location.'/';
        echo '<iframe src="'.$server.'index.php" class="iframe"></iframe>';
    }

    public function get_Path(){
        return $this->folder_path;
    }

    public function list($url = NULL , $alt_url = NULL){
        if($url == NULL): $url = $this->folder_path; endif;
        $files = scandir($url);
        sort($files);
        foreach ($files as $file){
            $fileORfolder = filetype($url.'/'.$file);
            $filetype = mime_content_type($url.'/'.$file);
            if($fileORfolder == 'file') switch ($filetype){
                case 'image/png':
                    $link = $url.'/'.$file;
                    echo $this->imageView($link,$file);
                    break;
                case 'image/jpeg' :
                    $link = $url.'/'.$file;
                    echo $this->imageView($link,$file);
                    break;
                case  'image/jpg':
                    $link = $url.'/'.$file;
                    echo $this->imageView($link,$file);
                    break;
                case   'image/svg':
                    $link = $url.'/'.$file;
                    echo $this->imageView($link,$file);
                    break;
                case   'image/gif':
                    $link = $url.'/'.$file;
                    echo $this->imageView($link,$file);
                    break;
                case 'application/pdf':
                    $link = $url.'/'.$file;
                    echo $this->pdfView($link,$file);
                    break;
                case 'application/docx':
                    $link = $url.'/'.$file;
                    echo $this->docxView($link,$file);
                    break;
                case 'application/doc':
                    $link = $url.'/'.$file;
                    echo $this->docView($link,$file);
                    break;
                case 'application/zip':
                    $link = $url.'/'.$file;
                    echo $this->zipView($link,$file);
                    break;
                case 'application/x-rar':
                    $link = $url.'/'.$file;
                    echo $this->rarView($link,$file);
                    break;
                case 'text/plain':
                    $link = $url.'/'.$file;
                    echo $this->rarView($link,$file);
                    break;
                case 'application/x-empty':
                    $link = $url.'/'.$file;
                    echo $this->txtView($link,$file);
                    break;
                default:
                    break;
            } elseif($fileORfolder == 'dir' and $file == '.'){
                if($url != $this->folder_path) {
                    $explode = explode('/',$alt_url);
                    $count = count($explode);
                    $sub_sub = '';
                    for($i = 0 ; $i <  $count-1 ; $i++){
                        if($i == ($count-2)){
                            $sub_sub .= $explode[$i];
                        }else{
                            $sub_sub .=  $explode[$i]. '/';
                        }
                    }
                    echo "<div class='back-button'><a href='?url=" . $alt_url . "&alt_url=".$sub_sub."'><i class=\"fas fa-arrow-circle-left\"></i></a></div>";
                    echo ' <div class="back-folder droppable" data-url="'.$alt_url.'"><i class="fas fa-arrow-circle-left"></i></div>';
                }
            }elseif($fileORfolder == 'dir' and $file != '..'){
                $file_url = $url . '/' . $file;
                echo $this->folderView($file_url,$file,$url);

            }
            //echo $fileORfolder .' - '.$filetype .' - '.$file .'</br>';
        }
        //print_r($files);

    }

    public function getStatus($file,$type = NULL){
        if($type != 'folder') {
            $stat = @stat($file);
            date_default_timezone_set('Europe/Istanbul');
            $date = date('d.m.Y H:i:s', filectime($file));
            echo '<div class="status_row"><b> Dosya Konumu : </b></br>' . $file . '</div>';
            echo '<div class="status_row"><b>Boyut : </b></br>' . round($stat['size']/1024,3) . ' KB </div>';
            echo '<div class="status_row"><b>Son değişiklik zamanı : </b></br>' . $date . '</div>';
            if ($type == 'image'):
                $type_ = mime_content_type($file);
                $image_info = getimagesize($file);
                echo '<div class="status_row"><b>Resmin Genişliği : </b></br>' . $image_info[0] . ' px</div>';
                echo '<div class="status_row"><b>Resmin Yüksekliği : </b></br>' . $image_info[1] . ' px</div>';
                echo '<div class="status_row"><b>Ön İzlenimi : </b></br><a href="' . $file . '" target="_blank"><img src="' . $file . '" class="status-image"></a> </div>';
                echo '<div class="status_row">
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#rename" data-whatever="' . $file . '">Yeniden Adlandır</button>';
                if ($type_ == 'image/png' || $type_ == 'image/jpg' || $type_ == 'image/jpeg') {
                    echo '<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#optimize" data-whatever="' . $file . '">Resmi Optimize Etme</button>';
                }
                echo '<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#resize" data-whatever="' . $file . '">Resmi Boyutlandır</button>';
                echo '<div class="status_row"><a href="crop.php?url=' . $file . '" class="btn btn-info btn-sm">Resmi Kırp</a>
                        <a href="javascript();" class="btn btn-danger btn-sm" role="button" data-href="?delete=' . $file . '" data-toggle="modal" data-target="#confirm-delete">Dosyayı sil</a></div>';
            else:
                echo '<div class="status_row"><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#rename" data-whatever="' . $file . '">Yeniden Adlandır</button></div>
              <div class="status_row"><a href="javascript();" class="btn btn-danger btn-sm" role="button" data-href="?delete=' . $file . '" data-toggle="modal" data-target="#confirm-delete">Dosyayı sil</a></div>';
            endif;
        }else{
            echo '<div class="status_row"><b> Dosya Konumu : </b></br>' . $file . '</div>';
            //$this->getFolderSize($file,0,0);
            $size = round(($this->folderSize($file)/1024),3);
            echo '<div class="status_row"><b>Boyut : </b></br>' .$size. ' KB </div>';
            echo '<div class="status_row"><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#file_rename" data-whatever="' . $file . '">Yeniden Adlandır</button></div>
              <div class="status_row"><a href="javascript();" class="btn btn-danger btn-sm" role="button" data-href="?delete_folder=' . $file . '" data-toggle="modal" data-target="#confirm-delete">Klasörü sil</a></div>';
        }
    }

    public function folderSize ($dir)
    {
        $size = 0;

        foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
            $size += is_file($each) ? filesize($each) : $this->folderSize($each);
        }

        return $size;
    }

    public function getRenameModal(){
        echo '<div class="modal fade" id="rename" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Dosya İsmi Değiştirme</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="#" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                                <input type="hidden" name="url" class="url">
                                <input type="hidden" name="type" value="rename">
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Yeni Dosya İsmi:</label>
                                    <input type="text" name="name" class="form-control" id="name">
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                            <button type="submit" class="btn btn-primary">Değiştir</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>';
    }

    public function getFileRenameModal(){
        echo '<div class="modal fade" id="file_rename" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Dosya İsmi Değiştirme</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="#" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                                <input type="hidden" name="url" class="url">
                                <input type="hidden" name="type" value="file_rename">
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Yeni Dosya İsmi:</label>
                                    <input type="text" name="name" class="form-control" id="file-name">
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                            <button type="submit" class="btn btn-primary">Değiştir</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>';
    }

    public function getAddFileModal(){
        echo '<div class="modal fade" id="add_file" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Yeni Dosya Ekleme</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="#" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                                <input type="hidden" name="url" class="url">
                                <input type="hidden" name="type" value="add_file">
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Dosya:</label>
                                    <input type="file" name="file" class="form-control" id="file" required>
                                    <div class = "size">
                                        <label for="recipient-name" class="col-form-label">Boyutlandırma :</label>
                                        <select class="form-control size-select" name="size">
                                          <option value="orjinal" selected>Orjinal Boyut</option>
                                          <option value="1920x1080" >HD Boyut (1920 x 1080)</option>
                                          <option value="730x400">Büyük Boyut  (730 x 400)</option>
                                          <option value="640x350">Orta Boyut  (640 x 350)</option>
                                          <option value="350x230">Küçük Boyut  (350 x 230)</option>
                                          <option value="different">Farklı Bir Boyut Gir</option>
                                        </select>
                                        <div class="different">
                                            <label for="recipient-name" class="col-form-label">Genişlik:</label>
                                            <input type="number" name="width" class="form-control">
                                            <label for="recipient-name" class="col-form-label">Yükseklik:</label>
                                            <input type="number" name="height" class="form-control">
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                            <button type="submit" class="btn btn-primary">Ekle</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>';
    }

    public function getAddFolderModal(){
        echo '<div class="modal fade" id="add_folder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Yeni Klasör Ekleme</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="#" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                                <input type="hidden" name="url" class="url">
                                <input type="hidden" name="type" value="add_folder">
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Klasör Name:</label>
                                    <input type="text" name="name" class="form-control" id="folder" required>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                            <button type="submit" class="btn btn-primary">Ekle</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>';
    }

    public function getOptimizeModel(){
        echo '<div class="modal fade" id="optimize" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Optimize Et</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="#" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                                <input type="hidden" name="url" class="url">
                                <input type="hidden" name="type" value="optimize">
                                <input type="hidden" name="file_type" class="file_type">
                                <div class="form-group">
                                    <label for="formControlRange">Optimizasyon Oranını Belirleyiniz</label>
                                    <input type="range" name="range" value="85" class="form-control-range" id="range" step="5" min="60" max="100">
                                    <span id="textInput">85</span>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                            <button type="submit" class="btn btn-primary">Optimize Et</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>';
    }

    public function getResizeModal(){
        echo '<div class="modal fade" id="resize" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Boyutlandırma</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="#" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                                <input type="hidden" name="url" class="url">
                                <input type="hidden" name="type" value="resize">
                                <div class="form-group">
                                    <div class = "image-size">
                                        <label for="recipient-name" class="col-form-label">Boyutlandırma :</label>
                                        <select class="form-control size-select" name="size" style="display: block;">
                                          <option value="1920x1080" selected>HD Boyut (1920 x 1080)</option>
                                          <option value="730x400">Büyük Boyut  (730 x 400)</option>
                                          <option value="640x350">Orta Boyut  (640 x 350)</option>
                                          <option value="350x230">Küçük Boyut  (350 x 230)</option>
                                          <option value="different">Farklı Bir Boyut Gir</option>
                                        </select>
                                        <div class="different">
                                            <label for="recipient-name" class="col-form-label">Genişlik:</label>
                                            <input type="number" name="width" class="form-control">
                                            <label for="recipient-name" class="col-form-label">Yükseklik:</label>
                                            <input type="number" name="height" class="form-control">
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                            <button type="submit" class="btn btn-primary">Ekle</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>';
    }

    public function getDeleteModel(){
        echo '<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Kontrol</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Dosyayı silmek istediğinme emin misin ?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">İptal Et</button>
                                <a class="btn btn-danger btn-ok">Sil</a>
                            </div>
                        </div>
                    </div>
                </div>';
    }


    private function folderView($file_url,$file,$url){
        $alt = explode('/',$url,-1);
        $alt_url = '';
        foreach ($alt as $row){
            $alt_url .= $row . '/';
        }
        $alt_url =  trim($alt_url,'/');
        return ' <div class="col-md-3 col-xs-6 droppable ss" data-url="'.$file_url.'" data-name="'.$file.'">
                <div class="file">
                    <div class="image">
                        <a href="?url='.$file_url.'&alt_url='.$url.'" data-href="?file='.$file_url.'&url='.$url.'&alt_url='.$alt_url.'&type=folder" class="file-link folder-link"><img src="assets/icons/folder.png" class="file-icon"/></a>
                    </div>
                    <div class="file-name"><a href="#" class="file-link folder-link">'.$file.'</a></div>
                    <div class="file-select"><input type="checkbox" name="url[]" value="'.$file_url.'" class="checkbox"/></div>
                </div>
               </div>';
    }

    private function imageView($url,$name){
        if(isset($_GET['url'])): $folder_url = $_GET['url']; else: $folder_url = null; endif;
        if(isset($_GET['alt_url'])): $sub_folder_url = $_GET['alt_url']; else: $sub_folder_url = null; endif;
        return ' <div class="col-md-3 col-xs-6 draggable ss" data-url="'.$url.'" data-name="'.$name.'">
                <div class="file status" dataID="'.$url.'">
                    <div class="image">
                        <a href="?file='.$url.'&type=image&url='.$folder_url.'&alt_url='.$sub_folder_url.'" class="file-link"><img src="'.$url.'" class="file-icon"/></a>
                    </div>
                    <div class="file-name"><a href="?file='.$url.'&type=image&url='.$folder_url.'&alt_url='.$sub_folder_url.'" class="file-link">'.$name.'</a></div>
                    <div class="file-select"><input type="checkbox" name="url[]" value="'.$url.'" class="checkbox"/></div>
                </div>
               </div>';
    }

    private function docxView($url,$name){
        if(isset($_GET['url'])): $folder_url = $_GET['url']; else: $folder_url = null; endif;
        if(isset($_GET['alt_url'])): $sub_folder_url = $_GET['alt_url']; else: $sub_folder_url = null; endif;
        return ' <div class="col-md-3 col-xs-6 draggable ss" data-name="'.$name.'">
                <div class="file status">
                    <div class="image">
                        <a href="?file='.$url.'&url='.$folder_url.'&alt_url='.$sub_folder_url.'" class="file-link"><img src="assets/icons/docx.png" class="file-icon"/></a>
                    </div>
                    <div class="file-name"><a href="?file='.$url.'&type=image&url='.$folder_url.'&alt_url='.$sub_folder_url.'" class="file-link">'.$name.'</a></div>
                    <div class="file-select"><input type="checkbox" name="url[]" value="'.$url.'" class="checkbox"/></div>
                </div>
               </div>';
    }

    private function docView($url,$name){
        if(isset($_GET['url'])): $folder_url = $_GET['url']; else: $folder_url = null; endif;
        if(isset($_GET['alt_url'])): $sub_folder_url = $_GET['alt_url']; else: $sub_folder_url = null; endif;
        return ' <div class="col-md-3 col-xs-6 draggable ss" data-name="'.$name.'">
                <div class="file status">
                    <div class="image">
                        <a href="?file='.$url.'&url='.$folder_url.'&alt_url='.$sub_folder_url.'" class="file-link"><img src="assets/icons/doc.png" class="file-icon"/></a>
                    </div>
                    <div class="file-name"><a href="?file='.$url.'&type=image&url='.$folder_url.'&alt_url='.$sub_folder_url.'" class="file-link">'.$name.'</a></div>
                    <div class="file-select"><input type="checkbox" name="url[]" value="'.$url.'" class="checkbox"/></div>
                </div>
               </div>';
    }

    private function pdfView($url,$name){
        if(isset($_GET['url'])): $folder_url = $_GET['url']; else: $folder_url = null; endif;
        if(isset($_GET['alt_url'])): $sub_folder_url = $_GET['alt_url']; else: $sub_folder_url = null; endif;
        return ' <div class="col-md-3 col-xs-6 draggable ss" data-name="'.$name.'">
                <div class="file status">
                    <div class="image">
                        <a href="?file='.$url.'&url='.$folder_url.'&alt_url='.$sub_folder_url.'" class="file-link"><img src="assets/icons/pdf.png" class="file-icon"/></a>
                    </div>
                    <div class="file-name"><a href="?file='.$url.'&type=image&url='.$folder_url.'&alt_url='.$sub_folder_url.'" class="file-link">'.$name.'</a></div>
                    <div class="file-select"><input type="checkbox" name="url[]" value="'.$url.'" class="checkbox"/></div>
                </div>
               </div>';
    }

    private function zipView($url,$name){
        if(isset($_GET['url'])): $folder_url = $_GET['url']; else: $folder_url = null; endif;
        if(isset($_GET['alt_url'])): $sub_folder_url = $_GET['alt_url']; else: $sub_folder_url = null; endif;
        return ' <div class="col-md-3 col-xs-6 draggable ss" data-name="'.$name.'">
                <div class="file status">
                    <div class="image">
                        <a href="?file='.$url.'&url='.$folder_url.'&alt_url='.$sub_folder_url.'" class="file-link"><img src="assets/icons/zip.png" class="file-icon"/></a>
                    </div>
                    <div class="file-name"><a href="?file='.$url.'&type=image&url='.$folder_url.'&alt_url='.$sub_folder_url.'" class="file-link">'.$name.'</a></div>
                    <div class="file-select"><input type="checkbox" name="url[]" value="'.$url.'" class="checkbox"/></div>
                </div>
               </div>';
    }

    private function rarView($url,$name){
        if(isset($_GET['url'])): $folder_url = $_GET['url']; else: $folder_url = null; endif;
        if(isset($_GET['alt_url'])): $sub_folder_url = $_GET['alt_url']; else: $sub_folder_url = null; endif;
        return ' <div class="col-md-3 col-xs-6 draggable ss" data-name="'.$name.'">
                <div class="file status">
                    <div class="image">
                        <a href="?file='.$url.'&url='.$folder_url.'&alt_url='.$sub_folder_url.'" class="file-link"><img src="assets/icons/rar.png" class="file-icon"/></a>
                    </div>
                    <div class="file-name"><a href="?file='.$url.'&type=image&url='.$folder_url.'&alt_url='.$sub_folder_url.'" class="file-link">'.$name.'</a></div>
                    <div class="file-select"><input type="checkbox" name="url[]" value="'.$url.'" class="checkbox"/></div>
                </div>
               </div>';
    }

    private function txtView($url,$name){
        if(isset($_GET['url'])): $folder_url = $_GET['url']; else: $folder_url = null; endif;
        if(isset($_GET['alt_url'])): $sub_folder_url = $_GET['alt_url']; else: $sub_folder_url = null; endif;
        return ' <div class="col-md-3 col-xs-6 draggable ss" data-name="'.$name.'">
                <div class="file">
                    <div class="image status">
                        <a href="?file='.$url.'&url='.$folder_url.'&alt_url='.$sub_folder_url.'" class="file-link"><img src="assets/icons/txt.png" class="file-icon"/></a>
                    </div>
                    <div class="file-name"><a href="?file='.$url.'&type=image&url='.$folder_url.'&alt_url='.$sub_folder_url.'" class="file-link">'.$name.'</a></div>
                    <div class="file-select"><input type="checkbox" name="url[]" value="'.$url.'" class="checkbox"/></div>
                </div>
               </div>';
    }



}