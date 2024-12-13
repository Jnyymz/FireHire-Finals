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

$post_id = $_GET['post_id'] ?? null;
$application_id = $_GET['application_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit a Message</title>
	<link rel="stylesheet" href="style/messtyles.css">
	<style>
		h1 {
			text-align: center;
			font-size: 2em;
			color: #5168c4;
			margin-top: 40px;
			margin-bottom: 20px;
		}

		/* Form Container */
		.formContainer {
			display: flex;
			justify-content: center;
			width: 100%;
			max-width: 600px;
			margin: 0 auto;
		}

		/* Form */
		.editmes-form {
			background-color: #5168c4;
			padding: 20px;
			border-radius: 8px;
			width: 100%;
			max-width: 480px;
			display: flex;
			flex-direction: column;
		}

		.editmes-form label {
			font-size: 1.1em;
			color: #333;
			margin-bottom: 5px;
		}

		.editmes-form input[type="text"] {
			padding: 10px;
			font-size: 1em;
			margin-bottom: 15px;
			border: 1px solid #ddd;
			border-radius: 5px;
			outline: none;
			width: 95%;
		}

		.editmes-form input[type="text"]:focus {
			border-color: #5168c4;
		}

		.editmes-form input[type="submit"] {
			background-color: #ffd05b;
			color: #5168c4;
			padding: 12px;
			font-size: 1.1em;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			transition: background-color 0.3s, color 0.3s;
		}

		.editmes-form input[type="submit"]:hover {
			background-color: #9face1;
			color: #fff;
		}

		/* Responsive Layout */
		@media screen and (max-width: 600px) {
			.formContainer {
				padding: 15px;
			}

			.editmes-form input[type="submit"] {
				font-size: 1em;
			}
		}
	</style>
</head>
<body>
	<?php include 'navbar.php'; ?>

    <?php  
    if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
        if ($_SESSION['status'] == "200") {
            echo "<h1 style='color: green;'>{$_SESSION['message']}</h1>";
        } else {
            echo "<h1 style='color: red;'>{$_SESSION['message']}</h1>";    
        }
    }
    unset($_SESSION['message']);
    unset($_SESSION['status']);
    ?>
	
	<h1 style="text-align:center;">Edit your message ⭑.ᐟ</h1>
	<div class="formContainer" style="display: flex; justify-content: center; width: 480px;">
		<?php $getMessageByID = getMessageByID($pdo, $_GET['message_id']); ?>
		<form class="editmes-form" action="core/handleForms.php" method="POST">
		    <input type="hidden" name="message_id" value="<?php echo htmlspecialchars($_GET['message_id']); ?>"> 
		    <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post_id); ?>">
		    <input type="hidden" name="application_id" value="<?php echo htmlspecialchars($application_id); ?>">
		    <label for="message_description">Content</label>
		    <input type="text" name="message_description" value="<?php echo htmlspecialchars($getMessageByID['description']); ?>">
		    <input type="submit" name="editMessageBtn" value="Save">
		</form>
	</div>
	
</body>
</html>