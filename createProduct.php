<?php 
    include_once 'randomString.php';
   $pdo=new PDO('mysql:host=localhost;port=3306;dbname=product_','root',''); //connect to database 
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//if there any error
   $error=[];
   $title ='';
   $image='';
   $price ='';
   $description='';
  //  echo '<pre>';
  //  var_dump($_FILES['image']['name']);
  // echo '<pre/>';
   if($_SERVER['REQUEST_METHOD']==='POST'){
     $image=$_FILES['image'] ?? null  ;
     if( !is_dir('images')){
          mkdir('images');
     }
     $pathImage='';
     if(!empty( $image['name'])){
      $pathImage='images/'.  randomString(10)  .'/'.    $image['name'];
      mkdir(dirname($pathImage));
      move_uploaded_file($image['tmp_name'], $pathImage);
     }
    $title =$_POST['title'];
    $price =$_POST['price'];
    $date=date('Y-m-d  H:i:s');
    $description=$_POST['description'];
    $index=random_int(30,10000);
     if(empty($title)){
        $error[]='Title is required' ;
     }
    if(empty($price)){
       $error[]='price is required';
    }
    //  $pdo->exec("INSERT INTO product (id, discription, price, createAt, title) VALUES( $index  , '$description '  ,  $price  ,  '$date','$title' )" );
    if(empty($error)){
      //  move_uploaded_file()
      $statemen=$pdo->prepare("INSERT INTO product (id, discription, price, createAt, title, image) VALUES (:id , :description, :price, :date , :title ,:image) ");
      $statemen->bindValue(':id',$index);
      $statemen->bindValue(':description',$description);
      $statemen->bindValue(':price',$price);
      $statemen->bindValue(':date',$date);
      $statemen->bindValue(':title',$title);
      $statemen->bindValue(':image',$pathImage);
      $statemen->execute();
      $title ='';
      $image='';
      $price ='';
      $description='';
      header('location:index.php ');
    }
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="app.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>create new product </title>
</head>
<body>
<h1>Create New Product :</h1>
<?php if(!empty($error)) : ?> 
            <div  class='alert alert-danger'   >
                        <?php  foreach ($error as $error ) : ?>
                                <?php   echo $error.'<br/>' ?>
                         <?php endforeach?>
          </div>
  <?php endif ?>
<form action="" method="post"  enctype="multipart/form-data" >
  <div class="mb-3">
    <label  class="form-label" >Title Product</label>
    <input type="text" class="form-control" name='title' value="<?php echo $title ?>">
  </div>
  <div class="mb-3">
    <label  class="form-label">Get Image</label>
    <input type="file" class="form-control" name='image' >
  </div>
  <div class="mb-3">
    <label  class="form-label">Price</label>
    <input type="text" class="form-control" name='price' value="<?php echo $price ?>">
  </div>
  <div class="mb-3">
    <label  class="form-label">Description</label>
    <input type="text" class="form-control"  name='description' value="<?php echo $description ?>">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</body>
</html>
