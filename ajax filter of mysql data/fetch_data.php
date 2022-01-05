<?php

require_once ('config.php');
error_reporting(0);

//echo '<pre>';
//print_r($_POST);
//echo '</pre>';
//echo '<br>';
//echo '<br>';

if(isset($_POST['min_price']) && isset($_POST['max_price'])){
    
    $min_price = $_POST['min_price'];
    $max_price = $_POST['max_price'];
    $sn = $_POST['sn'];
    
    if ($_POST['way'] == 1){
    $query = "SELECT * FROM `pricelist` WHERE price_rub BETWEEN '$min_price'
             AND '$max_price'";
    }else{
    $query = "SELECT * FROM `pricelist` WHERE price_opt_rub BETWEEN '$min_price'
             AND '$max_price'";    
    }
    
    if ($_POST['way'] == 1){
        $way = "price_rub";
    }else{
        $way = "price_opt_rub";
    }
    
    if ($_POST['way2'] == 1){
       $snsql = "AND storage1_pcs > $sn AND storage2_pcs > $sn";
    }else{
       $snsql = "AND storage1_pcs < $sn AND storage2_pcs < $sn";
    }
    
    $query = $query . '' . $snsql;
    
    $res = mysqli_query($con, $query);
    $count = mysqli_num_rows($res);
    
//    Сообщение об ошибке
    $err = 0;
    
   
    if(mb_strlen($_POST['min_price']) < 1){
       echo "<p id='msgg'>Заполните параметр цены 'ОТ'</p>";
       $err++;
    }
    
    if(mb_strlen($_POST['max_price']) < 1){
       echo "<p id='msgg'>Заполните параметр цены 'ДО'</p>";
       $err++;
    }
    
    if(mb_strlen($_POST['sn']) < 1){
       echo "<p id='msgg'>Заполните количество штук на складе</p>";
       $err++;
    }
    
    
//    echo '<p id="msgg"></p>';
    if($err > 0){
        die();
    }else{
    if($count == 0){
        echo "<p id='msgg'>Нет товаров соответствующих данным параметрам</p>";
        die();
    }}
    ?>


<div class="row">
    <table border="1">
<?php   
       $remh;
       $reml;
       $ft = 1;
       
    if($res1 = $con->query("SELECT `id`, `price_rub`, `price_opt_rub` FROM `pricelist`")){
        while ($row1 = $res1->fetch_assoc()){
        if ($ft === 1){
           $hp = $row1['price_rub'];
           $lop = $row1['price_opt_rub'];
           $ft = 0;
           $remh = $row1['id'];
           $reml = $row1['id'];
        }else{
            if ($row1['price_rub'] > $hp){
                $hp = $row1['price_rub'];
                $remh = $row1['id'];
            }
            if ($row1['price_opt_rub'] < $lop){
                $lop = $row1['price_opt_rub'];
                $reml = $row1['id'];
            } 
        }
    }
 }

 if($res2 = $con->query("SELECT * FROM `pricelist` WHERE $way BETWEEN '$min_price'
             AND '$max_price' $snsql")){
    echo '<tr>
    <td>Имя</td>
    <td>Цена</td>
    <td>Оптовая цена</td>
    <td>Наличие на складе 1</td>
    <td>Наличие на складе 2</td>
    <td>Страна производитель</td>
    <td>Примечание</td>
    </tr>';
    $sp = 0;
    $sop = 0;
    $st1 = 0;
    $st2 = 0;
    $amount = 0;
   
    while ($row2 = $res2->fetch_assoc()){
        $amount++;
        echo '<tr class="col-sm-4">';
        echo '<td>';
        echo $row2['name'];
        echo '</td>';
        
        if($row2['price_rub'] === $hp){
            echo '<td bgcolor="#F56E77">';
            echo $row2['price_rub'];
            $sp += $row2['price_rub'];
            echo '</td>';
        }else{
            echo '<td>';
            echo $row2['price_rub'];
            $sp += $row2['price_rub'];
            echo '</td>';
        }
        
        if($row2['price_opt_rub'] === $lop){
            echo '<td bgcolor="#7DF585">';
            echo $row2['price_opt_rub'];
            $sop += $row2['price_opt_rub'];
            echo '</td>';
        }else{
            echo '<td>';
            echo $row2['price_opt_rub'];
            $sop += $row2['price_opt_rub'];
            echo '</td>';
        }
        
        echo '<td>';
        echo $row2['storage1_pcs'];
        $st1 += $row2['storage1_pcs'];
        echo '</td>';
        
        echo '<td>';
        echo $row2['storage2_pcs'];
        $st2 += $row2['storage2_pcs'];
        echo '</td>';
        
        echo '<td>';
        echo $row2['country'];
        echo '</td>';
        
        $sks = $row2['storage1_pcs'] + $row2['storage2_pcs'];
        if($sks < 20){
            echo '<td bgcolor="#F5D3CA">';
            echo 'Осталось мало!! Срочно докупите!!!';
            echo '</td>';            
        }else{
            echo '<td>';
            echo '-';
            echo '</td>';
        }
        
        echo '</tr>';
        echo '</div>';
    }
    echo '<tr>';
    echo '<td>Имя</td>';
    echo '<td> Средняя цена: ' 
    . round($sp/$amount, PHP_ROUND_HALF_UP) 
            . '</td>';
    echo '<td>Средняя опт. цена: ' 
    . round($sop/$amount, PHP_ROUND_HALF_UP) 
            . '</td>';
    echo '<td> Всего на складе 1: ' . $st1 . '</td>';
    echo '<td> Всего на складе 2: ' . $st2 . '</td>';
    echo '<td>Страна производитель</td>';
    echo '<td>Примечание</td>';
    echo '</tr>';
    }
       
 ?>
</table>                       
</div>

<?php
}
?>
