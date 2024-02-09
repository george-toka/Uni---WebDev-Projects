<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        margin: 0;
        background-color: #f8f9fa;
    }

    .navbar {
        background-color: #003476;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
        color: #fff;
    }

    .navbar-toggler-icon {
        background-color: #fff;
    }

    .navbar-nav .nav-link {
        color: #fff;
    }

    .navbar-nav .nav-link:hover {
        color: #ffdc58;
    }

    .carousel-inner img {
        width: 100%;
        height: 400px;
        object-fit: contain;
    }

    .container {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-top: 20px;
    }

    h2 {
        color: #003476;
    }

    .btn-primary {
        background-color: #003476;
        border-color: #003476;
    }

    .btn-primary:hover {
        background-color: #001b3f;
        border-color: #001b3f;
    }

    #party {
        width: 100%;
        margin-bottom: 15px;
    }

    .form-check {
        margin-bottom: 10px;
    }

    .footer {
        background-color: #003476;
        color: #fff;
        text-align: center;
        padding: 5px;
        margin-top: auto;
    }
    #fft{
        margin-top: 14%;
        text-align: center;
        
    
    }
</style>

</head>
<body>
<script>
    // Disable the back button
    window.history.forward();
    // Enable it after a certain delay (e.g., 100 milliseconds)
    setTimeout(function() {
        window.history.forward();
    }, 100);
</script>
    <!-- Navbar Section -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">
            <img src="img/gov_gr.png" alt="Gov Logo" width="160" height="80" class="d-inline-block align-top">
        </a>
    </nav>

    <div id="fft" class="container">
        <h2><strong> Ευχαριστούμε που ψηφίσατε <?php echo htmlspecialchars($_GET['fname'] . ' ' . $_GET['lname']); ?></strong> 	&#128153; </h2>
    </div>
    
    <div class="footer">
        &copy; 2024 Online Voting System
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>