<?php
	// Connexion à la BDD
	$host = 'localhost';
	$db   = 'my_activities';
	$user = 'root';
	$pass = 'root';
	$charset = 'utf8mb4';
	$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
	$options = [
	    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
	    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	    PDO::ATTR_EMULATE_PREPARES   => false,
	];
	try {
	     $pdo = new PDO($dsn, $user, $pass, $options);
	} catch (PDOException $e) {
	     throw new PDOException($e->getMessage(), (int)$e->getCode());
	}

	// traitement du formulaire
	$statusID = 2;
	$lettreUsername = '';

	if (isset($_POST['lettre']) && isset($_POST['status'])) {
		$lettreUsername = htmlspecialchars($_POST['lettre']);
		if ($_POST['status'] == "active") {
			$statusID = 2;
		} else if ($_POST['status'] == "waiting") {
			$statusID = 1;
		} else if ($_POST['status'] == "waitingDelete") {
			$statusID = 3;
		}
	}
 ?>

<!DOCTYPE html>
<html>
<head>
	<title>All Users</title>
</head>
<body>
	<h1>Tous les utilisateurs</h1>
	<form action="all_users.php" method="post">
		<label for="letter">Start with letter: </label>
		<input type="text" name="lettre" value="<?php echo $lettreUsername; ?>">
		<label for="status"> and status : </label>
		<select name="status">
			<option value="active"
				<?php if (isset($_POST['status']) && $_POST['status'] == "active"){echo 'selected';}?>
				>Active account</option>
			<option value="waiting"
					<?php if (isset($_POST['status']) && $_POST['status'] == "waiting"){echo 'selected';}?>
			>Waiting for account validation</option>

			<option value="waitingDelete"
					<?php if (isset($_POST['status']) && $_POST['status'] == "waitingDelete"){echo 'selected';}?>
			>Waiting for account deletion</option>
		</select>
		<input type="submit" name="valider" value="ok">
	</form><br>
	<table>
		<thead>
			<td>Id</td>
			<td>Username</td>
			<td>Email</td>
			<td>Status</td>
		</thead>
		<?php
			$stmt = $pdo->prepare("SELECT users.id, username, email, name FROM users JOIN status ON users.status_id = status.id WHERE username LIKE ? AND status.id = ? ORDER BY username");
			$stmt->execute([$lettreUsername . '%', $statusID]);
			while ($row = $stmt->fetch())
			{
			    echo '<tr>';
			    	echo '<td>' . $row['id'] . '</td>';
			    	echo '<td>' . $row['username'] . '</td>';
			    	echo '<td>' . $row['email'] . '</td>';
			    	echo '<td>' . $row['name'] . '</td>';
			    echo '</tr>';
			}
		?>
	 </table>

</body>
</html>
