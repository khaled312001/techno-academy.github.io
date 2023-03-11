<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_course'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_img/'.$image_01;

  

   $select_courses = $conn->prepare("SELECT * FROM `courses` WHERE name = ?");
   $select_courses->execute([$name]);

   if($select_courses->rowCount() > 0){
      $message[] = 'course name already exist!';
   }else{

      $insert_courses = $conn->prepare("INSERT INTO `courses`(name, details, price, image_01, image_02, image_03) VALUES(?,?,?,?,?,?)");
      $insert_courses->execute([$name, $details, $price, $image_01, $image_02, $image_03]);

      if($insert_courses){
         if($image_size_01 > 2000000 OR $image_size_02 > 2000000 OR $image_size_03 > 2000000){
            $message[] = 'image size is too large!';
         }else{
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            move_uploaded_file($image_tmp_name_02, $image_folder_02);
            move_uploaded_file($image_tmp_name_03, $image_folder_03);
            $message[] = 'new course added!';
         }

      }

   }  

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_course_image = $conn->prepare("SELECT * FROM `courses` WHERE id = ?");
   $delete_course_image->execute([$delete_id]);
   $fetch_delete_image = $delete_course_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image_01']);
   unlink('../uploaded_img/'.$fetch_delete_image['image_02']);
   unlink('../uploaded_img/'.$fetch_delete_image['image_03']);
   $delete_course = $conn->prepare("DELETE FROM `courses` WHERE id = ?");
   $delete_course->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:courses.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>courses</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="add-courses">

   <h1 class="heading">add course</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>course name (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="enter course name" name="name">
         </div>
         <div class="inputBox">
            <span>course price (required)</span>
            <input type="number" min="0" class="box" required max="9999999999" placeholder="enter course price" onkeypress="if(this.value.length == 10) return false;" name="price">
         </div>
        <div class="inputBox">
            <span>image 01 (required)</span>
            <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
       
         <div class="inputBox">
            <span>course details (required)</span>
            <textarea name="details" placeholder="enter course details" class="box" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
      </div>
      
      <input type="submit" value="add course" class="btn" name="add_course">
   </form>

</section>

<section class="show-courses">

   <h1 class="heading">courses added</h1>

   <div class="box-container">

   <?php
      $select_courses = $conn->prepare("SELECT * FROM `courses`");
      $select_courses->execute();
      if($select_courses->rowCount() > 0){
         while($fetch_courses = $select_courses->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <img src="../uploaded_img/<?= $fetch_courses['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_courses['name']; ?></div>
      <div class="price">$<span><?= $fetch_courses['price']; ?></span>/-</div>
      <div class="details"><span><?= $fetch_courses['details']; ?></span></div>
      <div class="flex-btn">
         <a href="update_course.php?update=<?= $fetch_courses['id']; ?>" class="option-btn">update</a>
         <a href="courses.php?delete=<?= $fetch_courses['id']; ?>" class="delete-btn" onclick="return confirm('delete this course?');">delete</a>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no courses added yet!</p>';
      }
   ?>
   
   </div>

</section>








<script src="../js/admin_script.js"></script>
   
</body>
</html>