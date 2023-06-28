<?php
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