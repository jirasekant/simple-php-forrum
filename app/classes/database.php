<?php
class Database
{
    private $bindParam;
    private $host;
    private $username;
    private $password;
    private $databaseName;
    private $connection;

    // constructor
    public function __construct($host, $username, $password, $databaseName, BindParam $bindParam)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->databaseName = $databaseName;
        $this->bindParam = $bindParam;
    }

    // function to connect to the database
    public function connect()
    {
        $this->connection = mysqli_connect($this->host, $this->username, $this->password, $this->databaseName);

        if (!$this->connection) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    // function to query the database
    public function query($query)
    {
        $result = mysqli_query($this->connection, $query);

        if (!$result) {
            die("Query failed: " . mysqli_error($this->connection));
        }

        return $result;
    }

    // function to fetch data from the database
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
?>