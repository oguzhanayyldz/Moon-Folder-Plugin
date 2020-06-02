<?php


class Folder
{

    private $folder_path = 'uploaded';

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
                    echo $this->rarView($link,$file);
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
        $stat = @stat($file);
        date_default_timezone_set('Europe/Istanbul');
        $date = date('d.m.Y H:i:s', $stat['mtime']);
        echo '<div class="status_row"><b> Dosya Konumu : </b></br>'. $file .'</div>';
        echo '<div class="status_row"><b>Boyut : </b></br>'. $stat['size'] .'KB </div>';
        echo '<div class="status_row"><b>Son değişiklik zamanı : </b></br>'. $date .'</div>';
        if($type == 'image'):
            $image_info = getimagesize($file);
            echo '<div class="status_row"><b>Resmin Genişliği : </b></br>'. $image_info[0] .' px</div>';
            echo '<div class="status_row"><b>Resmin Yüksekliği : </b></br>'. $image_info[1] .' px</div>';
            echo '<div class="status_row"><b>Ön İzlenimi : </b></br><a href="'.$file.'" target="_blank"><img src="'.$file.'" class="status-image"></a> </div>';
            echo '<div class="status_row">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#rename" data-whatever="'.$file.'">Yeniden Adlandır</button>
                    <a href="#" class="btn btn-info btn-sm" role="button">Resmi Optimize Et</a> 
                    <a href="#" class="btn btn-primary btn-sm" role="button">Resmi Boyutlandır</a></div>';
            echo '<div class="status_row"><a href="#" class="btn btn-danger btn-sm" role="button">Resmi Sil</a></div>';
        endif;
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
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Değiştir</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>';
    }

    private function folderView($file_url,$file,$url){
        return ' <div class="col-md-3 col-xs-6">
                <div class="file">
                    <div class="image">
                        <a href="?url='.$file_url.'&alt_url='.$url.'" class="file-link"><img src="assets/icons/folder.png" class="file-icon"/></a>
                    </div>
                    <div class="file-name"><a href="#" class="file-link">'.$file.'</a></div>
                </div>
               </div>';
    }

    private function imageView($url,$name){
        if(isset($_GET['url'])): $folder_url = $_GET['url']; else: $folder_url = null; endif;
        if(isset($_GET['alt_url'])): $sub_folder_url = $_GET['alt_url']; else: $sub_folder_url = null; endif;
        return ' <div class="col-md-3 col-xs-6">
                <div class="file status" dataID="'.$url.'">
                    <div class="image">
                        <a href="?file='.$url.'&type=image&url='.$folder_url.'&alt_url='.$sub_folder_url.'" class="file-link"><img src="'.$url.'" class="file-icon"/></a>
                    </div>
                    <div class="file-name"><a href="#" class="file-link">'.$name.'</a></div>
                </div>
               </div>';
    }

    private function docxView($url,$name){
        if(isset($_GET['url'])): $folder_url = $_GET['url']; else: $folder_url = null; endif;
        if(isset($_GET['alt_url'])): $sub_folder_url = $_GET['alt_url']; else: $sub_folder_url = null; endif;
        return ' <div class="col-md-3 col-xs-6">
                <div class="file status">
                    <div class="image">
                        <a href="?file='.$url.'&url='.$folder_url.'&alt_url='.$sub_folder_url.'" class="file-link"><img src="assets/icons/docx.png" class="file-icon"/></a>
                    </div>
                    <div class="file-name"><a href="#" class="file-link">'.$name.'</a></div>
                </div>
               </div>';
    }

    private function docView($url,$name){
        if(isset($_GET['url'])): $folder_url = $_GET['url']; else: $folder_url = null; endif;
        if(isset($_GET['alt_url'])): $sub_folder_url = $_GET['alt_url']; else: $sub_folder_url = null; endif;
        return ' <div class="col-md-3 col-xs-6">
                <div class="file status">
                    <div class="image">
                        <a href="?file='.$url.'&url='.$folder_url.'&alt_url='.$sub_folder_url.'" class="file-link"><img src="assets/icons/doc.png" class="file-icon"/></a>
                    </div>
                    <div class="file-name"><a href="#" class="file-link">'.$name.'</a></div>
                </div>
               </div>';
    }

    private function pdfView($url,$name){
        if(isset($_GET['url'])): $folder_url = $_GET['url']; else: $folder_url = null; endif;
        if(isset($_GET['alt_url'])): $sub_folder_url = $_GET['alt_url']; else: $sub_folder_url = null; endif;
        return ' <div class="col-md-3 col-xs-6">
                <div class="file status">
                    <div class="image">
                        <a href="?file='.$url.'&url='.$folder_url.'&alt_url='.$sub_folder_url.'" class="file-link"><img src="assets/icons/pdf.png" class="file-icon"/></a>
                    </div>
                    <div class="file-name"><a href="#" class="file-link">'.$name.'</a></div>
                </div>
               </div>';
    }

    private function zipView($url,$name){
        if(isset($_GET['url'])): $folder_url = $_GET['url']; else: $folder_url = null; endif;
        if(isset($_GET['alt_url'])): $sub_folder_url = $_GET['alt_url']; else: $sub_folder_url = null; endif;
        return ' <div class="col-md-3 col-xs-6">
                <div class="file status">
                    <div class="image">
                        <a href="?file='.$url.'&url='.$folder_url.'&alt_url='.$sub_folder_url.'" class="file-link"><img src="assets/icons/zip.png" class="file-icon"/></a>
                    </div>
                    <div class="file-name"><a href="#" class="file-link">'.$name.'</a></div>
                </div>
               </div>';
    }

    private function rarView($url,$name){
        if(isset($_GET['url'])): $folder_url = $_GET['url']; else: $folder_url = null; endif;
        if(isset($_GET['alt_url'])): $sub_folder_url = $_GET['alt_url']; else: $sub_folder_url = null; endif;
        return ' <div class="col-md-3 col-xs-6">
                <div class="file status">
                    <div class="image">
                        <a href="?file='.$url.'&url='.$folder_url.'&alt_url='.$sub_folder_url.'" class="file-link"><img src="assets/icons/rar.png" class="file-icon"/></a>
                    </div>
                    <div class="file-name"><a href="#" class="file-link">'.$name.'</a></div>
                </div>
               </div>';
    }

    private function txtView($url,$name){
        if(isset($_GET['url'])): $folder_url = $_GET['url']; else: $folder_url = null; endif;
        if(isset($_GET['alt_url'])): $sub_folder_url = $_GET['alt_url']; else: $sub_folder_url = null; endif;
        return ' <div class="col-md-3 col-xs-6">
                <div class="file">
                    <div class="image status">
                        <a href="?file='.$url.'&url='.$folder_url.'&alt_url='.$sub_folder_url.'" class="file-link"><img src="assets/icons/txt.png" class="file-icon"/></a>
                    </div>
                    <div class="file-name"><a href="#" class="file-link">'.$name.'</a></div>
                </div>
               </div>';
    }



}