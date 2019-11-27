<!DOCTYPE html>
<html>
<head>
	<title>All Users</title>
</head>
<body>
    <?php
    spl_autoload_extensions(".php");
    spl_autoload_register();

    use yasmf\HttpHelper;
    ?>

    <h1>Tous les utilisateurs</h1>
	<!-- Formulaire de recherche -->
	<form action="PremiersPasPdoController.php" method="GET">
		<label for="letter">Start with letter: </label>
		<input type="text" name="lettre" value="<?php echo $lettre; ?>">
		<label for="status"> and status : </label>
		<select name="status">
			<option value="active"
				<?php if ($status =! null && $status == "active"){echo 'selected';}?>
				>Active account</option>
			<option value="waiting"
					<?php if ($status =! null && $status == "waiting"){echo 'selected';}?>
			>Waiting for account validation</option>

			<option value="waitingDelete"
					<?php if ($status =! null && $status == "waitingDelete"){echo 'selected';}?>
			>Waiting for account deletion</option>
		</select>
        <input hidden name="controller" value="PremiersPasPdo">
        <input type="submit" name="valider" value="ok">
	</form><br>

	<!-- Affichage du résultat de la requête -->
	<table>
		<thead>
			<td>Id</td>
			<td>Username</td>
			<td>Email</td>
			<td>Status</td>
		</thead>
		<?php
			while ($row = $users->fetch())
			{
			    echo '<tr>';
			    	echo '<td>' . $row['id'] . '</td>';
			    	echo '<td>' . $row['username'] . '</td>';
			    	echo '<td>' . $row['email'] . '</td>';
			    	echo '<td>' . $row['name'] . '</td>';
						if ($row['idStatus'] == 1) {
							echo '<td><a href="all_users.php?status_id=3&user_id='. $row['id'] .'&action=askDeletion">Supprimer</a></td>';
						}
			    echo '</tr>';
			}
		?>
	 </table>

</body>
</html>
