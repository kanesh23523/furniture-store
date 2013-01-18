<?php
    session_start();
    ob_start();
    if (!isset($_SESSION["basket"]))
    {
        $basket = array();
        $_SESSION["basket"] = $basket;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Your Basket &#124; DAVA</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
        <link href="css/home.css" rel="stylesheet" type="text/css" />
        <link href="css/basket.css" rel="stylesheet" type="text/css" />
<!--///////////////////////////////END OF STYLE SHEET ///////////////////////-->
        <script src="javascript/jquery-1.8.3.min.js" type="text/javascript"></script>
        <script src="javascript/validation.js" type="text/javascript"></script>
        
        <script type="text/javascript">
            
            //////// USING RAY'S JAVASCRIPT VALIDATION ///////
            function btnUpdate()
            {
                var frmUpdate = document.getElementById("frmUpdate");
                var qty = frmUpdate.qtyUpdate.value;

                if (isEmpty(qty))
                {
                    alert("Please enter quantity!");
                    return false;
                }

                if (!isInteger(qty))
                {
                    alert("Please enter whole number!");
                    return false;
                }

                if (qty < 0)
                {
                    alert("Please enter positive number!")
                    return false;
                }
                
                if (qty > 50)
                {
                    alert("Please contact sales team when ordering more than 50!")
                    return false;
                }
            }
            function checkout()
            {
                alert("Please log in to checkout");
                return false;
            }
        </script>
    </head>
    
    <body>
        <div id="container">
            <div id="headerDiv">
<!--/////////////////////////// WELCOME USER ////////////////////////////////-->  
                
                <?php
                if (isset($_POST["btnLogout"]))
                {
                    unset($_SESSION["customer"]);
                }
                if (isset($_SESSION["customer"]))
                {
                    $custName = $_SESSION["customer"]["name"];
                    echo "<span id='welcomeSpan'><a id='aWelcome' href='account.php'>Welcome, $custName</a></span>";
                    echo "  <script> 
                            $(function() 
                                {
                                    $('#login').remove();
                                })
                            </script>";
                }
                ?>
<!--///////////////////////// END OF WELCOME USER ///////////////////////////--> 
                <p>
                    <a id="login" href="login.php">login &#124;</a>
                    <a id="cart" href="basket.php">
                        <img src="css/images/imgCartW26xH26.png" width="26" height="26" alt="Cart Image"/>
                        my cart&nbsp;<?php $size = sizeof($_SESSION["basket"]); echo "<span id='nItems'>$size</span>"; ?>&nbsp;items
                    </a>
                </p>
            </div>
<!--///////////////////////////////NAVIGATION PANEL//////////////////////////-->
            <form>
                <div id="navigationDiv">
                    <ul>
                        <li>                      <a class="logo" href="index.php"></a>               </li>
                        <li>                      <a class="button" href="chairs.php">CHAIRS</a>      </li>
                        <li>                      <a class="button" href="chests.php">CHESTS</a>      </li>
                        <li>                      <a class="button" href="beds.php">BEDS</a>          </li>
                        <li class="txtNav">       <input type="text" name="txtSearch"/>               </li>
                        <li class="searchNav">    <input type="submit" name="btnSearch" value=""/>    </li>
                    </ul>
                </div>
            </form>
<!--///////////////////////////////END OF NAVIGATION/////////////////////////-->
            
            <div id="basketDiv">
                <h3 id="basketHeading"> Shopping Basket </h3>
                
<!--/////////////////////////////// BASKET TABLE ////////////////////////////-->
                <table id="basketTable">
                        <tr>
                            <th id="thProdName" colspan="2">Product Name</th> <th>Price</th> <th>Quantity</th> <th id="thLineTotal">&nbsp;&nbsp;Line Total</th> 
                        </tr>
                        <tr><td class='tdFirstThinLine' colspan='5'> </td></tr>
                        <?php
                        $update = false;
                        $basket = $_SESSION["basket"];    

                        if (isset($_REQUEST["qtyUpdate"]))  // IF UPDATE CLICKED THEN GET UPDATE ID, QUANTITY VALUE
                        {
                            $idToUpdate = $_REQUEST["hidIdUpdate"];
                            $qtyToUpdate = $_REQUEST["qtyUpdate"];
                            $update = true;
                        }
                        
                        foreach ($basket as $key => $item)      // DISPLAY EVERYTHING IN BASKET
                        {
                            $id = $item["id"];
                            $remove = false;
                            if ($update && ($id == $idToUpdate))    // IF EXISTING ID MATCHES UPDATE ID
                            {
                                if ($qtyToUpdate == 0)      // IF UPDATE QUANTITY IS 0 THEN PLACE jQUERY TO REMOVE ITEM AND SET $REMOVE TRUE 
                                {
                                    echo "<script>
                                        $(function() 
                                        {
                                            var fade = $('#tr$id');
                                            fade.fadeOut();
                                            
                                        }) 
                                        </script>";
                                    $remove = true;
                                }
                                else
                                {
                                    $basket[$key]["qty"] = $qtyToUpdate;
                                    
                                }
                            }
                            $types = $item["types"];
                            $imgName = $item["imageName"]; 
                            $name = $item["name"];
                            $price = $item["price"];
                            $qty = $basket[$key]["qty"];
                            $cost = $qty * $price;
                            echo "<tr id='tr$id'>
                                    <td class='tdProdImg'> <img src='css/images/$types/$imgName' width='50' height='52' alt='image $imgName'/> </td>
                                    <td class='tdName'> <p>$name</p> </td>
                                    <td class='tdPrice'> &pound$price </td>
                                    <td class='tdQty'> <form id='frmUpdate'> <input type='hidden' name='hidIdUpdate' value='$id'/> <input type='text' name='qtyUpdate' value='$qty'/> <input type='submit' value='update' onclick='return btnUpdate();'/> </form></td>
                                    <td class='tdLineTotal'>&nbsp;&nbsp;&pound$cost </td>
                                  </tr>
                                  <tr><td class='tdThinLine' colspan='5'> </td></tr>";
                            if ($remove)
                            {
                                unset($basket[$key]);   // REMOVING ITEM FROM THE BASKET
                                $_SESSION["basket"] = array_values($basket); // UPDATE SESSION BASKET
                                $nItems = sizeof($_SESSION["basket"]);
                                echo "  <script>
                                            $(function() 
                                            {
                                                $('#nItems').slideUp('slow', function() 
                                                {
                                                    $('#nItems').replaceWith('<span>$nItems</span>');
                                                });
                                            }); 
                                        </script>";
                            }
                            $_SESSION["basket"] = $basket;
                            $basketSize = sizeof($_SESSION["basket"]);
                            $setId = "";
                            $setName = "";
                            $setPrice = "";
                            $setQty = "";
                            $setImageName = "";
                            $setTypes = "";
                            for ($i = 0; $i < $basketSize; $i++)
                            {
                                $setId        .= ":".$_SESSION["basket"][$i]["id"]; 
                                $setName      .= ":".$_SESSION["basket"][$i]["name"]; 
                                $setPrice     .= ":".$_SESSION["basket"][$i]["price"]; 
                                $setQty       .= ":".$_SESSION["basket"][$i]["qty"];
                                $setImageName .= ":".$_SESSION["basket"][$i]["imageName"]; 
                                $setTypes     .= ":".$_SESSION["basket"][$i]["types"];
                            }
                            $subId = substr($setId, 1);
                            $subName = substr($setName, 1);
                            $subPrice = substr($setPrice, 1);
                            $subQty = substr($setQty, 1);
                            $subImageName = substr($setImageName, 1);
                            $subTypes = substr($setTypes, 1);

                            setcookie("basket[id]", $subId, time()+ 3600);
                            setcookie("basket[name]", $subName, time()+ 3600);
                            setcookie("basket[price]", $subPrice, time()+ 3600);
                            setcookie("basket[qty]", $subQty, time()+ 3600);
                            setcookie("basket[imageName]", $subImageName, time()+ 3600);
                            setcookie("basket[types]", $subTypes, time()+ 3600);
                        }
                        /////////////// END OF DISPLAYING BASKET DATA //////////

                        if($basket == null)     // IF BASKET IS EMPTY THEN REMOVE TABLE, DISPLAY MESSAGE
                        {
                            echo "<script>
                                    $(function() 
                                    {
                                        $('#basketHeading').append('<strong>Is Empty</strong>');
                                        $('#basketHeading').after('<h5>You have no items in your basket.</h5>');
                                        $('#basketTable').remove();
                                        $('#aCheckout').remove();
                                    }) 
                                  </script>";
                        }
                        else
                        {
                            $total = 0;
                            foreach ($basket as $key => $item)      // DISPLAY TOTAL, VAT, SHIPPING
                            {
                                $price = $item["price"];
                                $qty = $item["qty"];
                                $total = $total + ($price * $qty);
                            }
                            $shippingCost = 50;
                            define("VATRATE", 0.2);
                            $vat = VATRATE * $total;
                            $grandTotal = $total + $shippingCost + $vat;
                            echo "<tr class='trEmptySpace'><td colspan='5'></td></tr> ";
                            echo "<tr>  <td colspan='2'></td>  <td class='tdEnd' colspan='2'>  Subtotal:       </td>  <td class='tdEndData'>&nbsp;&nbsp;&pound;$total         </td>  </tr>";
                                 
                            echo "<tr>  <td colspan='2'></td>  <td class='tdEnd' colspan='2'>  Shipping Cost:  </td>  <td class='tdEndData'>&nbsp;&nbsp;&pound;$shippingCost  </td>  </tr>";
                                    
                            echo "<tr>  <td colspan='2'></td>  <td class='tdEnd' colspan='2'>  VAT:            </td>  <td class='tdEndData'>&nbsp;&nbsp;&pound;$vat           </td>  </tr>";
                                 
                            echo "<tr>  <td colspan='2'></td>  <td class='tdGrandTotal' colspan='2'>  Grand Total:    </td>  <td class='tdGrandTotalData'>&nbsp;&nbsp;&pound;$grandTotal    </td>  </tr>";
                        }
                        ?>
                    </table>
                    <div id="checkOutDiv">
                        <a id="aContinueShop" href="index.php">Continue shopping</a>
                        <?php
                            if (isset($_SESSION["customer"]))
                            {
                                echo "<a id='aCheckout' href='checkout.php'>Proceed to checkout</a>";
                            }
                            else
                            {
                                echo "<a onclick='return checkout();' id='aCheckout' href='checkout.php'>Proceed to checkout</a>";
                            }
                        ?>
                        
                    </div>
                    <div id="basketThickLine">
                        
                    </div>
            </div>
<!--///////////////////////////////END OF BASKET TABLE DIV/////////////////////////-->
            
            <div id="footerDiv">
                <p>
                    <a href="#">Page Last Updated: December 31, 2012</a>
                    &#124;
                    <a href="#">Page Editor: Davaasuren Dorjdagva</a>
                    &#124;
                    <a href="#">Terms of Use</a>
                    &#124;
                    <a href="#">Privacy Policy</a>
                    &#124;
                    <a href="#">&copy;2013 All Rights Reserved.</a>
                </p>
            </div>
        </div>
<!--///////////////////////////////END OF CONTAINER/////////////////////////-->
    </body>
</html>
<?php ob_flush(); ?>

