<?php
$requestUrl = $_SERVER['REQUEST_URI'];

require("app.php");
$database = new Database(
    'db',
    'php_docker',
    'password',
    'php_docker'
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
if ($articleId) {
    echo "This is the article with ID: " . $articleId;
    $result = $articleRepository->getArticleById($articleId);
    $commentResult = $commentRepository->getAllCommentsByArticleId($articleId);
    print_r($result);
    echo '<br><br>';
    print_r($commentResult);

    echo '<br><br>';
    function printComments($comments, $parentID = null, $depth = 0) {
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
}
?>