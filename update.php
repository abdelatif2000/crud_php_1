<?php
include_once 'randomString.php';
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=product_', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//  echo 'test'.$id;
$id = $_GET['id'] ?? null;
// echo '<pre>';
//     var_dump($id);
// echo '<pre/>';
// exit();
if (empty($id)) {
  header('location:index.php');
  exit();
}
$error = [];
$statemant = $pdo->prepare('SELECT * FROM product  WHERE id=:id ');
$statemant->bindValue('id', $id);
$statemant->execute();
$product = $statemant->fetchAll(PDO::FETCH_ASSOC);
//   echo '<pre>';
//     var_dump($product);
// echo  '</pre>';
$product = $product[0] ?? null;
$title = $product['title'] ?? null;
$description = $product['discription']  ?? null;
$price = $product['price'] ?? null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $image = $_FILES['image'];
  $pathImage = $product['image'];
  if (!empty($image['name'])) {
    $image = $_FILES['image'] ?? null;
    if (!is_dir('images')) {
      mkdir('images');
    }
    if (!empty($image['name'])) {
      $strArray = explode('/', $product['image']);
      $myFolder = $strArray[1];
      unlink($product['image']);
      $pathImage = 'images/' . $myFolder . '/' . $image['name'];
      move_uploaded_file($image['tmp_name'], $pathImage);
    }
  }
  $title = $_POST['title'] ?? null;
  $description = $_POST['description'] ?? null;
  $price = $_POST['price'] ?? null;

  if (empty($title)) {
    $error[] = 'Title is required';
  }
  if (empty($price)) {
    $error[] = 'price is required';
  }
  if (empty($error)) {
    $statemant = $pdo->prepare('UPDATE product  SET discription=:discription ,title=:title ,price=:price,image=:image   WHERE id=:id ');
    $statemant->bindValue('discription', $description);
    $statemant->bindValue('title', $title);
    $statemant->bindValue('price', $price);
    $statemant->bindValue('id', $id);
    $statemant->bindValue('id', $id);
    $statemant->bindValue('image', $pathImage);
    $statemant->execute();
    header('location:index.php');
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
  <title>update product </title>
</head>

<body>
  <h1>update Product <?php echo $product['title'] ?></h1>
  <?php if (!empty($error)) : ?>
    <div class='alert alert-danger'>
      <?php foreach ($error as $error) : ?>
        <?php echo $error . '<br/>' ?>
      <?php endforeach ?>
    </div>
  <?php endif ?>
  <img src="<?php echo $product['image'] ?>" />
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Title Product</label>
      <input type="text" class="form-control" name='title' value="<?php echo $title ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Get Image</label>
      <input type="file" class="form-control" name='image'>
    </div>
    <div class="mb-3">
      <label class="form-label">Price</label>
      <input type="text" class="form-control" name='price' value="<?php echo $price ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <input type="text" class="form-control" name='description' value="<?php echo $description ?>">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</body>
</html>