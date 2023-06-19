<?php
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
$articleId = end($segments);
// Fetch the article from the database using the $articleId
// Display the article on the page
if ($articleId == 'index.php') {
    //header("Location: http://localhost");
} else if ($articleId) {
    echo "This is the article with ID: " . $articleId;
    $result = $articleRepository->getArticleById($articleId);
    $commentResult = $commentRepository->getAllCommentsByArticleId($articleId);
    print_r($result);
    echo '<br><br>';
    print_r($commentResult);

    echo '<br><br>';
    function printComments($comments, $parentID = null, $depth = 0)
    {
        foreach ($comments as $comment) {
            if ($comment->getParentId() === $parentID) {
                // Print the comment
                echo str_repeat('-', $depth) . ' ' . $comment->getText() . '<br>';

                // Recursively print the replies
                printComments($comments, $comment->getId(), $depth + 1);
            }
        }
    }
    printComments($commentResult);

} else {
    echo "<h1>";
    foreach ($result as $res) {
        echo '<a href="articles/' . $res->getID() . '">' . $res->getName() . '</a><br>';
    }
    echo "</h1>";
    echo '
<body>
    <h2>Add Article</h2>
    <form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="POST">
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
    $article = new Article(
        $_POST['title'],
        $_POST['body'],
        date("Y-m-d H:i:s"), // Use the current date and time
        $_POST['user']
    );
    // Call the insertArticle function
    $articleRepository->addArticle($article);
}
?>