<?php
$new_password = 'admin123'; 
$hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
echo "Password yang akan digunakan: " . $new_password . "<br>";
echo "Hash yang BENAR untuk dimasukkan ke database: <br>";
echo "<strong>" . $hashed_password . "</strong>";
?>