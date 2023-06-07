// Path: app\index.html
## simple hello world html file
<html>
<body>
<h1>Hello World!</h1>

<?php
$connect = mysqli_connect(
    'db',
    'php_docker',
    'password',
    'php_docker'
);
$table_name = 'article';
$query = "SELECT * FROM $table_name";
$response = mysqli_query($connect, $query);

echo "<strong>$table_name</strong><br><br>";
while ($row = mysqli_fetch_array($response)) {
    echo $row['title'] . "<br>" . $row['body'] . "<br>" . $row['date_created'] . "<br><br>";
}

echo "Hello World!";
?>
</body>
</html>

