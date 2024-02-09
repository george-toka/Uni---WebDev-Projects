
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat&family=IBM+Plex+Sans:ital,wght@0,100;1,100;1,300&display=swap" rel="stylesheet">
</head>
    <style>
        body,html{
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            
        }
        .all{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            width: 100%;
            height: 100%;
            overflow: hidden;
            position: relative;
            background-image: url(../img/1.jpg);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            animation: change 36s linear infinite;
            perspective: 1px;
            transform-style: preserve-3d;

        }
        nav {
            clear: both;
            display: inline-flex;
            height: 80px;
            gap:30px;
            text-align: center;
            
        }

        nav a {
            min-width: 210px;
            height: auto;
            float: left;
            font-size: 1.5rem;
        }
        .container{
            display: flex;
            color: rgb(17, 16, 16);
            font-family: 'Caveat', cursive;
            font-family: 'IBM Plex Sans', sans-serif;
            font-weight: 900;
            margin-top: 1px;
            flex-wrap: wrap;
        }

        .container img{
            padding-left: 20px;
            padding-top: 20px;
        }
        .favicon{
            position:relative;
            width: 70px;
            height: 90px;
            flex-wrap:wrap;
        }
        h1{
            font-size: 30px;
            margin-top: 40px;
            font-family: 'Caveat', cursive;
            font-family: 'IBM Plex Sans', sans-serif;
            font-weight: 900;
            font-size: 50px;
            margin-bottom:0px;
            margin-left: 40px;
        }

        .grid-container{
            display: grid;
            grid-template-columns: auto auto auto;
        }
        .grid-item{
            display: block;
            padding: 10px;
            text-decoration: none;
            border: 1px solid black;
            color: black;
            background-color: rgb(230, 212, 212);

        }
        @keyframes change{
            0%{
                background-image: url(img/1.jpg);
            }
            20%{
                background-image: url(img/3.jpg);
            }
            40%{
                background-image: url(img/5.jpg);
            }
            60%{
                background-image: url(img/7.jpg);
            }
            80%{
                background-image: url(img/11.jpg);
            }
            100%{
                background-image: url(img/1.jpg);
            }
        }

        .center-element{
            position: absolute;
            top:40%;
        }
        @media (max-width: 500px){
            .container{
                display: inline-flex;
                flex-direction: column;
                color: rgb(17, 16, 16);
                font-family: 'Caveat', cursive;
                font-family: IBMPlexSans, Arial, Georgia, serif;
                font-weight: 900;
                margin-top: 1px;
                flex-wrap: wrap;
            }
            .grid-container{
            display: grid;
            background-color: rgb(230, 212, 212);
            grid-template-rows: 100px 100px 100px;
            grid-template-columns: auto;
        }
            h1{
                margin-left:0px;
            }
        }

    </style>
<body>
    <div class="all">
        <div class="container">
            <img src="img/ypes-logo-en.png" alt="ELLAS-GT-DE-XAMOGELAS" />
            <!-- <img class="favicon" src="https://ekloges.ypes.gr/favicon.ico" draggable="false" style="margin-left: 27%;"> -->
        </div>

        <div class="center-element">
            <h1>ELECTIONS 2024</h1> 
        <nav>
            <div class="grid-container">
                <a href="validate_user.php" class="grid-item">Σύνδεση Ψηφοφόρου</a>
                <a href="final.php" class="grid-item" target="blank">Εκλογικά Αποτελέσματα</a>
                <a href="#section3" class="grid-item">Υποψήφιοι</a>
            </div>          
        </nav>
        </div>
    </div>
</body>
</html>