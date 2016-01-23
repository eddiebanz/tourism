<?php 
	session_start();
	$currentyear = date('Y');
	$maxyear = $currentyear + 4;
?>

<h1>Set Moving Events</h1><br>

<form action='ayosngtalaan.php' method='post'>
	<label>Select Year:</label>
	<input type='number' name='taon' min='<?php echo $currentyear ?>' max='<?php echo $maxyear ?>'>
</form>

<?php 
	$currentyear = date('Y');
	$maxyear = $currentyear + 1;
	$query = "SELECT b.jyear, b.juliandate, a._id, a.name, a.scope FROM events a, pistahan b WHERE _id >= 'e9100' and a._id = b.event_id and b.jyear = ". $selectyear. " and (a.scope<>'workingh' and a.scope <> 'moon') ORDER BY a._id";
	$result = fetch_all($query);
?>

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