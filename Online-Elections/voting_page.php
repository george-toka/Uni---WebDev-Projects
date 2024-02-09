<?php

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include('connect.php');

// Retrieve the voter ID from the URL parameter
if (isset($_GET['voterID'])) {
    $voterID = $_GET['voterID'];
} else {
    // Handle the case where the URL parameter is not set
    echo "Voter ID not found in the URL.";
}

// Get Voter's Province first
$query = "SELECT EP_NAME, hasVoted FROM VOTER WHERE ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $voterID);
$stmt->execute();
$stmt->bind_result($temp, $flag);
// Fetch the results
while ($stmt->fetch()) {
    // Store values in an array, object, or any desired structure
    $voterProvince = $temp;
    $hasVoted = $flag;
}
$stmt->close();

// Fetch the maximum crosses allowed for the voter's electoral province
$query = "SELECT max_crosses FROM ELECTORAL_PROVINCE WHERE name = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $voterProvince);
$stmt->execute();
$stmt->bind_result($maxCrosses);
$stmt->fetch();
$stmt->close();

if ($hasVoted) {
    // Redirect to thanks4voting.php with the voter's name
    header("Location: thanks4voting.php?fname=" . urlencode($voterFName) . "&lname=" . urlencode($voterLName));
    exit();
}

// Fetch Names of Parties
$query = "SELECT PARTY_NAME FROM POLITICALPARTY";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    $party_names = array();
    $i = -1;
    while ($row = $result->fetch_assoc()) {
        $i += 1;
        $party_names[$i] = $row['PARTY_NAME'];
    }
}

// Fetch Candidates from the selected Party that are running in the voter's province
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['party'])) {
    // Check if the 'party' key is set in the POST data
    if (isset($_POST['party'])) {
        // Get the selected party value
        $selectedParty = $_POST['party'];
    } else {
        // Handle the case where the 'party' key is not set
        echo "No party selected.";
    }

    // Run query to select candidates
    $query = "SELECT FNAME , LNAME, C_ID FROM CANDIDATE WHERE P_NAME = ? and EP_NAME = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $selectedParty, $voterProvince);
    $stmt->execute();
    $stmt->bind_result($c_fname, $c_lname, $c_id);

    $candidates_info = [];
    // Fetch the results
    while ($stmt->fetch()) {
        // Store values in an array, object, or any desired structure
        $candidates_info[] = [
            'FNAME' => $c_fname,
            'LNAME' => $c_lname,
            'C_ID' => $c_id
        ];
    }
    $stmt->close();
}

// Check if the form is submitted and process the votes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['candidates']) && !$hasVoted) {
    // Check the number of selected candidates
    $selectedCandidatesCount = count($_POST['candidates']);

    // Ensure the voter does not exceed the maximum crosses allowed
    if ($selectedCandidatesCount > $maxCrosses) {
        // Display an error message
        echo '<div class="alert alert-danger" role="alert">Δεν μπορείτε να υπερβείτε τους μέγιστους επιτρεπόμενους σταυρούς για την εκλογική σας επαρχία.</div>';
    } else {
        // Continue processing the votes
        // Retrieve the voter's information first
        $query = "SELECT FNAME, LNAME FROM VOTER WHERE ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $voterID);
        $stmt->execute();
        $stmt->bind_result($voter_fname, $voter_lname);

        // Fetch the results
        while ($stmt->fetch()) {
            // Store values in variables
            $voterFName = $voter_fname;
            $voterLName = $voter_lname;
        }
        $stmt->close();

        // Update hasVoted status
        $hasVoted = 1;
        $updateVoterQuery = "UPDATE VOTER SET hasVoted = ? WHERE ID = ?";
        $stmt = $conn->prepare($updateVoterQuery);
        $stmt->bind_param("is", $hasVoted, $voterID);
        $stmt->execute();
        $stmt->close();

        foreach ($_POST['candidates'] as $nth_candidate) {
            $temp_id = $nth_candidate;
            $updateCandidateQuery = "UPDATE CANDIDATE SET VOTE_COUNT = VOTE_COUNT + 1 WHERE C_ID = ?";
            $stmt = $conn->prepare($updateCandidateQuery);
            $stmt->bind_param("s", $temp_id);
            $stmt->execute();
            $stmt->close();
        }

        // Redirect to thanks4voting.php with the voter's name
        header("Location: thanks4voting.php?fname=" . urlencode($voterFName) . "&lname=" . urlencode($voterLName));
        exit();
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
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
        .carousel-control-prev,
        .carousel-control-next {
    color: black;
}
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
    filter: invert(100%);
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
        #province{
            font-size: 20px;
        }
 
        .footer {
            position: fixed;
            width: 100%;
            background-color: #003476;
            color: #fff;
            padding: 5px;
            margin-top:10px;
            text-align: center;
            bottom: 0px;
        }
        .alert-danger{
            padding-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Navbar Section -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">
            <img src="img/gov_gr.png" alt="Gov Logo" width="160" height="80" class="d-inline-block align-top">
        </a>

        <div class="dropdown ml-auto">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Parties
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#" data-party="party1">K.O.T.E.S</a>
                <a class="dropdown-item" href="#" data-party="party2">P.A.O.K</a>
                <a class="dropdown-item" href="#" data-party="party3">PSYRIZA</a>
                <a class="dropdown-item" href="#" data-party="party4">P.P.O.G</a>
                <a class="dropdown-item" href="#" data-party="party5">PAN.AXIA</a>
            </div>
        </div>
    </nav>

    <!-- Carousel Section -->
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active contain">
                <img src="img/paok.jpg" class="d-block w-100" alt="Carousel Image 1">
            </div>
            <div class="carousel-item contain">
                <img src="img/pao.jpg" class="d-block w-100" alt="Carousel Image 2">
            </div>
            <div class="carousel-item fit">
                <img src="img/KOTES-KOMMA.jpg" class="d-block w-100" alt="Carousel Image 3">
            </div>
            <div class="carousel-item fit">
                <img src="img/pirates.jpg" class="d-block w-100" alt="Carousel Image 4">
            </div>
            <div class="carousel-item fit">
                <img src="img/psyriza.png" class="d-block w-100" alt="Carousel Image 5">
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

        
    <!--Vote Section-->
    <div class="container mt-5">
        <h2 class="mb-4">Online Voting System</h2>
        <h3 class="mb-2" id="province"> <?php echo "Province: $voterProvince";?> </h3>
        <form action="" method="post" id="votingForm">
            <div class="form-group">
                <label for="party">Select Political Party:</label>
                <select class="form-control" id="party" name="party">
                    <option value="">Select Party</option>
                    <?php
                    // Assuming $partyNames is an array of party names
                    foreach ($party_names as $party) {
                        // Check if the party is selected
                        $selected = (isset($_POST['party']) && $_POST['party'] == $party) ? 'selected' : '';
                        echo '<option value="' . $party . '" ' . $selected . '>' . $party . '</option>';
                    }
                    ?>
                </select>
                <button type="submit" class="btn btn-outline-dark">Confirm</button>
            </div>
        </form>

        
        <?php
        // Display checkboxes for candidates
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['party']) && !empty($candidates_info)) {
            echo '<form action="" method="post">'; 
            echo '<div class="form-group">';
            echo '<label>Select Candidates:</label><br>';

            foreach ($candidates_info as $candidate) {
                $candidateID = $candidate['C_ID'];
                $candidateName = $candidate['FNAME'] . ' ' . $candidate['LNAME'];

                echo '<div class="form-check">';
                echo '<input type="checkbox" class="form-check-input" name="candidates[]" value="' . $candidateID . '">';
                echo '<label class="form-check-label">' . $candidateName . '</label>';
                echo '</div>';
            }
            if(!$hasVoted){
                echo '<button type="submit" class="btn btn-primary mt-3">Vote</button>';
                echo '</div>';
                echo '</form>';
            }
        }
        
        ?>


    </div>                    
    
        <div class="footer">
            &copy; 2024 Online Voting System
        </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html> 