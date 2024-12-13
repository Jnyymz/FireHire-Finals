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

$post_id = $_GET['post_id'] ?? null;
$application_id = $_GET['application_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Messages</title>
    <style>
    
        /* General Styles */
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

        /* Reply Button */
        .messageContainer a {
            background-color: #ffd05b;
            color: #5168c4;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .messageContainer a:hover {
            background-color: #5168c4;
            color: #fff;
        }

        /* Ensure responsive layout for smaller screens */
        @media screen and (max-width: 600px) {
            .messageContainer {
                padding: 15px;
            }

            .messageContainer a {
                padding: 6px 12px;
            }
        }


    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <h1 style="text-align: center;">Messages from the applicant</h1>

    <!-- Fetch messages related to the specific application -->
    <?php
    if ($application_id) {
        $getMessagesForApplicant = getMessagesForApplication($pdo, $application_id);
        foreach ($getMessagesForApplicant as $message) {
    ?>
        <div class="message">
            <div class="messageContainer">
                <h2><?php echo htmlspecialchars($message['username']); ?></h2>
                <i><?php echo htmlspecialchars($message['date_added']); ?></i>
                <p><?php echo htmlspecialchars($message['description']); ?></p>
                <a href="replyToMessage.php?message_id=<?php echo $message['message_id']; ?>" style="float: right;">Reply</a>
            </div>
        </div>

    <?php
        }
    } else {
        echo "<p>No messages found for this applicant.</p>";
    }
    ?>

</body>
</html>