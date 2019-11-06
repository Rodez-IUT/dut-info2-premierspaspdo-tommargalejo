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

 ?>

<!DOCTYPE html>
<html>
<head>
	<title>All Users</title>
</head>
<body>
	<h1>Tous les utilisateurs</h1>
	<table>
		<thead>
			<td>Id</td>
			<td>Username</td>
			<td>Email</td>
			<td>Status</td>
		</thead>
		<?php 
			$stmt = $pdo->query('SELECT * FROM users JOIN status ON users.status_id = status.id ORDER BY username');
			while ($row = $stmt->fetch())
			{
			    //echo $row['email'] . "\n";
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