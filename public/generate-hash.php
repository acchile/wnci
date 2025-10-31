<?php
$password = 'admin123';
$hash = password_hash($password, PASSWORD_BCRYPT);

echo "<h2>Password Hash Generator</h2>";
echo "<strong>Password:</strong> " . htmlspecialchars($password) . "<br><br>";
echo "<strong>New Hash:</strong><br>";
echo "<textarea style='width:100%; height:100px;'>" . $hash . "</textarea><br><br>";
echo "Copy the hash above and use it in the SQL below.";