<?php
/**
 * AuthController - Manages user authentication sessions, login submissions, and logouts
 */
class AuthController {
    private $db_main;
    private $authModel;

    public function __construct() {
        $this->db_main = Database::getMainConnection();
        $this->authModel = new AuthModel($this->db_main);
    }

    /**
     * Handle authentication requests
     */
    public function handleRequest() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $method = $_SERVER['REQUEST_METHOD'];
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);

        if ($action === 'logout') {
            $this->logout();
            return;
        }

        if ($method === 'POST') {
            $this->login();
        } else {
            $this->showLoginForm();
        }
    }

    /**
     * Process login form submission
     */
    private function login() {
        $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_SPECIAL_CHARS);
        $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS));
        $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);

        if (empty($username) || empty($password)) {
            UtilController::flashError('ข้อมูลไม่ครบถ้วน', 'กรุณากรอกรหัสและรหัสผ่านให้ครบถ้วน');
            header('Location: index.php?route=login');
            exit();
        }

        $user = false;
        if ($role === 'teacher') {
            $user = $this->authModel->authenticateTeacher($username, $password);
            if ($user) {
                $allowed_majors = ['คอมพิวเตอร์', 'สุขศึกษาและพลศึกษา'];
                if (!in_array($user['Teach_major'], $allowed_majors)) {
                    UtilController::flashError('เข้าสู่ระบบไม่สำเร็จ', 'เฉพาะครูกลุ่มสาระคอมพิวเตอร์ หรือ สุขศึกษาและพลศึกษา เท่านั้นที่สามารถเข้าสู่ระบบได้');
                    $_SESSION['old_username'] = $username;
                    $_SESSION['old_role'] = $role;
                    header('Location: index.php?route=login');
                    exit();
                }
            }
        } else {
            $user = $this->authModel->authenticateStudent($username, $password);
        }

        if ($user) {
            $_SESSION['user'] = $user;
            header('Location: index.php?route=dashboard');
            exit();
        } else {
            UtilController::flashError('เข้าสู่ระบบไม่สำเร็จ', 'รหัสประจำตัวหรือรหัสผ่านไม่ถูกต้อง');
            $_SESSION['old_username'] = $username;
            $_SESSION['old_role'] = $role;
            header('Location: index.php?route=login');
            exit();
        }
    }

    /**
     * Display the login page
     */
    private function showLoginForm() {
        // If already logged in, redirect directly to dashboard
        if (isset($_SESSION['user'])) {
            header('Location: index.php?route=dashboard');
            exit();
        }

        $old_username = isset($_SESSION['old_username']) ? $_SESSION['old_username'] : '';
        $old_role = isset($_SESSION['old_role']) ? $_SESSION['old_role'] : 'student';

        // Clear leftover session vars after reading
        unset($_SESSION['old_username']);
        unset($_SESSION['old_role']);
        // Note: auth_error flash is handled by UtilController::renderFlashJS() in the view

        require_once __DIR__ . '/../views/login.php';
    }

    /**
     * End user session and redirect
     */
    private function logout() {
        unset($_SESSION['user']);
        session_destroy();
        header('Location: index.php?route=login');
        exit();
    }
}
