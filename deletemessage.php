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
	<title>Delete a Message</title>
	<link rel="stylesheet" href="style/messtyles.css">

	<style>
		.delete {
			background-color:  #5168c4;
			border: 1px solid #ddd;
			border-radius: 8px;
			width: 100%;
			max-width: 500px;
			margin: 40px auto;
			padding: 20px;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
		}

		/* Title */
		.delete h2 {
			color: #ffd05b;
			text-align: center;
			font-size: 1.5em;
			margin-bottom: 15px;
		}

		/* Message Preview */
		.delete h3 {
			color: white;
			font-size: 1.2em;
			margin-bottom: 20px;
		}

		/* Input Fields */
		.delete input[type="hidden"] {
			display: none;
		}

		/* Submit Button */
		.delete input[type="submit"] {
			background-color: #ffd05b;
			color: #5168c4;
			padding: 12px;
			font-size: 1.1em;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			transition: background-color 0.3s, color 0.3s;
		}

		.delete input[type="submit"]:hover {
			background-color: #9face1;
			color: #fff;
		}

		/* Responsive Layout */
		@media screen and (max-width: 600px) {
			.delete {
				padding: 15px;
			}

			.delete input[type="submit"] {
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

	<div class="delete">
		<form core="delete-form" action="core/handleForms.php" method="POST">
			<h2 style="color: #ffd05b;" >Are you sure you want to delete this?</h2>
			<?php $getMessageByID = getMessageByID($pdo, $_GET['message_id']); ?>
			<h3>Message: <?php echo $getMessageByID['description']; ?></h3>
		    <input type="hidden" name="message_id" value="<?php echo htmlspecialchars($getMessageByID['message_id']); ?>">
		    <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($_GET['post_id'] ?? ''); ?>">
		    <input type="hidden" name="application_id" value="<?php echo htmlspecialchars($_GET['application_id'] ?? ''); ?>">
		    <p>
		        <input type="submit" name="deleteMessageBtn" style="width: 100%;" value="Delete">
		    </p>
		</form>
	</div>
</body>
</html>