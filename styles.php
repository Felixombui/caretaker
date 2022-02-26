<?php
include 'config.php';
require('fpdf/fpdf.php');
session_start();
if ($_SESSION['logged'] == false) {
    header('location:login.php');
    $fullnames = "";
} else {
    $fullnames = $_SESSION['fullnames'];
    $username = $_SESSION['MM_Username'];
    $userid=$_SESSION['UserID'];
    $profqry=mysqli_query($config,"SELECT * FROM users WHERE id='$userid'");
    $profrow=mysqli_fetch_assoc($profqry);
    $names=$profrow['Names'];
    $email=$profrow['EmailAddress'];
    $phone=$profrow['PhoneNumber'];
    $rights=$profrow['Rights'];
    $profile='User ID: '.$userid.'<br>Username: '.$username.'<br>Full Names: '.$names.'<br>Email Address: '.$email.'<br>Phone Number: '.$phone.'<br>User Rights: '.$rights.'';
}


$qry = mysqli_query($config, "SELECT * FROM companydetails");
$row = mysqli_fetch_assoc($qry);
$companyname = $row['Names'];
$month=date('M-y');
$day=date('d');
if($day>11 AND $day<13){
    $houseqry=mysqli_query($config,"SELECT * FROM rooms");
    while($houserow=mysqli_fetch_assoc($houseqry)){
        $roomid=$houserow['RoomID'];
        $plotno=$houserow['PropertyName'];
        $rentpayable=$houserow['RentPayable'];
        $Deposit=$houserow['Deposit'];
        $water=$houserow['Water'];
        $electricity=$houserow['Electricity'];
        $gabbage=$houserow['gabbage'];
        //Check tenant
        $tenantqry=mysqli_query($config,"SELECT * FROM tenants WHERE RoomID='$roomid' AND PropertyName='$plotno'");
        if(mysqli_num_rows($tenantqry)>0){
            $tenantrow=mysqli_fetch_assoc($tenantqry);
            $names=$tenantrow['FirstName'].' '.$tenantrow['OtherNames'];
            $tenantid=$tenantrow['id'];
            //check payments
            $paymentqry=mysqli_query($config,"SELECT * FROM payments WHERE tenantid='tenantid' ORDER BY id DESC LIMIT 1");
            if(mysqli_num_rows($paymentqry)>0){
                $paymentrow=mysqli_fetch_assoc($paymentqry);
                $paymentmonth=$paymentrow['MonthPaid'];
                $thisbalance=$paymentrow['Balance'];
                
                $Deposit='0.0';
                if($paymentmonth==$month){
                    $balbf='0.0';
                    $paymentid=$paymentrow['id'];
                    //check if there is balance for his month
                    if($thisbalance>0){
                        if($thisbalance>$rentpayable){
                            $penalty=10/100 * $rentpayable;
                        }else{
                            $penalty=10/100 * $thisbalance;
                        }
                        $totalbalance=$thisbalance+$penalty;
                        $updateqry=mysqli_query($config,"UPDATE payments SET penalty='$penalty', Balance='$totalbalance' WHERE id='$paymentid'");
                    }
                }else{
                    $balbf=$paymentrow['Balance'];
                    $penalty=10/100 * $rentpayable;
                    $totalpayable=$thisbalance+$rentpayable+$water+$electricity+$gabbage+$penalty;
                    $insertqry=mysqli_query($config,"INSERT INTO payments(RoomID,PropertyName,tenantnames,BroughtFoward,Rent,Water,Electricity,gabbage,penalty,TotalPayable,TotalPaid,Balance,MonthPaid,tenantid) VALUES('$roomid','$plotno','$names','$thisbalance','$rentpayable','$water','$electricity','$gabbage','$penalty','$totalpayable','0.0','$totalpayable','$month','$tenantid')");
                }
            }else{
                $penalty=10/100 * $rentpayable;
                $totalpayable=$deposit+$rentpayable+$water+$electricity+$gabbage+$penalty;
                $insertqry=mysqli_query($config,"INSERT INTO payments(RoomID,PropertyName,tenantnames,BroughtFoward,Rent,Deposit,Water,Electricity,gabbage,penalty,TotalPayable,TotalPaid,Balance,MonthPaid,tenantid) VALUES('$roomid','$plotno','$names','$thisbalance','$rentpayable','$deposit','$water','$electricity','$gabbage','$penalty','$totalpayable','0.0','$totalpayable','$month','$tenantid')"); 
            }
            
        }

    }
}
?>
<html>

<head>
    <title><?php echo $companyname ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* Style the body */

        body {
            font-family: arial;
            margin: 0;
            background-color: none;
            color: white;
            
        }
        .floating_table{
            visibility: visible;
            border: 1px solid orange;
            box-shadow: 5px 10px 8px #888888;
            text-align: center;
            font-size: 12;
            background-color: cyan;
            color:maroon;

        }
        ul{
            list-style: none;
            text-align: left;
            color: blue;
        }
        /* Header/Logo Title */

        .header {
            padding: 30px;
            text-align: center;
            background: white; <!--#1abc9c;-->
            color: aqua;
            font-size: 30px;
        }

        /* Page Content */

        .content {
            padding: 20px;
        }

        .userplace {
            padding: 5px;
            text-align: unset;
            background-color: #1abc9c;
            color: grey;
        }

        .menu {
            width: 19%;
            float: left;
            background-color: blue;
            height: 75%;
            padding: 5px;
        }
        .menu a{
            color:blue;
        }
        .err{
            text-align: left;
            font-size: 12;
            border-style: none;
            width: auto;
            height: auto;
            color: aqua;
            background-color: red;
            padding: 5px;
            box-shadow: 5px 10px 8px #888888;
            
           
            
        }
        .success{
            text-align: left;
            font-size: 12;
            border-style: none;
            width: auto;
            height: auto;
            color: aqua;
            background-color: green;
            padding: 5px;
            box-shadow: 5px 10px 8px #888888;
        }

        .bodycontent {
            width: 80%;
            float: right;
            height: auto;
        }

        .smallcontent {
            text-align: left;
            font-size: 12;
            border-style: none;
            width: auto;
            height: auto;
            color: maroon;
        }
        input[type=text],select{
            
            border-radius: 5px;
            width: 200px;
            float: left;
        }
        input[type=email]{
            border-radius: 5px;
            
        }
        input[type=password]{
            border-radius: 5px;
        }
       
    </style>
    <style>
    .nicetable {
        border: 1px;
        border-style: solid;
        font-size: 13;
        border-collapse: collapse;
    }
    .tooltip{
        position: relative;
        display: inline-block;
        border-bottom: 1px dotted black;
    }
    .tooltiptext{
        visibility: hidden;
        width: 120px;
        background-color: #555;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        bottom: 125%;<!--initially 125% -->
        top:-50%;
        margin-left: -60px;
        opacity: 0;
        transition: opacity 0.3s;
    }
    .profiletiptext{
        visibility: hidden;
        width: 320px;
        background-color: #555;
        color: #fff;
        text-align: left;
        border-radius: 6px;
        padding: 5px;
        position: absolute;
        z-index: 5;
        <!--bottom: 0%;--> <!--initially 125% -->
        top:-250%;
        margin-left: -60px;
        opacity: 0;
        transition: opacity 0.3s;
    }
    .tooltip .profiletiptext::after{
        content="";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #555 transparent transparent transparent;
    }
    .tooltip:hover .profiletiptext{
        visibility: visible;
        opacity: 1;
    }
    .tooltip .tooltiptext::after{
        content="";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #555 transparent transparent transparent;
    }
    .tooltip:hover .tooltiptext{
        visibility: visible;
        opacity: 1;
    }
        a:link,
        a:visited {
            background-color: none;
            color: blue;
            padding: 2px 1px;
            /*text-align: left;*/
            text-decoration: none;
            display: inline-block;
            /*width: 100%;*/
            font-size: 12;
        }

        a:hover,
        a:active {
            /*background-color: lightblue;*/
            text-decoration: none;
            font-weight: bold;
        }
         /* Add a black background color to the top navigation */
.topnav {
  background-color: #333;
  overflow: hidden;
}
.floating_paper {
        visibility: visible;
        border: 1px;
        border-style: solid;
        box-shadow: 5px 10px 8px #888888;
        text-align: center;
        font-size: 12;
        background-color: white;
    }

/* Style the links inside the navigation bar */
.topnav a {
  float: left;
  display: block;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

/* Change the color of links on hover */
.topnav a:hover {
  background-color: #ddd;
  color: black;
}

/* Add an active class to highlight the current page */
.topnav a.active {
  background-color: #4CAF50;
  color: white;
}

/* Hide the link that should open and close the topnav on small screens */
.topnav .icon {
  display: none;
} 
.menulinks{
    margin-top: 5px;
    margin-right: 5px;
    float: left;
    border: 1px solid orange;
    color: black;
    width: 100px;
    height: 50px;
    padding: 5px;
    text-align: center;
}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

</html>

<br><div class="no-print">
<table width="100%" align="left" style="text-align: left; background-color:orange; margin-bottom:5px;">
    <tr><td style="padding: 10px;"><h3>
    <?php echo $companyname ?></h3>
</td></tr>
</table>

<table width="100%" align="center" style="background-color: blue;">
    <?php

    echo '<tr><td align="left"><div class="tooltip"><div class="smallcontent">
        <img src="images/user.PNG" width="15" height="15" align="left">' . $fullnames . '<span class="profiletiptext">'.$profile.'</div></div></td>       


        <td align="right"><a href="logout.php">Logout</a> </td></tr>';
    ?>

</table></div>
<br>