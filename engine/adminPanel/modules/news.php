<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 31.07.2017
 * Time: 2:29
 */

if (($_COOKIE['logining'] != 2) || ($_COOKIE['userStatus'] != 2)) {
    header('Location: /');
}
require_once '../modules/db_config.php';
$connect_DB = mysqli_connect($hostDB, $userDB, $passwordDB, $nameDB) or die("Ошибка" . mysqli_error($connect_DB));

$sql = "SELECT max(`id`) as `id` FROM `posts`";
$query = mysqli_query($connect_DB, $sql);
$news_num = mysqli_fetch_array($query);

//$sort = false;
$max = $news_num[0];
$min = 1;

$rus = array(
    "й", "ц", "у", "к", "е", "н", "г", "ш", "щ", "з", "х", "ъ",
    "ф", "ы", "в", "а", "п", "р", "о", "л", "д", "ж", "э",
    "я", "ч", "с", "м", "и", "т", "ь", "б", "ю"
);
$lat = array(
    "q", "w", "e", "r", "t", "y", "u", "i", "o", "p", "[", "]",
    "a", "s", "d", "f", "g", "h", "j", "k", "l", ";", "'",
    "z", "x", "c", "v", "b", "n", "m", ",", "."
);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM `posts` WHERE `posts`.`id` = '$id'";
    $query = mysqli_query($connect_DB, $sql);
    header("Location: news.php");
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/engine/adminPanel/css/style.css" rel="stylesheet">
    <title>News</title>
</head>
<body>

<div class="head">
    <a href="news.php"><h1>News</h1></a>
    <a href="/admin.php" class="adminExit">Admin Panel</a>
    <a class="viewSite inline-block" href="/" target="_blank">Просмотр сайта</a>
</div>

<div class="table">
    <div>
        <span>News: </span>
        <a href="addNews.php" class="addNews1"><b>Add News</b></a>
    </div>

    <div class="sortPanel">
        <div>
            <form action="editNews.php">
                <label>
                    <input name="id" type="number" class="inputId" value="<?php echo $_GET['id']; ?>">
                    <input name="inputIdBt" type="submit" class="btIdIn" value="Open news by id">
                </label>
            </form>
        </div>

        <div>
            <form>
                <label>
                    <input name="request" value="<?php echo $_GET['request']; ?>">
                    <input type="submit" name="search" value="Search">
                </label>
            </form>
        </div>


        <div>

            <form >
                <label>
                    <select name="sort" onchange="top.location.href =
                     this.options[this.selectedIndex].value;">
                        <option selected value="#">Sort by id</option>
                        <option value="?sort=idAsc">1-10</option>
                        <option value="?sort=idDesc">10-1</option>
                    </select>
                </label>

                <label>
                    <select name="sort" onchange="top.location.href =
                     this.options[this.selectedIndex].value;">
                        <option selected value="#">Sort by title</option>
                        <option value="?sort=titleAsc">A-z</option>
                        <option value="?sort=titleDesc">Z-a</option>
                    </select>
                </label>

                <label>
                    <select name="sort" onchange="top.location.href =
                     this.options[this.selectedIndex].value;">
                        <option selected value="#">Sort by date</option>
                        <option value="?sort=dateAsc">1-10</option>
                        <option value="?sort=dateDesc">10-1</option>
                    </select>
                </label>

            </form>

        </div>

    </div>
    <div>
        <?php

        $sort = $_GET['sort'];

        if (isset($_GET['request']) && $_GET['request'] != '') {

            $sort = '';

            $sql = "SELECT * FROM `posts`";
            $query = mysqli_query($connect_DB, $sql);

            $request = $_GET['request'];
            $request = mb_strtolower($request);
            $request = mb_strtolower($request);

            $arraySearch = explode(" ", $request);

            $n = 0;

            while ($infoArray = mysqli_fetch_array($query)) {
                $search = $infoArray['title'];
                $search = mb_strtolower($search);
                $arrayNews = explode(" ", $search);

                if (array_intersect($arraySearch, $arrayNews)) {
                    echo '<a href="editNews.php?id=' . $infoArray['id'] . '" class = "showNews">
            <span><b>| ID: ' . $infoArray['id'] . ' |</b></span>
            <span>| Title: ' . $infoArray['title'] . ' |</span>
            <span>| Time: ' . $infoArray['time'] . ' |</span>
            <span>| Views: ' . $infoArray['views'] . ' |</span>
            <span>| Likes: ' . $infoArray['likes'] . ' |</span>
            <span>| Date: ' . $infoArray['date'] . ' |</span>
            </a>
            <a href="?id=' . $infoArray['id'] . '" class="btDeleteN"><b>Delete</b></a>
            ';
                    $n++;
                }
            }
            if ($n == 0) {

                $sort = '';

                $sql = "SELECT * FROM `posts`";
                $query = mysqli_query($connect_DB, $sql);

                $request = str_replace($lat, $rus, $request);
                $request = mb_strtolower($request);
                $arraySearch = explode(" ", $request);

                while ($infoArray = mysqli_fetch_array($query)) {
                    $search = $infoArray['title'];
                    $search = mb_strtolower($search);
                    $arrayNews = explode(" ", $search);

                    if (array_intersect($arraySearch, $arrayNews)) {
                        $n++;
                        if ($n == 1) {
                            //переведений запрос треба вивести норм, це коли знайдено з переведеним
                            echo $request;
                        }
                        echo '<a href="editNews.php?id=' . $infoArray['id'] . '" class = "showNews">
            <span><b>| ID: ' . $infoArray['id'] . ' |</b></span>
            <span>| Title: ' . $infoArray['title'] . ' |</span>
            <span>| Time: ' . $infoArray['time'] . ' |</span>
            <span>| Views: ' . $infoArray['views'] . ' |</span>
            <span>| Likes: ' . $infoArray['likes'] . ' |</span>
            <span>| Date: ' . $infoArray['date'] . ' |</span>
            </a>
            <a href="?id=' . $infoArray['id'] . '" class="btDeleteN"><b>Delete</b></a>
            ';
                        $n++;
                    }
                }
            }
            if ($n == 0) {
                $sort = '';
                echo "False";
            }

            echo '<br>';

        }

        if ($sort == 'idAsc') {

            $sql = "SELECT * FROM `posts`";
            $query = mysqli_query($connect_DB, $sql);

            for ($i = $max; $i >= $min; $i--) {
                $infoArray = mysqli_fetch_assoc($query);

                if ($infoArray['id'] != '') {
                    echo '<a href="editNews.php?id=' . $infoArray['id'] . '" class = "showNews">
            <span><b>| ID: ' . $infoArray['id'] . ' |</b></span>
            <span>| Title: ' . $infoArray['title'] . ' |</span>
            <span>| Time: ' . $infoArray['time'] . ' |</span>
            <span>| Views: ' . $infoArray['views'] . ' |</span>
            <span>| Likes: ' . $infoArray['likes'] . ' |</span>
            <span>| Date: ' . $infoArray['date'] . ' |</span>
            </a>
            <a href="?id=' . $infoArray['id'] . '" class="btDeleteN"><b>Delete</b></a>
            ';
                }
            }
        }

        if ($sort == 'titleAsc') {

            $sql = "SELECT * FROM `posts` ORDER BY `posts`.`title` ASC";
            $query = mysqli_query($connect_DB, $sql);

            for ($i = $max; $i >= $min; $i--) {
                $infoArray = mysqli_fetch_assoc($query);

                if ($infoArray['id'] != '') {
                    echo '<a href="editNews.php?id=' . $infoArray['id'] . '" class = "showNews">
            <span><b>| ID: ' . $infoArray['id'] . ' |</b></span>
            <span>| Title: ' . $infoArray['title'] . ' |</span>
            <span>| Time: ' . $infoArray['time'] . ' |</span>
            <span>| Views: ' . $infoArray['views'] . ' |</span>
            <span>| Likes: ' . $infoArray['likes'] . ' |</span>
            <span>| Date: ' . $infoArray['date'] . ' |</span>
            </a>
            <a href="?id=' . $infoArray['id'] . '" class="btDeleteN"><b>Delete</b></a>
            ';
                }
            }
        }
        if ($sort == 'titleDesc') {

            $sql = "SELECT * FROM `posts` ORDER BY `posts`.`title` DESC";
            $query = mysqli_query($connect_DB, $sql);

            for ($i = $max; $i >= $min; $i--) {
                $infoArray = mysqli_fetch_assoc($query);

                if ($infoArray['id'] != '') {
                    echo '<a href="editNews.php?id=' . $infoArray['id'] . '" class = "showNews">
            <span><b>| ID: ' . $infoArray['id'] . ' |</b></span>
            <span>| Title: ' . $infoArray['title'] . ' |</span>
            <span>| Time: ' . $infoArray['time'] . ' |</span>
            <span>| Views: ' . $infoArray['views'] . ' |</span>
            <span>| Likes: ' . $infoArray['likes'] . ' |</span>
            <span>| Date: ' . $infoArray['date'] . ' |</span>
            </a>
            <a href="?id=' . $infoArray['id'] . '" class="btDeleteN"><b>Delete</b></a>
            ';
                }
            }
        }

        if ($sort == 'dateAsc') {

            $sql = "SELECT * FROM `posts` ORDER BY `posts`.`date` ASC";
            $query = mysqli_query($connect_DB, $sql);

            for ($i = $max; $i >= $min; $i--) {
                $infoArray = mysqli_fetch_assoc($query);

                if ($infoArray['id'] != '') {
                    echo '<a href="editNews.php?id=' . $infoArray['id'] . '" class = "showNews">
            <span><b>| ID: ' . $infoArray['id'] . ' |</b></span>
            <span>| Title: ' . $infoArray['title'] . ' |</span>
            <span>| Time: ' . $infoArray['time'] . ' |</span>
            <span>| Views: ' . $infoArray['views'] . ' |</span>
            <span>| Likes: ' . $infoArray['likes'] . ' |</span>
            <span>| Date: ' . $infoArray['date'] . ' |</span>
            </a>
            <a href="?id=' . $infoArray['id'] . '" class="btDeleteN"><b>Delete</b></a>
            ';
                }
            }
        }

        if ($sort == 'dateDesc') {

            $sql = "SELECT * FROM `posts` ORDER BY `posts`.`date` DESC";
            $query = mysqli_query($connect_DB, $sql);

            for ($i = $max; $i >= $min; $i--) {
                $infoArray = mysqli_fetch_assoc($query);

                if ($infoArray['id'] != '') {
                    echo '<a href="editNews.php?id=' . $infoArray['id'] . '" class = "showNews">
            <span><b>| ID: ' . $infoArray['id'] . ' |</b></span>
            <span>| Title: ' . $infoArray['title'] . ' |</span>
            <span>| Time: ' . $infoArray['time'] . ' |</span>
            <span>| Views: ' . $infoArray['views'] . ' |</span>
            <span>| Likes: ' . $infoArray['likes'] . ' |</span>
            <span>| Date: ' . $infoArray['date'] . ' |</span>
            </a>
            <a href="?id=' . $infoArray['id'] . '" class="btDeleteN"><b>Delete</b></a>
            ';
                }
            }

        } else {
            $sql = "SELECT * FROM `posts` ORDER BY `posts`.`id` DESC";
            $query = mysqli_query($connect_DB, $sql);

            for ($i = $max; $i >= $min; $i--) {
                $infoArray = mysqli_fetch_assoc($query);

                if ($infoArray['id'] != '') {
                    echo '<a href="editNews.php?id=' . $infoArray['id'] . '" class = "showNews">
            <span><b>| ID: ' . $infoArray['id'] . ' |</b></span>
            <span>| Title: ' . $infoArray['title'] . ' |</span>
            <span>| Time: ' . $infoArray['time'] . ' |</span>
            <span>| Views: ' . $infoArray['views'] . ' |</span>
            <span>| Likes: ' . $infoArray['likes'] . ' |</span>
            <span>| Date: ' . $infoArray['date'] . ' |</span>
            </a>
            <a href="?id=' . $infoArray['id'] . '" class="btDeleteN"><b>Delete</b></a>
            ';
                }
            }
        }
        ?>
    </div>
</div>

</body>
</html>