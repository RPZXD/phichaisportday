<?php
/**
 * StudentController - Manages the student dashboard, leaderboard standings, and certificate generation
 */
class StudentController {
    private $db_main;
    private $db_sports;
    private $athleteModel;
    private $matchModel;
    private $resultModel;
    private $sportModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Enforce Authentication
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?route=login');
            exit();
        }

        $this->db_main = Database::getMainConnection();
        $this->db_sports = Database::getSportsConnection();

        $this->athleteModel = new AthleteModel($this->db_sports, $this->db_main);
        $this->matchModel = new MatchModel($this->db_sports, $this->db_main);
        $this->resultModel = new ResultModel($this->db_sports, $this->db_main);
        $this->sportModel = new SportModel($this->db_sports);
    }

    /**
     * Route and handle incoming requests
     */
    public function handleRequest() {
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
        $route = filter_input(INPUT_GET, 'route', FILTER_SANITIZE_SPECIAL_CHARS);
        $method = $_SERVER['REQUEST_METHOD'];

        if (empty($action) && in_array($route, ['certificate', 'leaderboard', 'get_sport_regs'])) {
            $action = $route;
        }

        if ($method === 'POST') {
            switch ($action) {
                case 'register_event':
                    $this->registerAthleteToEvent();
                    break;
                case 'remove_event_reg':
                    $this->removeAthleteFromEvent();
                    break;
                default:
                    $this->showDashboard();
                    break;
            }
        } else {
            switch ($action) {
                case 'certificate':
                    $this->generateCertificate();
                    break;
                case 'leaderboard':
                    $this->showLeaderboard();
                    break;
                case 'get_sport_regs':
                    $this->getSportRegistrationsJson();
                    break;
                default:
                    $this->showDashboard();
                    break;
            }
        }
    }

    /**
     * Show student dashboard containing standings, personal profile, and medals
     */
    private function showDashboard() {
        $student_id = $_SESSION['user']['id'];
        $student_name = $_SESSION['user']['name'];

        // Get student's house details dynamically based on their classroom
        $athlete = $this->athleteModel->getStudentHouse($student_id);
        
        // Check if student is appointed as a color representative
        $isRepresentative = $this->athleteModel->isRepresentative($student_id);

        $registrations = $this->matchModel->getStudentRegistrations($student_id);
        $medals = $this->resultModel->getAthleteMedals($student_id);
        $colorAthletes = [];
        $sports = [];

        if ($athlete && $isRepresentative) {
            // Get all sports for enrollment dropdown
            $sports = $this->sportModel->getAllSports();

            // Get all active students belonging to the same house color
            $colorAthletes = $this->athleteModel->getStudentsByHouse($athlete['house_id']);
        }

        // Leaderboard standings
        $leaderboard = $this->resultModel->getLeaderboard();

        // Get recent scheduled matches
        $matches = $this->matchModel->getAllMatches();

        // Flash messages are read and rendered by UtilController::renderFlashJS() in the view.

        $presenter = new SportPresenter();

        require_once __DIR__ . '/../views/student_dashboard.php';
    }

    /**
     * Show leaderboard standings
     */
    private function showLeaderboard() {
        $leaderboard = $this->resultModel->getLeaderboard();
        $presenter = new SportPresenter();
        require_once __DIR__ . '/../views/leaderboard.php';
    }

    /**
     * Generate HTML/CSS certificate view for printing
     */
    private function generateCertificate() {
        $result_id = filter_input(INPUT_GET, 'result_id', FILTER_VALIDATE_INT);
        $student_id = $_SESSION['user']['id'];

        if (!$result_id) {
            UtilController::flashError('ข้อมูลไม่ถูกต้อง', 'ไม่พบข้อมูลใบประกาศนีเกียรติยศ');
            header('Location: index.php?route=dashboard');
            exit();
        }

        $certificate = $this->resultModel->getCertificateDetails($result_id, $student_id);

        if (!$certificate) {
            UtilController::flashError('ไม่พบใบประกาศนียศ', 'ไม่พบใบประกาศนียศหรือไม่มีสิทธิ์เข้าถึง');
            header('Location: index.php?route=dashboard');
            exit();
        }

        $presenter = new SportPresenter();

        require_once __DIR__ . '/../views/certificate.php';
    }

    /**
     * Register athlete of the same house to a sport event
     */
    private function registerAthleteToEvent() {
        $student_id = $_SESSION['user']['id'];
        
        // Security check: Verify the logged-in student is a representative
        $is_rep = $this->athleteModel->isRepresentative($student_id);
        if (!$is_rep) {
            UtilController::flashError('ไม่มีสิทธิ์', 'คุณไม่มีสิทธิ์ลงทะเบียนนักกีฬา');
            header('Location: index.php?route=dashboard');
            exit();
        }

        $rep_details = $this->athleteModel->getRepresentativeByStudentId($student_id);
        if (!$rep_details) {
            UtilController::flashError('ไม่พบข้อมูล', 'ไม่พบข้อมูลตัวแทนสีของคุณ');
            header('Location: index.php?route=dashboard');
            exit();
        }

        $sport_id = filter_input(INPUT_POST, 'sport_id', FILTER_VALIDATE_INT);

        // Collect and sanitize the array of athlete IDs
        $raw_ids = isset($_POST['sports_day_athlete_id']) ? (array)$_POST['sports_day_athlete_id'] : [];
        $target_athlete_ids = array_values(array_filter(array_map('trim', $raw_ids)));

        if (!$sport_id || empty($target_athlete_ids)) {
            UtilController::flashError('ข้อมูลไม่ครบ', 'กรุณาเลือกประเภทกีฬาและนักกีฬาอย่างน้อย 1 คน');
            header('Location: index.php?route=dashboard');
            exit();
        }

        $successCount = 0;
        $failHouse    = 0;
        $failDup      = 0;

        foreach ($target_athlete_ids as $target_athlete_id) {
            // Security: must belong to same house
            $target_student_house = $this->athleteModel->getStudentHouse($target_athlete_id);
            if (!$target_student_house || $target_student_house['house_id'] !== $rep_details['house_id']) {
                $failHouse++;
                continue;
            }
            if ($this->matchModel->registerAthleteToSport($target_athlete_id, $sport_id)) {
                $successCount++;
            } else {
                $failDup++;
            }
        }

        $total = count($target_athlete_ids);

        if ($successCount === $total) {
            $msg = $total === 1
                ? 'ลงทะเบียนเข้าแข่งขันกีฬาเรียบร้อยแล้ว'
                : "ลงทะเบียนนักกีฬาทั้ง {$total} คน เรียบร้อยแล้ว";
            UtilController::flashSuccess('ลงทะเบียนสำเร็จ', $msg);
        } elseif ($successCount > 0) {
            UtilController::flashWarning('ลงทะเบียนบางส่วนสำเร็จ',
                "สำเร็จ {$successCount} คน" .
                ($failDup   > 0 ? " • ซ้ำซ้อน {$failDup} คน" : '') .
                ($failHouse > 0 ? " • คณะสีไม่ตรง {$failHouse} คน" : ''));
        } else {
            UtilController::flashError('ไม่สามารถลงทะเบียนได้',
                $failHouse > 0
                    ? 'นักกีฬาบางคนไม่ได้อยู่ในคณะสีของคุณ'
                    : 'นักกีฬาทุกคนอาจลงสมัครในรายการนี้แล้ว');
        }

        header('Location: index.php?route=dashboard');
        exit();
    }


    /**
     * Remove athlete registration from sport
     */
    private function removeAthleteFromEvent() {
        $student_id = $_SESSION['user']['id'];
        
        // Security check: Verify the logged-in student is a representative
        $is_rep = $this->athleteModel->isRepresentative($student_id);
        if (!$is_rep) {
            UtilController::flashError('ไม่มีสิทธิ์', 'คุณไม่มีสิทธิ์จัดการข้อมูลนักกีฬา');
            header('Location: index.php?route=dashboard');
            exit();
        }

        $rep_details = $this->athleteModel->getRepresentativeByStudentId($student_id);
        if (!$rep_details) {
            UtilController::flashError('ไม่พบข้อมูล', 'ไม่พบข้อมูลตัวแทนสีของคุณ');
            header('Location: index.php?route=dashboard');
            exit();
        }

        $reg_id = filter_input(INPUT_POST, 'registration_id', FILTER_VALIDATE_INT);
        if (!$reg_id) {
            UtilController::flashError('ข้อมูลไม่ถูกต้อง', 'ข้อมูลการสมัครไม่ถูกต้อง');
            header('Location: index.php?route=dashboard');
            exit();
        }

        // Security check: Get target student ID from event registration
        $stmt = $this->db_sports->prepare("
            SELECT student_id FROM event_registrations WHERE id = :id
        ");
        $stmt->execute([':id' => $reg_id]);
        $target_student_id = $stmt->fetchColumn();

        if (!$target_student_id) {
            UtilController::flashError('ไม่พบข้อมูล', 'ไม่พบข้อมูลการลงทะเบียน');
            header('Location: index.php?route=dashboard');
            exit();
        }

        // Get target student's dynamic house and verify matches representative's house
        $target_student_house = $this->athleteModel->getStudentHouse($target_student_id);
        if (!$target_student_house || $target_student_house['house_id'] != $rep_details['house_id']) {
            UtilController::flashError('คณะสีไม่ตรง', 'คุณสามารถถอนตัวนักกีฬาได้เฉพาะในคณะสีของตัวเองเท่านั้น');
            header('Location: index.php?route=dashboard');
            exit();
        }

        if ($this->matchModel->removeAthleteFromSport($reg_id)) {
            UtilController::flashSuccess('ถอนตัวสำเร็จ', 'ถอนตัวนักกีฬาออกจากการแข่งขันเรียบร้อยแล้ว');
        } else {
            UtilController::flashError('ไม่สามารถถอนตัวได้', 'ไม่สามารถถอนตัวนักกีฬาได้');
        }

        header('Location: index.php?route=dashboard');
        exit();
    }

    /**
     * Get registrations for a sport event (JSON Response)
     */
    private function getSportRegistrationsJson() {
        $sport_id = filter_input(INPUT_GET, 'sport_id', FILTER_VALIDATE_INT);
        if (!$sport_id) {
            echo json_encode([]);
            exit();
        }
        $regs = $this->matchModel->getSportRegistrations($sport_id);
        header('Content-Type: application/json');
        echo json_encode($regs);
        exit();
    }
}
