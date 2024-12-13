<?php 
require_once 'core/models.php'; 
require_once 'core/dbConfig.php';
 
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
	<title>Delete Post</title>
    <link rel="stylesheet" href="style/styles.css">

	<style>
		.delete-form {
		background-color: #5168c4;
		padding: 25px;
		border-radius: 15px;
		width: 450px;
		margin: 30px auto;
		box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
		color: #fff;
		}

		.delete-form h2{
			text-align: left;
			color: #ffd05b;
			margin-bottom: 25px;
			font-size: 20px;
		}

		.delete-form input[type="submit"] {
			width: 100%;
			padding: 12px;
			background-color: #ffd05b;
			border: none;
			border-radius: 8px;
			font-size: 17px;
			color: #5168c4;
			font-weight: bold;
			cursor: pointer;
		}

		.delete-form input[type="submit"]:hover {
			background-color: #9face1;
			color: #fff;
			box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
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

		<?php $getPostByID = getPostByID($pdo, $_GET['post_id']); ?>
		<form  class="delete-form" action="core/handleForms.php?post_id=<?php echo $_GET['post_id']; ?>" method="POST">
			<h1>Are you sure you want to delete this job post?</h1>
			<h2>Job Title: <?php echo $getPostByID['job_title']; ?></h2>
			<h2>Company: <?php echo $getPostByID['company']; ?></h2>
			<h2>Job Description: <?php echo $getPostByID['description']; ?></h2>
			<input type="submit" name="deletePostBtn" value="Delete">
		</form>			
	
</body>
</html>