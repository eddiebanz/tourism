<?php 
	require('new-connection.php');
	session_start();

	$query = "SELECT _id, name, scope FROM events WHERE _id >= 'e9100' and scope<>'workingh' ORDER BY _id";
	$result = fetch_all($query);
?>

<h1>Set Moving Events</h1><br>
<form action="kalendaryo.php" method="post">
	<table border="5px" cellspacing="5px">
		<tr>
			<td>Event id</td>
			<td>Event Name</td>
			<td>Event Type</td>
			<td>Event Date</td>
		</tr>
		<?php foreach ($result as $key=>$value) {
			echo '<tr>';
				echo '<td>'.$value['_id']  .'</td>';
				echo '<td>'.$value['name'] .'</td>';
				echo '<td>'.$value['scope'].'</td>';
				echo "<td><input type='date' name='".$value['_id']."''></td>";
			echo '</tr>';
		} ?>
	</table><br>
	<input type="submit" value="Done">
</form>