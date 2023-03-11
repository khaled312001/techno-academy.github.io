<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>services</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products">

   <h1 class="heading">latest services</h1>

   <div class="box-container">

   <?php
     $select_services = $conn->prepare("SELECT * FROM `services`"); 
     $select_services->execute();
     if($select_services->rowCount() > 0){
      while($fetch_service = $select_services->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_service['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_service['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_service['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_service['image_01']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?pid=<?= $fetch_service['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_service['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_service['name']; ?></div>
      <div class="flex">
         <div class="price"><span>$</span><?= $fetch_service['price']; ?><span>/-</span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no services found!</p>';
   }
   ?>

   </div>

</section>













<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>