<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>
<?php require_once 'core/handleForms.php'; ?>

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
	<title>Messages</title>
	<link rel="stylesheet" href="style/messtyles.css">
	<style>
		h1 {
		text-align: center;
		color: #5168c4;
		padding: 20px 0;
		font-size: 2em;
	}

		/* Message Container */
		.message {
			background-color: #ffffff;
			border: 1px solid #ddd;
			border-radius: 8px;
			margin: 20px 30px;
			padding: 20px;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
		}

		/* Message Content */
		.messageContainer h2 {
			font-size: 1.5em;
			color: #5168c4;
			margin: 0;
			padding-bottom: 5px;
		}

		.messageContainer i {
			font-size: 0.9em;
			color: #9face1;
			display: block;
			margin-bottom: 10px;
		}

		.messageContainer p {
			font-size: 1em;
			color: #333;
		}

		/* Buttons */
		.buttons {
			text-align: right;
			margin-top: 15px;
		}

		.buttons a {
			background-color: #ffd05b;
			color: #5168c4;
			padding: 8px 15px;
			text-decoration: none;
			border-radius: 5px;
			margin-left: 10px;
			transition: background-color 0.3s, color 0.3s;
		}

		.buttons a:hover {
			background-color: #5168c4;
			color: #fff;
		}

		/* Ensure responsive layout for smaller screens */
		@media screen and (max-width: 600px) {
			.messageContainer {
				padding: 15px;
			}

			.buttons a {
				padding: 6px 12px;
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
    
	<h1 style="text-align:center;">All Messages</h1>
	
	<!-- Fetch all messages -->
	<?php $getAllMessages = getMessagesForPost($pdo, $_SESSION['user_id'],$_GET['post_id']);?>
	<?php foreach ($getAllMessages as $row) { ?>
	<div class="message">
		<div class="messageContainer">
			<h2><?php echo $row['username']; ?></h2>
			<i><?php echo $row['date_added']; ?></i>
			<p><?php echo $row['description']; ?></p>
			
			<div class="buttons" style="float:right;">
				<a href="seeAllReplies.php?message_id=<?php echo $row['message_id']; ?>&post_id=<?php echo $_GET['post_id']; ?>&application_id=<?php echo $_GET['application_id']; ?>">See All Replies</a>

				<?php if ($_SESSION['username'] == $row['username']) { ?>
				<a href="editmessage.php?message_id=<?php echo $row['message_id']; ?>&post_id=<?php echo $_GET['post_id']; ?>&application_id=<?php echo $_GET['application_id']; ?>">Edit Message</a>
				<a href="deletemessage.php?message_id=<?php echo $row['message_id']; ?>&post_id=<?php echo $_GET['post_id']; ?>&application_id=<?php echo $_GET['application_id']; ?>">Delete Message</a>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php } ?>

</body>
</html>