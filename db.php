<?php
$conn = new mysqli("localhost", "root", "", "ogloszenia");
if ($conn->connect_error) {
exit("Connection failed: " . $conn->connect_error);
}
?>