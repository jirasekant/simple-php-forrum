<?php
class CommentFactory
{
    public function createComment($contributorName, $email, $text, $publicationDate, $articleId, $parentId = null, $id = null)
    {
        return new Comment($contributorName, $email, $text, $publicationDate, $articleId, $parentId, $id);
    }
}
?>