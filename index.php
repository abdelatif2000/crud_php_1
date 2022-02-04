<?php 
   $pdo=new PDO('mysql:host=localhost;port=3306;dbname=product_','root',''); //connect to database 
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//if there any error
   $statment=$pdo->prepare('select * from product   order by createAt desc ');
   $statment->execute();
    $products=    $statment->fetchAll(PDO::FETCH_ASSOC);//convert the date to table ;
    $search=$_GET['search']   ??  '';

    if($search){
      $command=$pdo->prepare('SELECT * from product  where title  like  :search ');
      $command->bindValue(':search',$search);
      $command->execute();
      $products= $command->fetchAll(PDO::FETCH_ASSOC);
    }

//     echo '<pre>';
//       var_dump($products);
//   echo  '</pre>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="app.css">
    <title>Document</title>
</head>
<body>
<form method="GET" class='mb-2' >
               <input type="text" name='search' class="form-control"  value="<?php echo $search ?>"  placeholder="Search By Title... " />
               <!-- <button type="submit" class="btn btn-sm btn-danger">search</button> -->
  </form>
<a  class="btn btn-sm btn-primary" href="createProduct.php">Create Product</a>
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">id</th>
      <th scope="col">title</th>
      <th scope="col">price</th>
      <th scope="col">action</th>
      <th scope="col">image</th>
    </tr>
  </thead>
  <tbody>
      <?php 
          foreach ($products as  $product) { ?>
          <tr>
             <td scope="row"><?php echo $product['id'] ?> </td>
            <td scope="row"><?php echo $product['title'] ?> </td>
            <td scope="row"><?php echo $product['price'] ?> </td>
            <td scope="row"><?php echo $product['createAt'] ?> </td>
            <td scope="row">
              <form method="POST" action="delete.php" style="display: inline-block;">
                <input type="hidden" name='id' value="<?php echo $product['id']?>" />
              <button type="submit" class="btn btn-sm btn-danger">delete</button>
              </form>
            <a   class="btn btn-sm btn-primary"  href="update.php?id=<?php echo $product['id'] ?>"   > Edit</a>
          </td>
          <td scope="row" class='image' ><img src="<?php echo $product['image'] ?>" /> </td>
          </tr>
        <?php 
                   }
        ?>
    <tr>
    </tr>
  </tbody>
</table>
</body>
</html>