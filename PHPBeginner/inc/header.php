<html>
<head>
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>
    <div class="header">
        <div class="wrapper">
            <h1 class="branding-title"><a href=".">Personal Media Library</a></h1>
            <ul class="nav">
                <li class="books <?= $section == "books" ? "on":""; ?>"><a href="catalog.php?cat=books">Books</a></li>
                <li class="movies <?= $section == "movies" ? "on":""; ?>"><a href="catalog.php?cat=movies">Movies</a></li>
                <li class="music <?= $section == "music" ? "on":""; ?>"><a href="catalog.php?cat=music">Music</a></li>
                <li class="suggest <?= $section == "suggest" ? "on":""; ?>"><a href="suggest.php">Suggest</a></li>
            </ul>
        </div>
    </div>
    <div class="search">
        <form action="catalog.php" method="get">
            <label for="s">Search : </label>
            <input type="text" name="s" id="s"/>
            <input type="submit" value="go"/>
        </form>
    </div>
    <div id="content">