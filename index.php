<?php
/**
 * SportDay Championship Portal
 * Front Controller & Route Orchestrator
 */

// Set local timezone for Thailand
date_default_timezone_set('Asia/Bangkok');

// Simple autoload mapping for MVC architecture classes
spl_autoload_register(function ($class_name) {
    $paths = [
        __DIR__ . '/config/',
        __DIR__ . '/models/',
        __DIR__ . '/presenters/',
        __DIR__ . '/controllers/'
    ];

    foreach ($paths as $path) {
        $file = $path . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Start user session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prevent conflicts with other applications sharing the localhost session cookie
if (isset($_SESSION['user']) && (!is_array($_SESSION['user']) || !isset($_SESSION['user']['role']))) {
    unset($_SESSION['user']);
}

try {
    // Route matching for modular controllers
    $route = filter_input(INPUT_GET, 'route', FILTER_SANITIZE_SPECIAL_CHARS);
    if (empty($route)) {
        $route = isset($_SESSION['user']) ? 'dashboard' : 'landing';
    }

    // Force login for all routes except 'login' and 'landing'
    if (!isset($_SESSION['user']) && !in_array($route, ['login', 'landing'])) {
        header('Location: index.php?route=landing');
        exit();
    }

    switch ($route) {
        case 'landing':
            $db_sports = Database::getSportsConnection();
            $db_main = Database::getMainConnection();
            $resultModel = new ResultModel($db_sports, $db_main);
            $matchModel = new MatchModel($db_sports, $db_main);
            $sportModel = new SportModel($db_sports);
            
            $leaderboard = $resultModel->getLeaderboard();
            $matches = $matchModel->getAllMatches();
            $sports = $sportModel->getAllSports();
            
            // Get results lookup for completed matches
            $matchResults = [];
            foreach ($matches as $match) {
                if ($match['status'] === 'Completed') {
                    $matchResults[$match['id']] = $resultModel->getMatchResults($match['id']);
                }
            }
            
            $presenter = new SportPresenter();
            require_once __DIR__ . '/views/landing.php';
            break;

        case 'login':
            $controller = new AuthController();
            $controller->handleRequest();
            break;

        case 'dashboard':
            // Direct to the correct dashboard based on role
            if ($_SESSION['user']['role'] === 'teacher') {
                $controller = new TeacherController();
            } else {
                $controller = new StudentController();
            }
            $controller->handleRequest();
            break;

        case 'leaderboard':
        case 'certificate':
            // Accessible to students and teachers via StudentController handles
            $controller = new StudentController();
            $controller->handleRequest();
            break;

        // AJAX helper routes (restricted to teachers)
        case 'search_student':
            if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
                header('HTTP/1.1 403 Forbidden');
                echo json_encode(['error' => 'Forbidden']);
                exit();
            }
            $controller = new TeacherController();
            $controller->handleRequest();
            break;

        case 'get_sport_regs':
            if (!isset($_SESSION['user'])) {
                header('HTTP/1.1 403 Forbidden');
                echo json_encode(['error' => 'Forbidden']);
                exit();
            }
            if ($_SESSION['user']['role'] === 'teacher') {
                $controller = new TeacherController();
            } else {
                $controller = new StudentController();
            }
            $controller->handleRequest();
            break;

        default:
            // Fallback to dashboard route
            header('Location: index.php?route=dashboard');
            exit();
    }
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo "<div style='font-family: sans-serif; background: #0f172a; color: #f8fafc; padding: 2rem; border-radius: 8px; margin: 2rem; border: 1px solid #334155;'>";
    echo "<h1 style='color: #ef4444;'>500 Internal Server Error</h1>";
    echo "<p>Something went wrong: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}
