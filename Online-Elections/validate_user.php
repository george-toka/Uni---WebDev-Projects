<?php
include('connect.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
 
    $username_input = $_POST['username'];
    $password_input = $_POST['password'];

    // SQL query
    $sql = "SELECT ID, FNAME, LNAME, hasVoted FROM VOTER WHERE USERNAME = ? AND PASSWORD = ?";
    
    // Prepare statement
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error in query: " . $conn->error);
    }

    // Bind parameters
    $bindResult = $stmt->bind_param("ss", $username_input, $password_input);
    if (!$bindResult) {
        die("Error binding parameters: " . $stmt->error);
    }

    // Execute statement
    $executeResult = $stmt->execute();
    if (!$executeResult) {
        die("Error in query execution: " . $stmt->error);
    }

    // Get result
    $result = $stmt->get_result();
    if (!$result) {
        die("Error getting result: " . $stmt->error);
    }
    
    // Validation
    if ($row = $result->fetch_assoc()) {
        // Check the hasVoted value
        if ($row['hasVoted'] == 1) {
            // User has voted, redirect to thanks4voting.php
            header("Location: thanks4voting.php?fname=" . urlencode($row['FNAME']) . "&lname=" . urlencode($row['LNAME']));
            exit(); // Ensure that no further code is executed after the redirection
        } else {
            // User has not voted, proceed with login
            // Redirect to voting page
            // parse ID to the voting page
            $voterID = $row['ID'];
            header("Location: voting_page.php?voterID=" . urlencode($voterID));
            exit(); // Ensure that no further code is executed after the redirection
        }
    } else {
        // Invalid user, show error
        $error_message = "Invalid credentials";
    }
    
    
    // Close result set
    $result->close();
    
    // Close statement
    $stmt->close();

}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting System Login Page</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
   
    <style>
        body {
            background-color: #003476;
        }

        .container-small-center {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .container {
            width: 500px;
            height: auto;
            border-radius: 3%;
            background-color: white;
        }

        img {
            width: 80%;
            border-radius: 5%;
            margin-left: 5%;
        }

        .fu {
            margin-top: 20%;
            margin-left: 20%;
        }

        .form-control {
            width: 72%;
            margin-left: 14%;
            margin-bottom: 10px;
        }

        .sign-in-button {
            margin-top: 30px;
        }

        h3 {
            text-align: center;
            margin-bottom: 30px;
        }

        p {
            position: relative;
            left: 12%;
            bottom: 4%; 
        }
    </style>
</head>
<body>
    <div class="container-small-center">
        <div class="container border border-dark py-2">
            <div class="row">
                <div class="col"><img src="img/gov_gr.png" alt="gov site" class="fu"></div>
                <div class="col"><img src="img/ballot_flat.jpg" alt="ballot"></div>
            </div>
            <div class="row">
                <h3 class="fs-2 text-body-emphasis">Login</h3>
            </div> 
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"> 
            <?php if (isset($error_message)): ?>
                     <div class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
                     </div>
             <?php endif; ?>
                <div class="row">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control" aria-label="Username" required>
                </div>
                <div class="row">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" aria-label="Password">
                </div>
                <div class="row sign-in-button">
                    <div class="d-flex justify-content-center">
                        <input type="submit" value="Login" class="btn btn-primary w-50">
                    </div>
                </div>
            </form>
            <p class="mt-5 mb-3 text-body-secondary" style="margin-left:25%;">© Ntolmadakia</p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
