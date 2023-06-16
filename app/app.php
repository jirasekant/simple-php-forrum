<?php
class Article
{
    private $id;
    private $name;
    private $text;
    private $publicationDate;
    private $contributorName;

    // Constructor, getters, and setters for the properties
    //constructor
    public function __construct($id, $name, $text, $publicationDate, $contributorName)
    {
        $this->id = $id;
        $this->name = $name;
        $this->text = $text;
        $this->publicationDate = $publicationDate;
        $this->contributorName = $contributorName;
    }
    //getters for the properties
    public function getID()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getText()
    {
        return $this->text;
    }
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }
    public function getContributorName()
    {
        return $this->contributorName;
    }
    //setters for the properties
    public function setName($name)
    {
        $this->name = $name;
    }
    public function setText($text)
    {
        $this->text = $text;
    }
    public function setPublicationDate($publicationDate)
    {
        $this->publicationDate = $publicationDate;
    }
    public function setContributorName($contributorName)
    {
        $this->contributorName = $contributorName;
    }

}

class Comment
{
    private $contributorName;
    private $email;
    private $text;
    private $publicationDate;
    private $parentId;

    // Constructor, getters, and setters for the properties
    //constructor
    public function __construct($contributorName, $email, $text, $publicationDate, $parentId = null)
    {
        $this->contributorName = $contributorName;
        $this->email = $email;
        $this->text = $text;
        $this->publicationDate = $publicationDate;
        $this->parentId = $parentId;
    }
    //getters
    
    public function getContributorName()
    {
        return $this->contributorName;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getText()
    {
        return $this->text;
    }
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }
    public function getParentId()
    {
        return $this->parentId;
    }

    //setters
    public function setContributorName($contributorName)
    {
        $this->contributorName = $contributorName;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function setText($text)
    {
        $this->text = $text;
    }
    public function setPublicationDate($publicationDate)
    {
        $this->publicationDate = $publicationDate;
    }
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }
}

class Database
{
    private $host;
    private $username;
    private $password;
    private $databaseName;
    private $connection;

    //Constructor
    public function __construct($host, $username, $password, $databaseName)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->databaseName = $databaseName;
    }

    //Function to connect to the database
    public function connect()
    {
        $this->connection = mysqli_connect($this->host, $this->username, $this->password, $this->databaseName);

        if (!$this->connection) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    //Function to query the database
    public function query($query)
    {
        $result = mysqli_query($this->connection, $query);

        if (!$result) {
            die("Query failed: " . mysqli_error($this->connection));
        }

        return $result;
    }

    //Function to fetch data from the database
    public function fetch($query)
    {
        $result = $this->query($query);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }
}

class ArticleDataMapper
{
    private $database;
    private $articleFactory;
    private $queryBuilder;

    public function __construct(Database $database, ArticleFactory $articleFactory, QueryBuilder $queryBuilder)
    {
        $this->database = $database;
        $this->articleFactory = $articleFactory;
        $this->queryBuilder = $queryBuilder;
    }

    public function fetchArticleById($id)
    {
        $result = [];
        $queryResult = $this->queryBuilder->select(array('id', 'title', 'body', 'user', 'date_created'))->from('article')->where('id = ' . $id)
                                                                                                        ->execute($this->database);
        for ($i=0; $i < count($queryResult); $i++) {
            $result[$i] = $this->articleFactory->createArticle($queryResult[$i]['id'], $queryResult[$i]['title'], 
                $queryResult[$i]['body'], $queryResult[$i]['user'], $queryResult[$i]['date_created']);
        }
        return $result;

    }

    public function fetchAllArticles()
    {
        $result = [];
        $queryResult = $this->queryBuilder->select(array('id', 'title', 'body', 'user', 'date_created'))->from('article')->execute($this->database);
        for ($i=0; $i < count($queryResult); $i++) {
            $result[$i] = $this->articleFactory->createArticle($queryResult[$i]['id'], $queryResult[$i]['title'], 
                $queryResult[$i]['body'], $queryResult[$i]['user'], $queryResult[$i]['date_created']);
        }
        return $result;

    }

    public function insertArticle(Article $article)
    {
        // Implementation details
    }
}

class CommentDataMapper
{
    private $database;
    private $queryBuilder;

    public function __construct(Database $database, QueryBuilder $queryBuilder)
    {
        $this->database = $database;
        $this->queryBuilder = $queryBuilder;
    }

    public function fetchCommentById($id)
    {
        return $this->queryBuilder->select('id', 'contributor_name', 'email', 'text', 'publication_date', 'parent_id')
            ->from('comments')
            ->where('id', '=', $id);
    }

    public function fetchAllCommentsByArticleId($articleId)
    {
        return $this->queryBuilder->select('id', 'contributor_name', 'email', 'text', 'publication_date', 'parent_id')
            ->from('comments')
            ->where('article_id', '=', $articleId)->execute();
    }

    public function insertComment(Comment $comment)
    {
        // Implementation details
    }
}

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
        // Implementation details
    }
}

class CommentRepository
{
    private $dataMapper;

    public function __construct(CommentDataMapper $dataMapper)
    {
        $this->dataMapper = $dataMapper;
    }

    public function getAllCommentsByArticleId($articleId)
    {
        // Implementation details
    }

    public function getCommentById($id)
    {
        // Implementation details
    }

    public function addComment(Comment $comment)
    {
        // Implementation details
    }
}

class ArticleFactory
{
    public function createArticle($id, $name, $text, $publicationDate, $contributorName)
    {
        return new Article($id, $name, $text, $publicationDate, $contributorName);
    }
}

class CommentFactory
{
    public function createComment($contributorName, $email, $text, $publicationDate, $parentId = null)
    {
        return new Comment($contributorName, $email, $text, $publicationDate, $parentId);
    }
}

class QueryBuilder
{
    private $query;

    public function select($columns)
    {
        $this->query = "SELECT " . implode(", ", $columns);
        return $this;
    }

    public function from($table)
    {
        $this->query .= " FROM " . $table;
        return $this;
    }

    public function where($condition)
    {
        $this->query .= " WHERE " . $condition;
        return $this;
    }

    public function execute(Database $database)
    {
        return $database->fetch($this->query);
    }
}
?>