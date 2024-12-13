<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>

<?php  
if (!isset($_SESSION['username'])) {
	header("Location: login.php");
}

$getUserByID = getUserByID($pdo, $_SESSION['user_id']);

if ($getUserByID['is_hr'] == 1) {
	header("Location: hr/index.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>User Profile</title>
	<link rel="stylesheet" href="style/styles.css">
	<style>
		.userInfo {
		background-color: #5168c4;
		padding: 25px;
		border-radius: 15px;
		width: 450px;
		margin: 30px auto;
		box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
		color: #fff;
		}

		.userInfo h2{
			text-align: left;
			color: #ffd05b;
			margin-bottom: 25px;
			font-size: 20px;
		}


	</style>
</head>
<body>
	<?php include 'navbar.php'; ?>

	<div class="container">
		<div class="userInfo">
			<h1>User Information</h1>
			<h3>Username: <span><?php echo $getUserByID['username']; ?></span></h3>
			<h3>First Name: <span><?php echo $getUserByID['first_name']; ?></span></h3>
			<h3>Last Name: <span><?php echo $getUserByID['last_name']; ?></span></h3>
			<h3>Date Joined: <span><?php echo $getUserByID['date_added']; ?></span></h3>
		</div>
	</div>
</body>
</html>