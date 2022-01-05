<?php
require_once ("fetch.php");
require_once ('config.php');
?>
<html>
    <head>
        <meta name="viewport" content="width-device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <div class="container">
            
            <div class="row">
                
                <div class="col-sm-3">
                    
                    <span class="shead">Показать товары, у которых </span>
                    <div id="filters">
                    <select>
                        <option value="1">Розничная цена</option>
                        <option value="2">Оптовая цена</option>
                    </select>
                    </div>
                    <span class="shead">от</span>
                    <!--<div class="price-range-block">-->    
    
	    
			<!--<div id="slider-range" class="price-filter-range" name="rangeInput"></div>-->

			<input type="number" min=0 max="999998" oninput="validity.valid||(value='0');" id="min_price" class="price-range-field" />
                        <span class="shead"> до </span>
                        <input type="number" min=0 max="999999" oninput="validity.valid||(value='10000');"  id="max_price" class="price-range-field" />
                        <span class="shead"> рублей и на складе </span>
                        <select name="way" id="way">
                        <option value="" disabled="" selected="">Более</option>
                        <option value="">Более</option>
                        <option value="">Менее</option>
                        </select>
                        <input>
                        <span class="shead"> штук. </span>
                        <button>ПОКАЗАТЬ ТОВАРЫ</button>
<!--			<button class="price-range-search" id="price-range-submit">ПОКАЗАТЬ ТОВАРЫ</button>-->
						

                    <!--</div>-->
                    
                </div>
                <div class="col-sm-9">
                    <div id="searchResults" class="search-results-block">
                        
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

 if($res2 = $con->query("SELECT * FROM `pricelist`")){
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
                    </div>
                </div>
            </div>
        </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>    

    <script type="text/javascript" src="js/script.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function(){
            
                
        
                function filterProducts(){
                    
                    $("#searchResults").html("<p>Loading....<p>");
                    
                    var min_price = $("#min_price").val();
                    var max_price = $("#max_price").val();
                    
                    //alert(min_price + max_price);
                    
                    $.ajax({
                        
                        url:"fetch_data.php",
                        type: "POST",
                        data: {min_price:min_price,max_price:max_price},
                        success:function(data){
                            
                            
                        $("#searchResults").html(data);    
                        }
                    });
                }
                
                $("#min_price, #max_price").on('keyup',function(){
                    filterProducts();
                });
            
            $("#slider-range").slider({
		range: true,
		orientation: "horizontal",
		min: 0,
		max: 10000,
		values: [0, 10000],
		step: 100,

		slide: function (event, ui) {
		  if (ui.values[0] == ui.values[1]) {
			  return false;
		  }
		  
		  $("#min_price").val(ui.values[0]);
		  $("#max_price").val(ui.values[1]);
                  
                  filterProducts();
	  }
	  });
          
          $("#min_price").val($("#slider-range").slider("values", 0));
	  $("#max_price").val($("#slider-range").slider("values", 1));
          
        })
    </script>
    </body>
</html>

<?php


