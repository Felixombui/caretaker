<?php
include 'styles.php';
$lastID=$_GET['id'];
if(empty($lastID)){
    $qry=mysqli_query($config,"SELECT * FROM tenants LIMIT 1");
    $row=mysqli_fetch_assoc($qry);
    $lastID=$row['id'];
}
$qry = mysqli_query($config, "SELECT * FROM tenants WHERE id>'$lastID' LIMIT 15");
if ($_POST['search']) {
    $search = addslashes($_POST['searchlandlord']);
    $qry = mysqli_query($config, "SELECT * FROM tenants WHERE tenantid LIKE '%$search%' AND id>'$lastID' OR FirstName LIKE '%$search%' id>'$lastID' or OtherNames LIKE '%$search%' id>'$lastID' OR PropertyName LIKE '%$search%' id>'$lastID' or RoomNumber LIKE '%$search%' id>'$lastID' ORDER BY RoomNumber ASC LIMIT 15");
}
?>

<table width="100%" align="center" bgcolor="white">
    <tr>
        <td>
        <table width="20%" align="left" style="background-color: cyan; color:blue; border-collapse:collapse; border-right:1px solid orange;">
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
            <table width="79%" align="right">
                <tr class="floating_table">
                    <td>
                        <form name="landlordsearch" method="POST" action="">
                            <table width="100%">
                                <tr>
                                    <td><input type="text" name="searchlandlord" placeholder="Search tenants" size="32"><input type="submit" name="search" value="Search"></td>
                                    <td width="15%"><a href="newtenant.php"><img src="images/add.PNG" height="20" width="20" align="left">Add Tenant</a></td><td><a href="importtenants.php"><img src="images/import.png" width="20" height="20" align="left">Import</a></td>
                                </tr>
                            </table>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table width="100%" class="floating_table">
                            <tr bgcolor="#807c7b">
                                <td><b>ID</b></td>
                                <td><b>Names</b></td>
                                
                                <td><b>Property Name</b></td>
                                <td><b>House No<b></td>
                                <td>&nbsp;</td>

                            </tr>
                            <?php
                            while ($row = mysqli_fetch_assoc($qry)) {
                                $id=$row['id'];
                                $tenantid=$row['tenantid'];
                                echo '<tr class="smallcontent" bgcolor=""><td>' . $row['tenantid'] . '</td><td>' . $row['FirstName'] . ' ' . $row['OtherNames'] . '</td><td>' . $row['PropertyName'] . '</td><td width="15%">' . $row['RoomNumber'] . '</td><td><div class="tooltip"><a href="edittenant.php?id='.$id.'"><img src="images/edit.png" width="16" height="16" ></a><span class="tooltiptext">Edit</span></div><div class="tooltip">
                                <a href="evacuate.php?id='.$id.'"><img src="images/icons/book_delete.ico" width="15" height="15" ></a><span class="tooltiptext">Evacuate</span>
                                </div></td></tr>';
                                $lastID=$row['id'];
                            }
                            $records=mysqli_num_rows($qry);
                            ?>
                        </table><p>
                        <table width="100%" bgcolor="#807c7b" class="floating_table">
                            <tr><td>
                                <div>
                                    <div class="smallcontent">Records found: <?php echo $records ?></div>
                        </div>
                            </td><td><div class="smallcontent"><?php echo' <a href="tenants.php?id='.$lastID.'">Next</a> ' ?></td></tr>
                        </table></p>
                    </td>
                </tr>
            </table>

</table>