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
    public function __construct($name, $text, $publicationDate, $contributorName, $id = null)
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
    private $id;
    private $articleId;

    // Constructor, getters, and setters for the properties
    //constructor

    public function __construct($contributorName, $email, $text, $publicationDate, $articleId, $parentId = null, $id = null)
    {
        $this->contributorName = $contributorName;
        $this->email = $email;
        $this->text = $text;
        $this->publicationDate = $publicationDate;
        $this->parentId = $parentId;
        $this->articleId = $articleId;
        $this->id = $id;
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
    public function getId()
    {
        return $this->id;
    }
    public function getArticleId()
    {
        return $this->articleId;
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
class BindParam
{

    private $values = array(), $types = '';



    public function add($type, &$value)
    {

        $this->values[] = $value;

        $this->types .= $type;

    }



    public function get()
    {

        return array_merge(array($this->types), $this->values);

    }

}
class Database
{
    private $bindParam;
    private $host;
    private $username;
    private $password;
    private $databaseName;
    private $connection;

    //Constructor
    public function __construct($host, $username, $password, $databaseName, BindParam $bindParam)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->databaseName = $databaseName;
        $this->bindParam = $bindParam;
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
    public function insert($query, $values)
    {
        $stmt = $this->connection->prepare($query);
        foreach ($values as $value) {
            $this->bindParam->add('s', $value);
        }
        $params = $this->bindParam->get();
        $stmt->bind_param(...$params);
        $stmt->execute();
        $stmt->close();
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
        for ($i = 0; $i < count($queryResult); $i++) {
            $result[$i] = $this->articleFactory->createArticle(
                $queryResult[$i]['id'], $queryResult[$i]['title'],
                $queryResult[$i]['body'], $queryResult[$i]['date_created'], $queryResult[$i]['user']
            );
        }
        return $result;

    }

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
        return $this->queryBuilder->select('user, email, text, date_created, id, parent_id, article_id')
            ->from('comments')
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

    public function getCommentById($id)
    {
        // Implementation details
    }

    public function addComment(Comment $comment)
    {
        $this->dataMapper->insertComment($comment);
        header("Location: /articles/" . $comment->getArticleId());
        exit();
    }
}

class ArticleFactory
{
    public function createArticle($id, $name, $text, $publicationDate, $contributorName)
    {
        return new Article($name, $text, $publicationDate, $contributorName, $id);
    }
}

class CommentFactory
{
    public function createComment($contributorName, $email, $text, $publicationDate, $articleId, $parentId = null, $id = null)
    {
        return new Comment($contributorName, $email, $text, $publicationDate, $articleId, $parentId, $id);
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