<?php
class ArticleFactory
{
    public function createArticle($id, $name, $text, $publicationDate, $contributorName)
    {
        return new Article($name, $text, $publicationDate, $contributorName, $id);
    }
}
?>