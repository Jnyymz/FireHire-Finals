<?php  
require_once 'core/models.php'; 
require_once 'core/handleForms.php'; 

if (!isset($_SESSION['username'])) {
	header("Location: login.php");
}

$getUserByID = getUserByID($pdo, $_SESSION['user_id']);

if ($getUserByID['is_hr'] == 0) {
	header("Location: ../index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Reply to Messages</title>

	<style>
		body {
			background-color: #faf4dc;
			font-family: Arial, sans-serif;
			color: #5168c4;
			margin: 0;
			padding: 0;

		}

		.navbar {
			display: flex;
			justify-content: space-between;
			align-items: center;
			background-color: #5168c4;
			padding: 10px 20px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
		}

		.navbar-content {
			display: flex;
			align-items: center;
		}

		.navbar-content img {
			height: 50px;
			margin-right: 20px;
		}

		.navbar-content h3 {
			margin: 0;
			font-size: 16px;
		}

		.navbar-content h3 a {
			color: #ffd05b;
			text-decoration: none;
			margin-right: 15px;
			font-weight: bold;
		}

		.navbar-content h3 a:hover {
			color: #9face1;
		}

		.navbar h1 {
			margin: 0;
			font-size: 18px;
			text-align: center;
			color: #ffd05b;
		}

		.username-highlight {
			color: #9face1;
			font-weight: bold;
		}

		h1 {
			text-align: center;
			font-size: 2em;
			color: #5168c4;
			margin-top: 40px;
			margin-bottom: 20px;
		}

		/* Message Container */
		.messageContainer {
			background-color: #5168c4;
			border: 1px solid #ddd;
			border-radius: 8px;
			margin: 20px auto;
			padding: 20px;
			width: 100%;
			max-width: 700px;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
		}

		/* Message Content */
		.messageContainer h2 {
			font-size: 1.6em;
			color: #ffd05b;
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
			font-size: 1.1em;
			color: #ffd05b;
		}

		/* Replies Section */
		.replyContainer {
			margin-top: 30px;
			border-top: 2px solid #ddd;
			padding-top: 20px;
		}

		.replyContainer h1 {
			font-size: 1.5em;
			color: #5168c4;
			text-align: center;
			margin-bottom: 20px;
		}

		/* Individual Reply */
		.reply {
			background-color: #f9f9f9;
			border: 1px solid #ddd;
			border-radius: 8px;
			padding: 15px;
			margin-bottom: 15px;
		}

		.reply h3 {
			font-size: 1.4em;
			color: #5168c4;
			margin: 0;
			padding-bottom: 5px;
		}

		.reply i {
			font-size: 0.9em;
			color: #9face1;
			display: block;
			margin-bottom: 10px;
		}

		.reply p {
			font-size: 1.1em;
			color: #333;
		}

		/* Edit & Delete Links */
		.editAndDelete a {
			background-color: #ffd05b;
			color: #5168c4;
			padding: 8px 15px;
			text-decoration: none;
			border-radius: 5px;
			transition: background-color 0.3s, color 0.3s;
			margin-left: 10px;
		}

		.editAndDelete a:hover {
			background-color: #9face1;
			color: #fff;
		}

		/* Reply Form */
		.replyContainer form {
			text-align: center;
		}

		.replyContainer input[type="text"] {
			width: 60%;
			padding: 10px;
			font-size: 1.1em;
			margin-right: 10px;
			border: 1px solid #ddd;
			border-radius: 5px;
			outline: none;
		}

		.replyContainer input[type="text"]:focus {
			border-color: #5168c4;
		}

		.replyContainer input[type="submit"] {
			background-color: #ffd05b;
			color: #5168c4;
			padding: 12px;
			font-size: 1.1em;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			transition: background-color 0.3s, color 0.3s;
		}

		.replyContainer input[type="submit"]:hover {
			background-color: #5168c4;
			color: #fff;
		}

		/* Responsive Layout */
		@media screen and (max-width: 600px) {
			.messageContainer {
				padding: 15px;
				width: 100%;
			}

			.replyContainer input[type="text"] {
				width: 80%;
			}

			.replyContainer input[type="submit"] {
				font-size: 1em;
			}

			.reply h3 {
				font-size: 1.2em;
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
		}

		else {
			echo "<h1 style='color: red;'>{$_SESSION['message']}</h1>";	
		}

	}
	unset($_SESSION['message']);
	unset($_SESSION['status']);
	?>

	<!-- Fetch all messages and replies -->
	<h1>All Messages</h1>
	<div class="messageContainer">
		<?php $getAllMessages = getAllMessages($pdo, $_GET['message_id']); ?>
		<h2><?php echo $getAllMessages['username']; ?></h2>
		<i><?php echo $getAllMessages['date_added']; ?></i>
		<p><?php echo $getAllMessages['description']; ?></p>
		<hr>
		<div class="replyContainer">
			<h1>All Replies</h1>
			<?php $getAllRepliesByMessages = getAllRepliesByMessage($pdo, $_GET['message_id']);  ?>
			<?php foreach ($getAllRepliesByMessages as $row) { ?>
			<div class="reply">
				<h3><?php echo $row['username']; ?></h3>
				<i><?php echo $row['date_added']; ?></i>
				<p><?php echo $row['description']; ?></p>

				<?php if ($_SESSION['username'] == $row['username']) { ?>
					<div class="editAndDelete" style="float:right;">
						<a href="editreply.php?reply_id=<?php echo $row['reply_id'] ?>">Edit</a>
						<a href="deletereply.php?reply_id=<?php echo $row['reply_id'] ?>">Delete</a>
					</div>
				<?php } ?>

			</div>	
			<?php } ?>
			<form action="index.php" method="POST" style="margin-top: 70px;">
				<p>
					<input type="text" name="reply_description" placeholder="Reply here">
					<input type="hidden" name="message_id" value="<?php echo $_GET['message_id']; ?>">
					<input type="submit" name="insertReplyBtn" value="Reply";>
				</p>
			</form>
		</div>
	</div>
</body>
</html>