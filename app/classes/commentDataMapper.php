<?php
class CommentDataMapper
{
    private $database;
    private $commentFactory;
    private $queryBuilder;

    public function __construct(Database $database, CommentFactory $commentFactory, QueryBuilder $queryBuilder)
    {
        $this->database = $database;
        $this->commentFactory = $commentFactory;
        $this->queryBuilder = $queryBuilder;
    }

    public function fetchCommentById($id)
    {
        return $this->queryBuilder->select(array('user', 'email', 'text', 'date_created', 'id', 'parent_id', 'article_id'))
            ->from('comment')
            ->where('id = ' . $id);
    }

    public function fetchAllCommentsByArticleId($articleId)
    {
        $result = [];
        $queryResult = $this->queryBuilder->select(array('user', 'email', 'text', 'date_created', 'id', 'parent_id', 'article_id'))
            ->from('comment')
            ->where('article_id = ' . $articleId)->execute($this->database);
        for ($i = 0; $i < count($queryResult); $i++) {
            $result[$i] = $this->commentFactory->createComment(
                $queryResult[$i]['user'], $queryResult[$i]['email'],
                $queryResult[$i]['text'], $queryResult[$i]['date_created'], $queryResult[$i]['article_id'],
                $queryResult[$i]['parent_id'], $queryResult[$i]['id']
            );
        }
        return $result;
    }

    public function insertComment(Comment $comment)
    {
        $values = [];
        $values[0] = $comment->getContributorName();
        $values[1] = $comment->getEmail();
        $values[2] = $comment->getText();
        $values[3] = $comment->getPublicationDate();
        $values[4] = $comment->getParentId();
        $values[5] = $comment->getArticleId();



        $query = "INSERT INTO comment (user, email, text, date_created, parent_id, article_id) VALUES (?,?,?,?,?,?)";
        $this->database->insert($query, $values);

    }
}
?>