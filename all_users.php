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

	// traitement du formulaire re recherche
	$statusID = 2;
	$lettreUsername = '';

	if (isset($_GET['lettre']) && isset($_GET['status'])) {
		$lettreUsername = htmlspecialchars($_GET['lettre']);
		if ($_GET['status'] == "active") {
			$statusID = 2;
		} else if ($_GET['status'] == "waiting") {
			$statusID = 1;
		} else if ($_GET['status'] == "waitingDelete") {
			$statusID = 3;
		}
	}

	// traitement du formulaire de supression
	if (isset($_GET['status_id']) && isset($_GET['user_id']) && isset($_GET['action']) && $_GET['action'] == "askDeletion") {
		try {
		 $pdo->beginTransaction();
			 // Ajout au log
	 		$datetime = date("Y-m-d H:i:s");
	 		$stmt = $pdo->prepare("INSERT INTO action_log (action_date, action_name, user_id) values (?, ?, ?)");
	 		$stmt->execute([ $datetime, $_GET['action'], $_GET['user_id']]);

			//throw new \Exception("Error Processing Request", 1);

			// modification du status
			$stmt = $pdo->prepare("UPDATE users SET status_id = ? WHERE id = ?");
			$stmt->execute([ 3, $_GET['user_id']]);

			$pdo->commit();
		}catch (Exception $e){
			$pdo->rollBack();
		 	throw $e;
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
	<!-- Formulaire de recherche -->
	<form action="all_users.php" method="GET">
		<label for="letter">Start with letter: </label>
		<input type="text" name="lettre" value="<?php echo $lettreUsername; ?>">
		<label for="status"> and status : </label>
		<select name="status">
			<option value="active"
				<?php if (isset($_GET['status']) && $_GET['status'] == "active"){echo 'selected';}?>
				>Active account</option>
			<option value="waiting"
					<?php if (isset($_GET['status']) && $_GET['status'] == "waiting"){echo 'selected';}?>
			>Waiting for account validation</option>

			<option value="waitingDelete"
					<?php if (isset($_GET['status']) && $_GET['status'] == "waitingDelete"){echo 'selected';}?>
			>Waiting for account deletion</option>
		</select>
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
			$stmt = $pdo->prepare("SELECT users.id, username, email, name, status.id as idStatus FROM users JOIN status ON users.status_id = status.id WHERE username LIKE ? AND status.id = ? ORDER BY username");
			$stmt->execute([$lettreUsername . '%', $statusID]);
			while ($row = $stmt->fetch())
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
