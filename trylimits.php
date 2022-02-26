<?php
include 'config.php';
$LastID=$_GET['id'];
if(empty($LastID)){
    $qry=mysqli_query($config,"SELECT * FROM tenants LIMIt 1");
    $row=mysqli_fetch_assoc($qry);
    $LastID=$row['id'];
    $newqry=mysqli_query($config,"SELECT * FROM tenants WHERE id>$LastID LIMIT 20");
}else{
    $newqry=mysqli_query($config,"SELECT * FROM tenants WHERE id>$LastID LIMIT 20");
}



?>
<table>
    <tr><td>id</td></tr>
    <?php
    while($newrow=mysqli_fetch_assoc($newqry)){
        echo '<tr><td>'.$newrow['id'].'</td></tr>';
        $LastID=$newrow['id'];
    }
    
        ?>
<tr><td><?php echo '<a href="trylimits.php?id='.$LastID.'"Next >> </a>' ?></td></tr>
</table>