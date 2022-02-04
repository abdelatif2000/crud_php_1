<?php
$id =$_POST['id'] ?? null;
if(empty($id)){
    header('loaction:index.php');  
    exit();
}
$pdo=new PDO('mysql:host=localhost;port=3306;dbname=product_','root',''); //connect to database 
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//if there any error
$getNameFolder=$pdo->prepare('SELECT   image FROM product  WHERE  id=:id ');
$getNameFolder->bindValue('id',$id);
$getNameFolder->execute();
 $folder=  $getNameFolder->fetchAll(PDO::FETCH_ASSOC);
 $strArray = explode('/', $folder[0]['image']);
 $myFolder = $strArray[1];
 echo $myFolder;
 function removeDirectory($path) {
    $files = glob($path . '/*');
    foreach ($files as $file) {
        is_dir($file) ? removeDirectory($file) : unlink($file);
    }
    rmdir($path);
}
removeDirectory('images/'.$myFolder);
$statement=$pdo->prepare('DELETE FROM PRODUCT WHERE  id=:id  ');
$statement->bindValue('id',$id);
$statement->execute();
header('location:index.php');


