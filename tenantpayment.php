<?php
include 'styles.php';
//include 'config.php';
if($_POST['submit']){
    $search=$_POST['search'];
    $monthpaid=$_POST['smonth'].'-'.$_POST['syear'];
    $qry=mysqli_query($config,"SELECT * FROM payments WHERE RoomID='$search' AND MonthPaid='$monthpaid'");
   
          
}
?>



<table width="90%" align="center" bgcolor="white">
    <tr>
        <td>
        <table width="20%" align="left" bgcolor="#1abc9c" class="floating_table">
        <tr><td>
        <ul>
            <li><a href="index.php"><img src="images/home.png" width="20" height="20" align="left"> Home </a></li>
        <li><a href="landlords.php"><img src="images/landlords.PNG" width="20" height="20" align="left">Landlords</a></li>
        <li><a href="property.php"><img src="images/property.PNG" width="20" height="20" align="left">Property</a></li>
        <li><a href="house.php"><img src="images/bed.PNG" width="20" height="20" align="left">Houses/Rooms</a></li>
        <li><a href="tenants.php"><img src="images/tenant.PNG" width="20" height="20" align="left">Tenants</a></li>
        <li><a href="payments.php"><img src="images/pay.PNG" width="20" height="20" align="left">Payments</a></li>
        <li><hr color="white"></li>
        <li><b>Tools</b></li>
        <li><hr color="white"></li>
        <li><a href="importdata.php"><img src="images/import.png" width="20" height="20"> Import Data</a></li>
        <li><a href="backup.php"><img src="images/export.png" width="20" height="20"> Backup Data</a></li>
        <li><hr color="white"></li>
        <li><b>Personal Settings</b></li>
        <li><hr color="white"></li>
        <li><a href="changepassword.php"><img src="images/key.PNG" width="20" height="20" align="left">Change Password</a></li>
        <li><a href="newuser.php"><img src="images/user.PNG" width="20" height="20" align="left">Add New User</a></li>
        </ul>
        </td></tr>
            
        </table>
            <table width="79%" align="center" bgcolor="#1abc9c" class="floating_table">
                <tr>
                    <td class="smallcontent">
                        <img src="images/pay.PNG" align="left" height="22" width="22"> Payments
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>
                        
                                       
                                    </td>
                                </tr>
                
            </table>
        <p>
            
            <table width="79%" align="center" >
                <tr>
                    <td>
                    
                    <table class="floating_table" width="75%" align="left">
                    <tr align="left">
                    <td>
                        <form name="searchpayments" method="post" action="">
                        <label>Search:</label></td><td> <input type="text" name="search"></td><td>Select Month:</td>
                        <td><select name="smonth">
                            <?php $month=date('M') ?>
                            <option value="<?php echo $month ?>" selected><?php echo $month ?> </option>
                            <option value="Jan">Jan</option>
                            <option value="Feb">Feb</option>
                            <option value="Mar">Mar</option>
                            <option value="Apr">Apr</option>
                            <option value="May">May</option>
                            <option value="Jun">Jun</option>
                            <option value="Jul">Jul</option>
                            <option value="Aug">Aug</option>
                            <option value="Sep">Sep</option>
                            <option value="Oct">Oct</option>
                            <option value="Nov">Nov</option>
                            <option value="Dec">Dec</option>
                        </select> 
                    <select name="syear">
                        <?php $year=date('y') ?>
                        <option value="<?php echo $year ?>"><?php echo $year ?></option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                    </select>
                    </td><td><input type="submit" name="submit" value="Search"></td>
                        </form>
                    </td>
                    </tr>
                    </table>
                    
                    <table class="floating_table" width="24%" align="right">
                    <tr><td>
                    <ul>
                    <li><a href="payments.php"><img src="images/pay.png" width="20" height="20" align="left"> New Payment</a></li>
                    <li><a href="importmpesa.php"><img src="images/import.png" width="20" height="20" align="left" >  Import M-Pesa Payments</a></li>
                    <li><hr color="white"></li>
                    <li> Payment Reports</li>
                    <li><hr color="white"></li>
                    <li><img src="images/AddProperty.png" width="20" height="20"><a href="tenantpayment.php">Per tenant</a></li>
                    <li><img src="images/AddProperty.png" width="20" height="20"><a href="plotpayment.php">Per Plot</a></li>
                    <li><img src="images/AddProperty.png" width="20" height="20"><a href="landlordpayment.php">Per Landlord</li>
                    </ul>
                    </td></tr>
                    </table>
                    <table width="75%" align="left" class="floating_paper">
                    <tr><td>House No</td><td>Amount Payable</td><td>Amount Paid</td><td>Balance</td><td>Date</td></tr>
                    <?php
                     while($row=mysqli_fetch_assoc($qry)){
                        $id=$row['id'];
                        $roomid=$row['RoomID'];
                        $propertyname=$row['PropertyName'];
                        $totalpayable=number_format($row['TotalPayable'],2);
                        $totalpaid=number_format($row['TotalPaid'],2);
                        $balance=number_format($row['Balance'],2);
                        $date=$row['DatePaid']; 
                        $receipt=$row['receipt'];
                        $slipnumber=$row['slipnumber'];
                        if(empty($receipt)){
                            $receiptimg='';
                        }else{
                            $receiptimg='<div class="tooltip"><a href="" target="_new"><a href="'.$receipt.'" target="_new"><img src="'.$receipt.'" width="20" height="20"><span class="tooltiptext">
                            <img src="'.$receipt.'" width="320" height="320"><br> Slip number: '.$slipnumber.'
                            </span></a></div>';
                        }
                        echo '<tr class="smallcontent" bgcolor="black" ><td >'.$propertyname.'/'.$roomid.'</td>
                        <td>'.$totalpayable.'</td>
                        <td>'.$totalpaid.'</td>
                        <td>'.$balance.'</td>
                        <td>'.$date.'</td>
                        <td><div class="tooltip"><a href="receipt.php?id='.$id.'" target="_new"><img src="images/icons/printer.ico" height="15" width="15"></a><span class="tooltiptext">Print</span></div>'.$receiptimg.' </td></tr>';
                   }
                    ?>
                    </table>;
                    <table width="75%" align="left">
                        <tr><td><?php
                   echo $msg;
                   ?></td></tr>
                    </table>
                   
                    
                    </td>
                    
                </tr>
            </table>
            </p>
        </td>
    </tr>
</table>

</table>