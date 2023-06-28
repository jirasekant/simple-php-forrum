<?php
class ArticleRepository
{
    private $dataMapper;

    public function __construct(ArticleDataMapper $dataMapper)
    {
        $this->dataMapper = $dataMapper;
    }

    public function getAllArticles()
    {
        return $this->dataMapper->fetchAllArticles();
    }

    public function getArticleById($id)
    {
        return $this->dataMapper->fetchArticleById($id);
    }

    public function addArticle(Article $article)
    {
        $this->dataMapper->insertArticle($article);
        header("Location: /");
    }
}
?>