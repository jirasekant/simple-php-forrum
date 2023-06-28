<?php
class CommentRepository
{
    private $dataMapper;

    public function __construct(CommentDataMapper $dataMapper)
    {
        $this->dataMapper = $dataMapper;
    }

    public function getAllCommentsByArticleId($articleId)
    {
        return $this->dataMapper->fetchAllCommentsByArticleId($articleId);
    }

    public function addComment(Comment $comment)
    {
        $this->dataMapper->insertComment($comment);
        header("Location: /articles/" . $comment->getArticleId());
        exit();
    }
}
?>