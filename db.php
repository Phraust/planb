<?php
//Database connections


$host = "********";
$db_name = "********";
$username = "********";
$password = "********";
try {
    $con = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
}catch(PDOException $exception){ //TO handle connection error
    echo "Connection error: " . $exception->getMessage();
}


$sqlicon = new mysqli("IP", "USER", "PW", "DATABASENAME");
if (mysqli_connect_errno()) {
printf("Connect failed: %s\n", mysqli_connect_error());
exit();
}

$link = mysqli_connect("IP", "USER", "PW", "DATABASENAME");

//Notice the $conr
$hostr = "********";
$db_namer = "********";
$usernamer = "********";
$passwordr = "********";
try {
    $conr = new PDO("mysql:host={$hostr};dbname={$db_namer}", $usernamer, $passwordr);
}catch(PDOException $exception){ //TO handle connection error
    echo "Connection error: " . $exception->getMessage();
}
?>
