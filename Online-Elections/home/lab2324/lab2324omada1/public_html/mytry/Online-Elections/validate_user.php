<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<h1>orgingas</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Όνομα Χρήστη:</label>
    <input type="text" id="username" name="username" required><br>

    <label for="password">Κωδικός:</label>
    <input type="password" id="password" name="password" required><br>

    <input type="submit" value="Login">

    <?php
include('connect.php');
$username_input = $_POST['USERNAME'];
$password_input = $_POST['PASSWORD'];

// SQL query (step 3)
$sql = "SELECT * FROM VOTER WHERE USERNAME = ? AND PASSWORD = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username_input, $password_input);
$stmt->execute();
$result = $stmt->get_result();

// Validation (step 4)
if ($result->num_rows > 0) {
// User is valid, proceed with login
// Redirect to voting page
header("Location: voting_page.php");
exit(); // Ensure that no further code is executed after the redirection
} else {
// Invalid user, show error or redirect to login page
echo "Invalid credentials";
}

// Close connection (step 5)
$stmt->close();
$conn->close();

?>



</body>
</html>