<?php
/**
 * Database Connection class handling dual PDO connections
 */
class Database {
    private static $host = 'localhost';
    private static $db_main_name = 'phichaia_student';
    private static $db_sports_name = 'school_sports_day';

    private static $db_main = null;
    private static $db_sports = null;

    /**
     * Helper to retrieve credentials dynamically based on environment
     */
    private static function getCredentials() {
        $is_local = in_array(
            $_SERVER['SERVER_NAME'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost',
            ['localhost', '127.0.0.1']
        ) || php_sapi_name() === 'cli';

        if ($is_local) {
            return [
                'username' => 'root',
                'password' => ''
            ];
        } else {
            return [
                'username' => 'phichaia_rpz',
                'password' => 'r9u06D#e9'
            ];
        }
    }

    /**
     * Establish and return connection to phichaia_student
     * 
     * @return PDO
     */
    public static function getMainConnection() {
        if (self::$db_main === null) {
            try {
                $creds = self::getCredentials();
                $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$db_main_name . ";charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];
                self::$db_main = new PDO($dsn, $creds['username'], $creds['password'], $options);
            } catch (PDOException $e) {
                die("Connection error for main database: " . $e->getMessage());
            }
        }
        return self::$db_main;
    }

    /**
     * Establish and return connection to school_sports_day
     * 
     * @return PDO
     */
    public static function getSportsConnection() {
        if (self::$db_sports === null) {
            try {
                $creds = self::getCredentials();
                $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$db_sports_name . ";charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];
                self::$db_sports = new PDO($dsn, $creds['username'], $creds['password'], $options);
            } catch (PDOException $e) {
                die("Connection error for sports database: " . $e->getMessage());
            }
        }
        return self::$db_sports;
    }
}

// Expose connections as global variables as requested
$db_main = Database::getMainConnection();
$db_sports = Database::getSportsConnection();
