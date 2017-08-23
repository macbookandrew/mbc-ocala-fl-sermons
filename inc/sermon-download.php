<?php

//   get parameters from $_GET
$set = 'sermons';
$file = $_GET['file'];

//   set variables from inputs
$file_path = filter_input(INPUT_GET, 'file', FILTER_SANITIZE_STRING);
$uploads_dir = '/home4/memorial/public_html/wp-content/uploads/';
$full_file_path = $uploads_dir.$set.'/'.str_replace('&#39;','\'',$file_path);
$file_extension = substr(strrchr($file_path, "."), 1);
if (!isset($file_name_to_use)) {$file_name_to_use = basename($full_file_path);}

//   filter out dangerous requests
if (strpos($file_path, '/') !== false) {die('<p>Hacking attempt&hellip;thwarted.</p>');}

//   fix spaces in filename for IE
if (strpos($_SERVER['HTTP_USER_AGENT'],'MSIE') !== false) {$file_name_to_use = str_replace(' ','%20',$file_name_to_use);}

//   diagnostic
$diagnostic = false;

if ($diagnostic == true) {
    echo "set: $set<br/>file_path: $file_path<br/>";
    echo "filext: $file_extension<br/>full_file_path: $full_file_path<br/>";
    echo "file_name_to_use: $file_name_to_use<br/>";
    echo "filesize: ".filesize($full_file_path).'<br/>';
    if (strpos($_SERVER['HTTP_USER_AGENT'],'MSIE') !== false) {echo 'IE';}
}
else {
    //   output headers and set name
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    header("Content-Description: File Transfer");
    header("Content-Type: $file_extension");
    header("Content-Disposition: attachment; filename=\"$file_name_to_use\"");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".filesize($full_file_path));

    //   read out file
    @readfile($full_file_path);
}
exit;
?>
