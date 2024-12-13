<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';
require_once 'core/handleForms.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$getUserByID = getUserByID($pdo, $_SESSION['user_id']);

if ($getUserByID['is_hr'] == 1) {
    header("Location: hr/index.php");
    exit();
}


$post_id = isset($_GET['post_id']) ? $_GET['post_id'] : null;
if (!$post_id) {
    echo "Post ID is missing!";
    exit();
}

$application_id = $_GET['application_id'] ?? null;  // Retrieve application_id from the URL
if (!$application_id) {
    echo "Application ID is missing!";
    exit();
}

$application = getApplicationById($pdo, $post_id, $_SESSION['user_id']);
$job = getPostByID($pdo, $post_id);

if (!$application) {
    echo "Application not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Your Application</title>
    <link rel="stylesheet" href="style/applystyles.css">
    <style>
        .application-review {
            background-color: #5168c4;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            padding: 20px;
            width: 100%;
            max-width: 800px;
        }

        /* Title Styling */
        .application-review h1 {
            text-align: center;
            font-size: 1.8em;
            color: #ffd05b;
        }

        /* Application Detail Box */
        .application-detail-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin: 20px 0;
            padding: 15px;
        }

        .application-detail-box h3 {
            font-size: 1.4em;
            color: #5168c4;
            margin-bottom: 10px;
        }

        .application-detail-box p {
            font-size: 1.1em;
            color: #333;
        }

        /* Application Status Styling */
        .application-status-message {
            background-color: #e1f7d5;
            border: 1px solid #c0e3a1;
            color: #3a723a;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }

        .application-status-message p {
            font-size: 1.2em;
        }

        /* Form Styling */
        .application-update-form,
        .application-withdraw-form {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }

        .application-update-form h3,
        .application-withdraw-form h3 {
            font-size: 1.4em;
            color: #5168c4;
            margin-bottom: 15px;
        }

        .application-update-form label,
        .application-withdraw-form label {
            font-size: 1.1em;
            margin-bottom: 5px;
            display: block;
        }

        .application-update-form textarea,
        .application-withdraw-form input[type="file"] {
            width: 100%;
            padding: 10px;
            font-size: 1.1em;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .application-update-form textarea {
            height: 120px;
        }

        .application-update-form input[type="submit"],
        .application-withdraw-form input[type="submit"] {
            background-color: #ffd05b;
            color: #5168c4;
            padding: 10px 15px;
            font-size: 1.1em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
        }

        .application-update-form input[type="submit"]:hover,
        .application-withdraw-form input[type="submit"]:hover {
            background-color: #5168c4;
            color: #fff;
        }

        /* Media Query for Mobile Devices */
        @media screen and (max-width: 600px) {
            .application-review {
                width: 95%;
                margin: 10px;
            }

            .application-update-form,
            .application-withdraw-form {
                padding: 15px;
            }

            .application-update-form textarea {
                height: 100px;
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

    <div class="application-review">
        <h1>View your application for <?php echo htmlspecialchars($job['job_title']); ?> ⭑.ᐟ</h1>

        <!-- Application details -->
        <div class="application-detail-box">
            <h3>Reason for Application</h3>
            <p><?php echo !empty($application['reason']) ? htmlspecialchars($application['reason']) : 'N/A'; ?></p>
        </div>

        <div class="application-detail-box">
            <h3>Resume</h3>
            <p>
                <?php 
                $pdfPath = $application['pdf_path'];
                if (!empty($pdfPath)) {
                    if (strpos($pdfPath, 'uploads/') === 0) {
                        $pdfPath = substr($pdfPath, strlen('uploads/'));
                    }
                    echo '<a href="uploads/' . htmlspecialchars($pdfPath) . '" target="_blank">View Document</a>';
                } else {
                    echo 'No document submitted.';
                }
                ?>
            </p>
        </div>

        <div class="application-detail-box">
            <h3>Application Status</h3>
            <p><?php echo ucfirst(htmlspecialchars($application['status'])); ?></p>
        </div>
    </div>

    <div class="application-review">
        <?php if ($application['status'] == 'pending'): ?>

            <!-- Form for updating application -->
            <div class="application-update-form">
                <h3>Update your application</h3>
                <form action="viewApplication.php?post_id=<?php echo $post_id; ?>&application_id=<?php echo $application['application_id']; ?>" method="POST" enctype="multipart/form-data">
                    <label for="reason">Why are you the best fit for this position?</label><br>
                    <textarea name="reason" rows="4" cols="50"><?php echo htmlspecialchars($application['reason']); ?></textarea><br><br>

                    <label for="pdf">Upload your resume (PDF only):</label><br>
                    <input type="file" name="pdf" id="pdf"><br><br>

                    <input type="submit" name="updateApplicationBtn" value="Update Application">
                </form>
            </div>

            <!-- Form for withdrawing the application -->
            <div class="application-withdraw-form">
                <form action="viewApplication.php?post_id=<?php echo $post_id; ?>&application_id=<?php echo $application['application_id']; ?>" method="POST">
                    <input type="submit" name="withdrawApplicationBtn" value="Withdraw Application">
                </form>
            </div>

        <!-- Display acceptance and rejection message -->
        <?php elseif ($application['status'] == 'accepted'): ?>
            <div class="application-status-message">
                <p>Congratulations! You have been accepted for this position.</p>
            </div>
        <?php elseif ($application['status'] == 'rejected'): ?>
            <div class="application-status-message">
                <p>Sorry, you have not been accepted for this position.</p>
            </div>
        <?php endif; ?>
        </div>
</body>
</html>