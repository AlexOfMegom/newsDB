<?php
// // require ;
// require_once('myClassForDB.php');
// // include 'myClassForDB.php';

// $peger = new NewsPaginator('https://localhost/8081', ""); 
// $items = $peger->getItems("SELECT * FROM `news`");


?>

<?php
// require_once('myClassForDB.php');
// header('Content-Type: text/html; charset=utf-8');

if (isset($_GET['id'])) {
    $newsId = $_GET['id'];

    // echo $newsId . "/<br>";
}

$conn = new PDO("mysql:host=127.0.0.1:3307;dbname=news", "root", "root");

$sql = "SELECT * FROM news";
$result = $conn->query($sql);

// $host = "127.0.0.1:3307";
// $user = "root";
// $password = "root";
// $dbname="news";
// try {

// //     // Подключение к БД
//     $dbh = new PDO("mysql:host=127.0.0.1:3307;dbname=news", "root", "root");
//     $sql = "SELECT * FROM `news`";
//     $result = $conn->query($sql);

//     while ($row = $result->fetch()) {
//         // if ($row['id'] ==$newsId) {
//             $row['announce'];
//     //     }
//      }


//     }
//     catch (PDOException $e) {
//         echo "Database error: " . $e->getMessage();
//     }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>





    <title></title>
    <link rel='stylesheet' href='style.css'>

    <div class="content">

        <div class="container">
            <div class="cont_wrap"></div>
            <div class="wrapper ">
                <div class="line">

                </div>
                <?php

                while ($row = $result->fetch()) {

                    if ($row['id'] == $newsId) { ?>
                        <div class="annonce">
                            <h3> <? echo $row['announce'] . "<br>"; ?> </h3>
                        </div>

                        <? echo $row['content'] . "<br>"; ?>
                        <div class="line"></div>

                <? }
                } ?>


                <div id="news-text">

                    <div class="back-to-newslist"><a href="./news.php">Все новости >>></a></div>
                </div>

            </div>

            <div></div>
        </div>
    </div>









    <script>
        fetch('https://api.binance.com/api/v3/avgPrice?symbol=BTCUSDT')
            .then(r => r.json()
                .then(j => console.log(parseFloat(j.price).toFixed(2))));

        fetch('https://api.binance.com/api/v3/avgPrice?symbol=ETHUSDT')
            .then(r => r.json()
                .then(j => console.log(parseFloat(j.price).toFixed(2))));

        fetch('https://api.binance.com/api/v3/avgPrice?symbol=BNBUSDT')
            .then(r => r.json()
                .then(j => console.log(parseFloat(j.price).toFixed(2))));
    </script>


    <style>
        .html {
            background-color: #ddd;
            padding: 0;
            margin: 0;
        }

        .content {
            background-color: #ddd;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: row;
        }

        .container {}

        .wrapper {
            background-color: #fff;
            border: #000 solid 2px;
            padding: 100px;
            margin: 10% 5%;
        }

        .line {
            text-align: center;
            /* Выравниваем текст по центру */
            border-top: 1px dashed #000;
            /* Параметры линии  */
            height: 18px;
            /* Высота блока */
            background: url(images/scissors.png) no-repeat 10px -18px;
            /* Параметры фона */
        }

        a {
            color: red;
        }

        .wrapper {}
    </style>
</body>

</html>