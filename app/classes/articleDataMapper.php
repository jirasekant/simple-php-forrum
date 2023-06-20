<?php
class ArticleDataMapper
{
    private $database;
    private $articleFactory;
    private $queryBuilder;

    // cunstructor
    public function __construct(Database $database, ArticleFactory $articleFactory, QueryBuilder $queryBuilder)
    {
        $this->database = $database;
        $this->articleFactory = $articleFactory;
        $this->queryBuilder = $queryBuilder;
    }

    // get article by id
    public function fetchArticleById($id)
    {
        $result = [];
        $queryResult = $this->queryBuilder->select(array('id', 'title', 'body', 'user', 'date_created'))->from('article')->where('id = ' . $id)
            ->execute($this->database);
        for ($i = 0; $i < count($queryResult); $i++) {
            $result[$i] = $this->articleFactory->createArticle(
                $queryResult[$i]['id'], $queryResult[$i]['title'],
                $queryResult[$i]['body'], $queryResult[$i]['date_created'], $queryResult[$i]['user']
            );
        }
        return $result;
    }

    // get all articles
    public function fetchAllArticles()
    {
        $result = [];
        $queryResult = $this->queryBuilder->select(array('id', 'title', 'body', 'user', 'date_created'))->from('article')->execute($this->database);
        for ($i = 0; $i < count($queryResult); $i++) {
            $result[$i] = $this->articleFactory->createArticle(
                $queryResult[$i]['id'], $queryResult[$i]['title'],
                $queryResult[$i]['body'], $queryResult[$i]['user'], $queryResult[$i]['date_created']
            );
        }
        return $result;

    }

    // insert article
    public function insertArticle(Article $article)
    {
        $values = [];
        $values[0] = $article->getName();
        $values[1] = $article->getText();
        $values[2] = $article->getContributorName();
        $values[3] = $article->getPublicationDate();
        $query = "INSERT INTO article (title, body, user, date_created) VALUES (?,?,?,?)";
        $this->database->insert($query, $values);
    }
}
?>