<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Database Connection Diagnostic</h1>";

$overrideFile = __DIR__ . '/config/db_credentials.php';
echo "Checking db_credentials.php: ";
if (file_exists($overrideFile)) {
    echo "Exists. ";
    $creds = include $overrideFile;
    if (is_array($creds)) {
         echo "Valid array structure. Username: " . htmlspecialchars($creds['username'] ?? 'Not set') . "<br>";
    } else {
         echo "ERROR: db_credentials.php did not return an array!<br>";
    }
} else {
    echo "ERROR: db_credentials.php does not exist!<br>";
}

require_once __DIR__ . '/config/database.php';

echo "<h2>Attempting Connection to Main Database (phichaia_student):</h2>";
try {
    $db_main = Database::getMainConnection();
    echo "✅ Success!<br>";
} catch (Exception $e) {
    echo "❌ Failed: " . htmlspecialchars($e->getMessage()) . "<br>";
}

echo "<h2>Attempting Connection to Sports Database (school_sports_day):</h2>";
try {
    $db_sports = Database::getSportsConnection();
    echo "✅ Success!<br>";
} catch (Exception $e) {
    echo "❌ Failed: " . htmlspecialchars($e->getMessage()) . "<br>";
}
