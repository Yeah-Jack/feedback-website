<?php
include("config.php");

if (isset($_POST['input'])) {
	$input = mysqli_real_escape_string($con, $_POST['input']);
	$query = "SELECT * FROM teachers WHERE 
              first_name LIKE '%{$input}%' OR 
              last_name LIKE '%{$input}%' OR 
              email LIKE '%{$input}%' OR 
              abbreviation LIKE '%{$input}%'";

	$result = mysqli_query($con, $query);

	if (mysqli_num_rows($result) > 0) {
		echo '<div class="grid gap-4">';
		while ($row = mysqli_fetch_assoc($result)) {
			echo '<div class="bg-white p-4 rounded-md shadow hover:shadow-md cursor-pointer transition-shadow" 
                       onclick="selectTeacher(\'' . htmlspecialchars($row['email']) . '\', \'' .
				htmlspecialchars($row['first_name']) . '\', \'' .
				htmlspecialchars($row['last_name']) . '\')">';
			echo '<div class="font-semibold text-lg text-slate-800">' .
				htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) .
				' <span class="text-sm text-slate-500">(' . htmlspecialchars($row['abbreviation']) . ')</span></div>';
			echo '<div class="text-slate-600">' . htmlspecialchars($row['email']) . '</div>';
			echo '</div>';
		}
		echo '</div>';
	} else {
		echo '<div class="text-center py-4 text-slate-600">Keine Lehrer gefunden</div>';
	}
}
?>