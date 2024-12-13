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

$message_id = $_GET['message_id'] ?? null;
$post_id = $_GET['post_id'] ?? null;
$application_id = $_GET['application_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Replies</title>
    <link rel="stylesheet" href="style/messtyles.css">
    <style>
      

        /* Main Heading */
        h1 {
            color: #5168c4;
            text-align: center;
            font-size: 2.5em;
            margin: 20px 0;
        }

        /* Links */
        a {
            color: #5168c4;
            text-decoration: none;
        }

        a:hover {
            color: #ffd05b;
        }

        /* Container for Post and Replies */
        .postContainer {
            background-color: #5168c4;
            border: 2px solid black;
            border-radius: 10px;
            padding: 25px;
            width: 60%;
            margin: 20px auto;
        }

        .postContainer h2 {
            color: white;
            font-size: 1.8em;
            margin-bottom: 15px;
        }

        /* Message Styling */
        .message {
            margin-top: 20px;
            background-color: #5168c4;
            border: 1px solid black;
            border-radius: 8px;
            padding: 20px;
        }

        .message h3 {
            font-size: 1.4em;
            color: white;
            margin-bottom: 10px;
        }

        .message h3 span {
            color: red; /* Highlight 'HR' text */
        }

        .message i {
            font-style: italic;
            color: #ffd05b;
            margin-bottom: 10px;
        }

        .message p {
            color: white;
            font-size: 1.1em;
        }

        /* Button Link Styling */
        p a {
            background-color: #ffd05b;
            color: #5168c4;
            padding: 10px 15px;
            text-align: center;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
            font-size: 1.1em;
            transition: background-color 0.3s, color 0.3s;
        }

        p a:hover {
            background-color: #5168c4;
            color: white;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .postContainer {
                width: 90%;
            }

            h1 {
                font-size: 2em;
            }

            .message {
                padding: 15px;
            }
        }

    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h1 style="text-align:center;">All Replies</h1>
    <p>Click here to <a href="sendAMessage.php?post_id=<?php echo $_GET['post_id']; ?>&application_id=<?php echo $_GET['application_id']; ?>">send another message</a></p>

    <!-- Fetch all replies -->
    <div class="postContainer" style="background-color: #5168c4; border-style: solid; border-color: black;width: 60%; padding: 25px; margin: 0 auto;">
        <?php 
        $getAllMessages = getMessagesForPost($pdo, $_SESSION['user_id'], $post_id); 
        
        foreach ($getAllMessages as $message) {
            if ($message['message_id'] == $message_id) {  
        ?>
        <h2 style="color: white;">Replies</h2>
        <?php 
            $getAllRepliesByMessage = getAllRepliesByMessage($pdo, $message['message_id'], $post_id, $application_id);
            
            foreach ($getAllRepliesByMessage as $row) {
        ?>
        <div class="message" style="margin-top: 20px;">
            <div class="messageContainer" >
                <h3><?php echo $row['username'];?><span style="color:red;"> (HR)</span></h3>
                <i><?php echo $row['date_added']; ?></i>
                <p><?php echo $row['description']; ?></p>
            </div>
        </div>
        <?php }  ?>
        <?php } 
        }  ?>
    </div>
</body>
</html>