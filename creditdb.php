<html>
<head><title>View all users</title>
<style>
.bg {  
 background-color:#ddd;
}
</style>
<body class="bg">

<center>
<h3>All Users</h3><?php

// Create connection
$conn = mysql_connect("localhost", "root", "vipanchi") or die("couldn't select mysql");

// connection db

$db=mysql_select_db("credit", $conn) or die("couldn't select db");

$query = "select * from vcredit";
$res = mysql_query($query) or die ("query faild:". mysql_error());

echo "<table border= '0'>";
echo "<tr>";
echo "<th>Name</th><th>Email</th> <th>Current_Credit</th><th>Ac_no</th>";
echo "</tr>";
while($row= mysql_fetch_array($res))
{
echo "<tr>";
echo "<td>".$row['Name']."</td>"; 
echo "<td>".$row['Email']."</td>";
echo"<td>".$row['Current_Credit']."</td>";
echo"<td>".$row['Ac_no']."</td>";
echo "</tr>";
}
echo"</table>";

mysql_close($conn);

?></center>
</body>
</html>