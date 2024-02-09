<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//One-time procedure to store
include('connect.php');

$query = "SELECT PARTY_NAME, SUM(VOTE_COUNT) AS VOTES FROM POLITICALPARTY, CANDIDATE WHERE PARTY_NAME=P_NAME GROUP BY PARTY_NAME";
$stmt = $conn->prepare($query);
$stmt->execute();
$stmt->bind_result($names,$votes); 

$party_names = [];
$votes_pp = [];
// Fetch Votes Per Party & Party Names OK
while($stmt->fetch()){
    $party_names[] = $names;
    $votes_pp[] = $votes;
}
$stmt->close();

// Calculate Percentages (we need the max_number of votes first)
$query = "SELECT COUNT(ID) AS VOTERS, max_crosses FROM VOTER,ELECTORAL_PROVINCE WHERE EP_NAME = `name` GROUP BY EP_NAME";
$stmt = $conn->prepare($query);
$stmt->execute();
$stmt->bind_result($voters, $crosses); 

$voters_pp = [];
$max_crosses = [];
while($stmt->fetch()){
    $voters_pp[] = $voters;
    $max_crosses[] = $crosses;
}

$stmt->close();

$max_votes = 0;
$all_voters = array_sum($voters_pp);
for($i = 0; $i < count($voters_pp); $i++){
    $max_votes = $max_votes + $voters_pp[$i]*$max_crosses[$i];
}

// Percentage Per Party - store & echo OK
$percentages_pp = [];
for ($i = 0; $i < count($votes_pp); $i++){
    $percentages_pp[$i] = ($votes_pp[$i] / $max_votes) * 100;
    $query = "UPDATE ELECTION_RESULTS SET PERCENTAGES = ? WHERE PARTY_NAME = ?";
    $stmt = $conn->prepare($query); 
    $stmt->bind_param("ds", $percentages_pp[$i], $party_names[$i]);
    $stmt->execute();    
    $stmt->close();
}

// Electorate Seats Per Party - store & echo OK
$total_seats = 300;
$seats_pp = [];
for ($i = 0; $i < count($party_names); $i++){
    $seats_pp[$i] = (int)($percentages_pp[$i] * $total_seats / 100);
    $query = "UPDATE ELECTION_RESULTS SET ELECTORATE_SEATS = ? WHERE PARTY_NAME = ?";
    $stmt = $conn->prepare($query); 
    $stmt->bind_param("is", $seats_pp[$i], $party_names[$i]);
    $stmt->execute();    
    $stmt->close();
}

// Total Abstention Ratio% - echo only OK
$query = "SELECT COUNT(ID) AS VOTERS FROM VOTER WHERE hasVoted=1";
$stmt = $conn->prepare($query);
$stmt->execute();    
$stmt->bind_result($vtrs);
$stmt->fetch();
$voters = $vtrs;
$stmt->close();
$total_AR = (1 - ($voters / $all_voters)) * 100;

// Vote Count & AR% Per Province - store & echo
$query = "SELECT EP_NAME, COUNT(ID) AS VOTERS, SUM(CASE WHEN hasVoted = 1 THEN 1 ELSE 0 END) AS vote_count FROM VOTER JOIN ELECTORAL_PROVINCE ON name = EP_NAME GROUP BY EP_NAME";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die('Error during statement preparation: ' . $conn->error);
} 
$stmt->execute();
$stmt->bind_result($province_name, $province_voters, $province_vote_count); 

$province_info = [];
while($stmt->fetch()){
    $abs_ration = (($province_voters - $province_vote_count) / $province_voters) * 100;
    $province_info[] = [
        'name' => $province_name,
        'VOTERS' => $province_voters,
        'vote_count' => $province_vote_count,
        'abs_ratio' => $abs_ration
    ];
}

$stmt->close();

foreach($province_info as $info){
    $query = "UPDATE ELECTORAL_PROVINCE SET vote_count = ?, abs_ratio = ? WHERE `name` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ids", $info['vote_count'], $info['abs_ratio'], $info['name']);
    $stmt->execute();
    $stmt->close();
}
$conn->close();
?>