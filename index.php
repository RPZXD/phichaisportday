<?php
/**
 * SportDay Championship Portal
 * Front Controller & Route Orchestrator
 */

// Enable output buffering & Gzip compression to fit ModSecurity limit (<1MB)
if (!ob_start("ob_gzhandler")) {
    ob_start();
}

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

    $publicRoutes = ['login', 'landing', 'leaderboard', 'schedule', 'brackets', 'houses', 'certificates', 'public_certificate'];

    // Force login for routes that are not public
    if (!isset($_SESSION['user']) && !in_array($route, $publicRoutes)) {
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
            $certificateModel = new CertificateModel($db_sports, $db_main);
            
            $leaderboard = $resultModel->getLeaderboard();
            $matches = $matchModel->getAllMatches();
            $sports = $sportModel->getAllSports();
            $certificatesList = $certificateModel->getCanvaCertificatesList(4329, '2569');
            
            $presenter = new SportPresenter();
            require_once __DIR__ . '/views/landing.php';
            break;

        case 'leaderboard':
            $db_sports = Database::getSportsConnection();
            $db_main = Database::getMainConnection();
            $resultModel = new ResultModel($db_sports, $db_main);
            
            $leaderboard = $resultModel->getLeaderboard();
            $presenter = new SportPresenter();
            require_once __DIR__ . '/views/public_leaderboard.php';
            break;

        case 'schedule':
            $db_sports = Database::getSportsConnection();
            $db_main = Database::getMainConnection();
            $matchModel = new MatchModel($db_sports, $db_main);
            $resultModel = new ResultModel($db_sports, $db_main);
            
            $matches = $matchModel->getAllMatches();
            $matchResults = [];
            foreach ($matches as $match) {
                if ($match['status'] === 'Completed') {
                    $matchResults[$match['id']] = $resultModel->getMatchResults($match['id']);
                }
            }
            
            $presenter = new SportPresenter();
            require_once __DIR__ . '/views/public_schedule.php';
            break;

        case 'brackets':
            $db_sports = Database::getSportsConnection();
            $db_main = Database::getMainConnection();
            $bracketModel = new BracketModel($db_sports);
            $resultModel = new ResultModel($db_sports, $db_main);
            $matchModel = new MatchModel($db_sports, $db_main);

            $active_brackets = $bracketModel->getAllActiveBrackets();
            $matches = $matchModel->getAllMatches();
            $matchResults = [];
            foreach ($matches as $match) {
                if ($match['status'] === 'Completed') {
                    $matchResults[$match['id']] = $resultModel->getMatchResults($match['id']);
                }
            }

            $presenter = new SportPresenter();
            require_once __DIR__ . '/views/public_brackets.php';
            break;

        case 'houses':
            $db_sports = Database::getSportsConnection();
            $db_main = Database::getMainConnection();
            $resultModel = new ResultModel($db_sports, $db_main);
            
            $leaderboard = $resultModel->getLeaderboard();
            $presenter = new SportPresenter();
            require_once __DIR__ . '/views/public_houses.php';
            break;

        case 'certificates':
            $db_sports = Database::getSportsConnection();
            $db_main = Database::getMainConnection();
            $certificateModel = new CertificateModel($db_sports, $db_main);
            
            $certificatesList = $certificateModel->getCanvaCertificatesList(4329, '2569');
            $presenter = new SportPresenter();
            require_once __DIR__ . '/views/public_certificates.php';
            break;

        case 'public_certificate':
            $db_sports = Database::getSportsConnection();
            $db_main = Database::getMainConnection();
            $resultModel = new ResultModel($db_sports, $db_main);
            $certificateModel = new CertificateModel($db_sports, $db_main);

            $result_id = filter_input(INPUT_GET, 'result_id', FILTER_VALIDATE_INT);
            $student_id = filter_input(INPUT_GET, 'student_id', FILTER_SANITIZE_SPECIAL_CHARS);
            $certNo = filter_input(INPUT_GET, 'cert_no', FILTER_SANITIZE_SPECIAL_CHARS);

            if (!$result_id || !$student_id) {
                header('Location: index.php?route=certificates');
                exit();
            }

            $certificate = $resultModel->getCertificateDetails($result_id, $student_id);
            if (!$certificate) {
                header('Location: index.php?route=certificates');
                exit();
            }

            if (empty($certNo)) {
                $certNo = '4329/2569';
            }

            $settings = $certificateModel->getActiveSettings();
            $layout = [];
            $presenter = new SportPresenter();
            require_once __DIR__ . '/views/certificate.php';
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

        case 'teacher_certificate':
            if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
                header('Location: index.php?route=dashboard');
                exit();
            }
            $controller = new TeacherCertificateController();
            $controller->handleRequest();
            break;

        case 'certificate':
            // Accessible to students and teachers via StudentController
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
} catch (Throwable $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo "<div style='font-family: sans-serif; background: #0f172a; color: #f8fafc; padding: 2rem; border-radius: 8px; margin: 2rem; border: 1px solid #334155;'>";
    echo "<h1 style='color: #ef4444;'>500 Internal Server Error</h1>";
    echo "<p>Something went wrong: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}
