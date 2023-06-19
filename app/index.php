<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$requestUrl = $_SERVER['REQUEST_URI'];

require("app.php");
$bindParam = new BindParam;
$database = new Database(
    'db',
    'php_docker',
    'password',
    'php_docker',
    $bindParam
);
$queryBuilder = new QueryBuilder();

$articleFactory = new ArticleFactory();
$articleDataMapper = new ArticleDataMapper($database, $articleFactory, $queryBuilder);
$articleRepository = new ArticleRepository($articleDataMapper);

$commentFactory = new CommentFactory();
$commentDataMapper = new CommentDataMapper($database, $commentFactory, $queryBuilder);
$commentRepository = new CommentRepository($commentDataMapper);

$database->connect();
$result = $articleRepository->getAllArticles();

// Split the URL into separate segments
$segments = explode('/', $requestUrl);

// Assuming the article ID is the last segment of the URL
$articleId = null;
if (count($segments) > 2)
    $articleId = $segments[2];

// Fetch the article from the database using the $articleId
// Display the article on the page
if ($articleId == 'index.php' || $articleId == 'favicon.ico') {
} else if ($articleId) {
    $result = $articleRepository->getArticleById($articleId);
    $commentResult = $commentRepository->getAllCommentsByArticleId($articleId);
    echo '<h1>' . $result[0]->getName() . '<br></h1><b>' . $result[0]->getContributorName() . '</b> at ' . $result[0]->getPublicationDate() . '<br><br>' . $result[0]->getText() . '<br><br><br>';

    function printComments($articleId, $comments, $parentID = null, $depth = 0)
    {
        foreach ($comments as $comment) {
            if ($comment->getParentId() === $parentID) {
                // Open the comment box
                echo '<div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; position: relative;">';

                // Print the comment with username
                echo $comment->getContributorName() . ': ' . $comment->getText();

                // Generate a reply button with anchor link
                echo '<a href="/articles/' . $articleId . '/reply/' . $comment->getId() . '" name="replyButton" value="' . $comment->getId() . '" style="position: absolute; top: 5px; right: 5px;">Reply</a>';

                // Recursively print the replies
                printComments($articleId, $comments, $comment->getId(), $depth + 1);

                // Close the comment box
                echo '</div>';
            }
        }
    }

    $parentId = null;
    printComments($articleId, $commentResult);
    if (count($segments) > 3) {
        $parentId = $segments[4];
    }
    echo '
    <body>
    <h2>Add Comment</h2>
    <form action="http://localhost/" method="POST">
        <input type="hidden" name="commentSubmit" value="1">
        <input type="hidden" name="articleId" value="' . $articleId . '">
        <input type="hidden" name="parentId" value="' . $parentId . '">

        <label for="contributorName">Your Name:</label><br>
        <input type="text" id="contributorName" name="contributorName"><br><br>

        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email"><br><br>

        <label for="commentText">Comment:</label><br>
        <textarea id="commentText" name="commentText" rows="5" cols="30"></textarea><br><br>

        <input type="submit" value="Submit">
    </form>
</body>';
} else {
    echo "<h1>Simple PHP Forum<br></h1><h2>Available articles:<br></h2><h3>";
    foreach ($result as $res) {
        echo '<a href="articles/' . $res->getID() . '">' . $res->getName() . '</a><br>';
    }
    echo "</h3>";
    echo '
<body>
    <h2>Add Article</h2>
    <form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="POST">
        <input type="hidden" name="articleSubmit" value="1">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title"><br><br>
        
        <label for="body">Body:</label><br>
        <textarea id="body" name="body" rows="5" cols="30"></textarea><br><br>
        
        <label for="user">User:</label><br>
        <input type="text" id="user" name="user"><br><br>
        
        <input type="submit" value="Submit">
    </form>
</body>
';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    if (isset($_POST['articleSubmit'])) {
        $article = new Article(
            $_POST['title'],
            $_POST['body'],
            date("Y-m-d H:i:s"), // Use the current date and time
            $_POST['user']
        );
        // Call the insertArticle function
        $articleRepository->addArticle($article);
    } else if (isset($_POST['commentSubmit'])) {
        $comment;
        if (!$_POST['parentId']) {
            $comment = new Comment(
                $_POST['contributorName'],
                $_POST['email'],
                $_POST['commentText'],
                date("Y-m-d H:i:s"), // Use the current date and time
                $_POST['articleId'],
            );
        } else {
            $comment = new Comment(
                $_POST['contributorName'],
                $_POST['email'],
                $_POST['commentText'],
                date("Y-m-d H:i:s"), // Use the current date and time
                $_POST['articleId'],
                $_POST['parentId']
            );
        }

        $commentRepository->addComment($comment);
    }
}
?>