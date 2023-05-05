<?php -
   require 'include/function.php';  
     if (!isLoggedIn()) {
     $_SESSION['msg'] = "You must log in first";
     header('location:  login.php');
     exit;
     }
?>
<?php
    require 'cart/check_if_added.php';
    require 'include/header.php';
    
      $user_id = mysqli_real_escape_string($con, $_SESSION['user']['Account_ID']);
      $userdetail = mysqli_query($con, "SELECT * FROM account WHERE Account_ID = {$user_id}");
      $urow = mysqli_fetch_assoc($userdetail);


    $Account_ID=$_SESSION['id'];
    $user_products_query="SELECT products.id,products.name,products.photo,products.price,cart.Qty,inventory.qty,inventory.supply_id FROM cart INNER JOIN products ON cart.Item_ID = products.id INNER JOIN inventory ON cart.Item_ID = inventory.supply_id where cart.Account_ID='$Account_ID'  Group By inventory.supply_id";
    $user_products_result=mysqli_query($con,$user_products_query) or die(mysqli_error($con));
    $no_of_user_products= mysqli_num_rows($user_products_result);

    $sum=0;
    $total=0;
    $vat=0;
    if($no_of_user_products == 0){ 
    
    }else{
    while($row=mysqli_fetch_array($user_products_result) ){
            $quantity=$row['Qty'];
            $sum= $quantity*$row['price'];
            $total += $sum;   
            $vat =   ($total/ 100) * 12;
    }
    }
    $user_products_result=mysqli_query($con,$user_products_query) or die(mysqli_error($con));
    $no_of_user_products= mysqli_num_rows($user_products_result);
    $counter=0;
    while($row=mysqli_fetch_array($user_products_result))                   
    $counter = ltrim($counter, "0");

    
    $userquery="select Street,Barangay,City,Zip_Code,Contact_Num,Email_Add  from account where Account_ID='$Account_ID'";
    $userresult=mysqli_query($con,$userquery) or die(mysqli_error($con));
    $noproducts= mysqli_num_rows($userresult);


     $f = "needtocomplete";

    if(isset($_POST['save'])){
        
    $checkbox = $_POST['check'];
    for($i=0;$i<count($checkbox);$i++){
    $del_id = $checkbox[$i]; 
    mysqli_query($con ,"DELETE FROM cart WHERE Account_ID='$Account_ID' and Item_ID='".$del_id."'");
    $message = "Data deleted successfully !";
    }
    }
    $result = mysqli_query($con ,"SELECT * FROM cart");


  
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cart</title>
    <link rel="stylesheet" href=" css/cart.css">
</head>
<body>

<section class="cart" id="cart">
       <form method="post" action= cart/cart_update.php>
  <div class="wrapper">
        <h2></h2>
        <?php 
        $user_products_result=mysqli_query($con,$user_products_query) or die(mysqli_error($con));
        $no_of_user_products= mysqli_num_rows($user_products_result);
        $counter=0;
        $i=0;

        while($row = mysqli_fetch_array($result)) {
        while($row=mysqli_fetch_array($user_products_result)){           
        $number = number_format($row['price'],2);

         if($row['qty'] == 0){ 
    
         }else{
        ?>
   
        <div class="project">
            <div class="shop">
                <div class="box">  
                        <img src=" Picture/product/<?php echo $row["photo"]; ?>" alt="Images" >
                        <div class="content">
                        <input type="hidden"  name="Product_ID" id="Product_ID" value="<?php echo $row["supply_id"]; ?>">
                        <input type="checkbox" id="checkItem" name="check[]" value="<?php echo $row["supply_id"]; ?>" style="float:right;">
                        <h3><?php echo $row['name']?></h3>
                        <h4>Price: <?php echo $number?></h4>
                        <p class="unit">Quantity: <input type="number" name="qty" id="qty" placeholder="<?php echo $row['Qty']?>" min="1" max="<?php echo $row['qty']?>" class="input"/>
                        <p class="btn-area">
                        <i class="fa fa-trash"></i>
                        <span class="btn2"><a type='submit' style='color:#fff;' onclick="return confirm('Are you sure you want to delete this item?');" 
                        href=' ../cart/cart_remove.php?id=<?php echo $row['supply_id'] ?>'>Remove</a>
                        </p>
            </div>   
            </div>
            <?php 
            $i++;
            }
            }
            $counter=$counter+1;}
            ?>    
            </div>
            <div class="cartalert">
            <h1 ><?php if($no_of_user_products == 0) { echo 'no product in cart'   ?></h1>
            <?php }?>
        </div>
        </div>
        <div class="fright-bar">
            <?php if( $f ==  $urow['Street']){  ?>
            <div class="rbh">
            <div class="right-bar">
                <p><span>Subtotal</span><span>₱<?php echo number_format((float) $total, 2);?></span></p>
                <hr>
                <p><span>VAT (12%)</span><span>₱<?php echo number_format((float) $vat, 2);?></span></p>
                <hr>
                <p><span>Shipping</span><span>₱50</span></p>
                <hr>
                <p><span>Total</span><span>₱<?php echo number_format((float) $total + $vat + '50', 2);?></span></p>
                <a href="register.php?id=<?php echo $Account_ID?>"><i class="fa fa-shopping-cart"></i>Add Address</a>
                <br>
                <?php
                if($no_of_user_products>0 ){ 
                ?>
                <div class="red">
                <a onclick="return confirm('Are you sure you want to delete all item?');" href=" cart/removoall.php"><i class="fa fa-trash"></i>Remove all</a>
                </div>
                <?php 
                }else{
                ?>
                <?php
                }
                ?>
            </div>
            </div>
            <!-- <button type="submit" class="btn btn-primary" name="save">Remove Selected</button> -->
            <?php
            }elseif($no_of_user_products > 0 ){ 
            ?>
          
            <div class="rbh">
            <div class="right-bar">
                <p><span>Subtotal</span><span>₱<?php echo number_format((float) $total , 2);?></span></p>
                <hr>
                <p><span>VAT (12%)</span><span>₱<?php echo number_format((float) $vat , 2);?></span></p>
                <hr>
                <p><span>Shipping</span><span>₱50</span></p>
                <hr>
                <p><span>Total</span><span>₱<?php echo number_format((float) $total + $vat + '50',2);?>   
                </span></p>
                <button><i class="fa fa-shopping-cart"></i>Checkout</button>
                <br>    
                <div class="red">
                <a onclick="return confirm('Are you sure you want to delete all item?');" href=" cart/removoall.php"><i class="fa fa-trash"></i>Remove all</a>
                </div>
                </form> 
            </div>
            </div>
            <?php 
            }else{
            echo '<div class="rbh">
            <div class="right-bar">
                <p><span>Subtotal</span><span>₱ 0</span></p>
                <hr>
                <p><span>VAT (12%)</span><span>₱ 0</span></p>
                <hr>
                <p><span>Shipping</span><span>₱ 0</span></p>
                <hr>
                <p><span>Total</span><span>₱ 0</span></p>
                <a href=" ../productstore.php"><i class="fa fa-shopping-cart"></i>Shop</a>
                <br>
            </div>
            </div>';
            }
            ?>
        </div>
    </div>
 </section>   


        <?php
            include 'include/footer.php';
        ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="/Ecommerce/js/main.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
$("#checkAl").click(function () {
$('input:checkbox').not(this).prop('checked', this.checked);
});
</script>
</body>
</html>