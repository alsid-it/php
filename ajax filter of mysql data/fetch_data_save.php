<?php

require_once ('config.php');

if(isset($_POST['min_price']) && isset($_POST['max_price'])){
    
    $min_price = $_POST['min_price'];
    $max_price = $_POST['max_price'];
    
    $query = "SELECT * FROM `pricelist` WHERE price_rub BETWEEN '$min_price'
             AND '$max_price'";
    
    $res = mysqli_query($con, $query);
    $count = mysqli_num_rows($res);
    
    if($count == 0){
        
        echo "Sorry No data found";
    }
    ?>

<div class="row">
                            <?php 
                            while ($row = mysqli_fetch_array($res)) {
                                $name = $row['name'];
                                $price_rub = $row['price_rub'];
                                $price_opt_rub = $row['price_opt_rub'];
                                $storage1_pcs = $row['storage1_pcs'];
                                $storage2_pcs = $row['storage2_pcs'];
                                $country = $row['country'];
                            ?>
                            
                            <div class="col-sm-4">
                                <div class="product_card">
                                    <div class="card">
                                     <h1><?php echo $name; ?></h1>
                                     <p class="desc"><?php echo $country; ?></p>
                                     <div class="row">
                                      <div class="col-sm-6">
                                      <p class="pricce"><?php echo $price_rub; ?></p>
                                      </div>
                                      <div class="col-sm-6">
                                      <p class="qty"><input type="number" 
                                      value="<?php echo $storage1_pcs + $storage2_pcs; ?>" name="product_quantity"></p>
                                      </div>
                                      </div>
                                      <p><button>Add to Cart</button></p>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
</div>

<?php
    }
?>
