<?php
    
    // Set the level of error reporting
    error_reporting(E_ALL);

    // Display errors on the screen
    ini_set('display_errors', 1);
    include("connect.php");
    // Fetch Party Results
    $query = "SELECT PARTY_NAME, ELECTORATE_SEATS, PERCENTAGES FROM ELECTION_RESULTS";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $stmt->bind_result($names, $seats, $t_percentages);

    $party_results = [];
    while($stmt->fetch()){
        $party_results[] = [
            'PARTY_NAME' => $names,
            'ELECTORATE_SEATS' => $seats,
            'PERCENTAGES' => $t_percentages
        ];
    }
    $stmt->close();

    // Fetch Province Statistics
    $query = "SELECT `name`,vote_count, abs_ratio FROM ELECTORAL_PROVINCE";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $stmt->bind_result($pnames,$votes, $ratio);

    $province_stats = [];
    while($stmt->fetch()){
        $province_stats[] = [
            'name' => $pnames,
            'ABSTENTION_RATIO' => $ratio,
            'Vote Count' => $votes
        ];
    }
    $stmt->close();

     // Total Abstention Ratio% - echo only OK
     $query = "SELECT COUNT(ID) AS VOTERS FROM VOTER";
     $stmt = $conn->prepare($query);
     $stmt->execute();
     $stmt->bind_result($vtrs);
     $stmt->fetch();
     $all_voters = $vtrs;
     $stmt->close();
 
     $voters = 0;
     foreach ($province_stats as $province) {
         $voters += $province['Vote Count'];
     }
 
     $total_AR = (1 - ($voters / $all_voters)) * 100;
     $conn->close();
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Voting Results</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <link rel="stylesheet" href="../Online-Elections/css/final.css">

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

        #box{
            height:70vh;
        }
        .wrapped{
            display: inline-flex;
            flex-wrap: wrap;
            float:left;
            width:36%;
        }
        h2{
            position:absolute;
            margin: 0px;
            padding: 0px;
        }

        .result-container{
            display: grid;
            flex-wrap:wrap;
            width: 40%;
            border-radius: 5%;
            position: relative;
            top: 11%;
            grid-template-columns: 1fr;
            height:auto;
        }
        @media (max-width: 1000px) {
            #box {
                height: auto;
            }

            .wrapped {
                width: 100%;
            }
            
            .result-container{
                text-align: center;
                left: 13%;
                width: 80%;
            }
        }


</style>

</head>
<body>

    <!-- Navbar Section -->
    <nav class="navbar navbar-expand-lg navbar-light">
        
            <img src="../Online-Elections/img/gov_gr.png" alt="Gov Logo" width="160" height="80" class="d-inline-block align-top">
        
    </nav>
    
    <div class="container my-5" id="box"> 
        <img src="/img/greece_map.jpg" draggable="false" class="wrapped"/>
        <h2> Results </h2>

<section>
  <h1>Party Percentages & Seats </h1>

  <?php if (!empty($party_results)): ?>
    <?php foreach ($party_results as $party): ?>
      <div class="poll-option">
        <span class="poll-option__label"><?php echo $party['PARTY_NAME']; ?></span>
        <table class="poll-option__result">
          <tr>
            <td><?php echo $party['ELECTORATE_SEATS']; ?></td>
            <td>
              <span></span>
              <span style='width: <?php echo $party['PERCENTAGES'] . '%'; ?>'></span>
            </td>
            <td><?php echo round($party['PERCENTAGES'],2) . '%'; ?></td>
          </tr>
        </table>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No party results available.</p>
  <?php endif; ?>

</section>

<section>
<h1>Abstention Ratio </h1>

<?php if (!empty($province_stats)): ?>
    <?php foreach ($province_stats as $province): ?>
      <div class="poll-option">
        <span class="poll-option__label"><?php echo $province['name']; ?></span>
        <table class="poll-option__result">
          <tr>
            <td><?php echo $province['Vote Count']; ?></td>
            <td>
              <span></span>
              <span style='width: <?php echo $province['ABSTENTION_RATIO'] . '%'; ?>'></span>
            </td>
            <td><?php echo round($province['ABSTENTION_RATIO'],2) . '%'; ?></td>
          </tr>
        </table>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No province results available.</p>
  <?php endif; ?>

  <div class="poll-option" id="third_sec">
    <h3>TOTAL</h3>
    <table class="poll-option__result">
      <tr>
        <td></td>
        <td>
          <span></span>
          <span style='width: <?php echo round($total_AR,2) . '%'; ?>'></span>
        </td>
        <td><?php echo round($total_AR,2) . '%'; ?></td>
      </tr>
    </table>
  </div>

</section>
    </div>

    
    
    <div class="footer">
        &copy; 2024 Online Voting System
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</body>
</html>