# Welcome to Moon Folder Plugin!

Hello, I'm Oğuzhan Ayyıldız. I designed a file management system where you can manage your own files on your own website. Thanks to this system, it is now easier to process files and upload new files.

The contents of our expectations are as follows.

 - List folder and file
 - Access information of files and folders
 - Add New Files and Folders
 - Delete Files and Folders
 - Optimize Image Files
 - Resize Image Files
 - Crop Image Files
 - Search Files and Folders
 - Move to File from Folders

## Install

First, after downloading the files, put them in the directory of your website. Then open the **"Folder"** file inside the **library** folder.

>   private $folder_path ='uploaded'; 

Instead of the "uploaded" section, write the main folder you want to view.
Then, on which page you want to run the application, open that page and write the following codes.

> include ('path/to/library/Folder.php');  
> $folder = new Folder();

> $folder->loader();

Finally, write this style code.

> **.iframe {**  
	  width: 100%;  
	  height: 100vh;  
	  overflow: hidden !important;  
	  background: transparent;  
	  border:none;  
	  margin:0px !important;  
**}**

You can use the application so easily now.

[My Web Page](http://oguzhanayyildiz.com/)
