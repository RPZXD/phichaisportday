<?php
/**
 * TeacherController - Manages teacher operations including classroom assignments, representative appointments, match schedules, and points logging
 */
class TeacherController {
    private $db_main;
    private $db_sports;
    private $athleteModel;
    private $houseModel;
    private $sportModel;
    private $matchModel;
    private $resultModel;
    private $bracketModel;

    public function __construct() {
        // Enforce Authentication and Role check
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
            header('Location: index.php?route=login');
            exit();
        }

        $this->db_main = Database::getMainConnection();
        $this->db_sports = Database::getSportsConnection();

        $this->athleteModel = new AthleteModel($this->db_sports, $this->db_main);
        $this->houseModel = new HouseModel($this->db_sports);
        $this->sportModel = new SportModel($this->db_sports);
        $this->matchModel = new MatchModel($this->db_sports, $this->db_main);
        $this->resultModel = new ResultModel($this->db_sports, $this->db_main);
        $this->bracketModel = new BracketModel($this->db_sports);
    }

    /**
     * Route and handle incoming requests
     */
    public function handleRequest() {
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
        $route = filter_input(INPUT_GET, 'route', FILTER_SANITIZE_SPECIAL_CHARS);
        $method = $_SERVER['REQUEST_METHOD'];

        if (empty($action) && in_array($route, ['search_student', 'get_sport_regs'])) {
            $action = $route;
        }

        if ($method === 'POST') {
            switch ($action) {
                case 'register_athlete': // repurposed for appointing representative
                    $this->registerAthlete();
                    break;
                case 'delete_athlete': // repurposed for deleting representative
                    $this->deleteAthlete();
                    break;
                case 'assign_classroom':
                    $this->assignClassroom();
                    break;
                case 'delete_classroom':
                    $this->deleteClassroom();
                    break;
                case 'add_sport':
                    $this->addSport();
                    break;
                case 'create_match':
                    $this->createMatch();
                    break;
                case 'delete_match':
                    $this->deleteMatch();
                    break;
                case 'record_result':
                    $this->recordMatchResult();
                    break;
                case 'create_bracket':
                    $this->createBracket();
                    break;
                case 'reset_bracket':
                    $this->resetBracket();
                    break;
                case 'record_bracket_result':
                    $this->recordBracketResult();
                    break;
                case 'update_match_date':
                    $this->updateMatchDate();
                    break;
                case 'record_sport_results':
                    $this->recordSportResults();
                    break;
                default:
                    $this->showDashboard();
                    break;
            }
        } else {
            switch ($action) {
                case 'search_student':
                    $this->searchStudent();
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
     * Show main dashboard with houses, sports, athletes, matches, etc.
     */
    private function showDashboard() {
        $houses = $this->houseModel->getAllHouses();
        $sports = $this->sportModel->getAllSports();
        $representatives = $this->athleteModel->getAllRepresentatives();
        $classroom_mappings = $this->houseModel->getClassroomMappings();
        $matches = $this->matchModel->getAllMatches();

        // Get results lookup for completed matches
        $matchResults = [];
        foreach ($matches as $match) {
            if ($match['status'] === 'Completed') {
                $matchResults[$match['id']] = $this->resultModel->getMatchResults($match['id']);
            }
        }

        // Get final medals lookup for each sport
        $sportMedals = $this->resultModel->getAllSportMedals();

        // Generate Leaderboard
        $leaderboard = $this->resultModel->getLeaderboard();

        // Get single elimination tournament brackets
        $selected_sport_id = filter_input(INPUT_GET, 'bracket_sport_id', FILTER_VALIDATE_INT);
        if (!$selected_sport_id && !empty($sports)) {
            $selected_sport_id = $sports[0]['id'];
        }
        $brackets = [];
        if ($selected_sport_id) {
            $brackets = $this->bracketModel->getBracketsBySport($selected_sport_id);
        }

        // Flash messages are read and rendered by UtilController::renderFlashJS() in the view.

        $presenter = new SportPresenter();

        require_once __DIR__ . '/../views/teacher_dashboard.php';
    }

    private function searchStudent() {
        $q = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_SPECIAL_CHARS);
        $house_id = filter_input(INPUT_GET, 'house_id', FILTER_VALIDATE_INT) ?: null;
        if (empty($q) || strlen($q) < 2) {
            echo json_encode([]);
            exit();
        }
        $students = $this->athleteModel->searchStudents($q, $house_id);
        header('Content-Type: application/json');
        echo json_encode($students);
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

    /**
     * Appoint a student as a color representative (repurposed from registerAthlete)
     */
    private function registerAthlete() {
        $student_id = trim(filter_input(INPUT_POST, 'student_id', FILTER_SANITIZE_SPECIAL_CHARS));
        $house_id = filter_input(INPUT_POST, 'house_id', FILTER_VALIDATE_INT);

        if (empty($student_id) || !$house_id) {
            UtilController::flashError('ข้อมูลไม่ครบถ้วน', 'ระบุรหัสนักเรียนหรือเลือกคณะสีไม่ครบถ้วน');
        } else {
            // Check if student belongs to this color by classroom mapping
            $student_house = $this->athleteModel->getStudentHouse($student_id);
            if (!$student_house) {
                UtilController::flashError('ไม่พบนักเรียน', 'ไม่พบข้อมูลนักเรียนคนนี้ หรือไม่มีห้องเรียนที่จัดสังกัดคณะสี');
            } elseif ($student_house['house_id'] != $house_id) {
                UtilController::flashError('คณะสีไม่ตรง', 'นักเรียนคนนี้จัดอยู่คณะ ' . $student_house['house_name'] . ' ตามห้องเรียน ไม่สามารถแต่งตั้งเป็นตัวแทนคณะสีอื่นได้');
            } else {
                $result = $this->athleteModel->appointRepresentative($student_id, $house_id);
                if ($result) {
                    UtilController::flashSuccess('แต่งตั้งสำเร็จ', 'แต่งตั้งตัวแทนคณะสีสำเร็จเรียบร้อยแล้ว');
                } else {
                    UtilController::flashError('ไม่สามารถแต่งตั้งได้', 'นักเรียนอาจได้รับการแต่งตั้งเป็นตัวแทนแล้ว');
                }
            }
        }
        header('Location: index.php?route=dashboard');
        exit();
    }

    /**
     * Remove representative appointment (repurposed from deleteAthlete)
     */
    private function deleteAthlete() {
        $athlete_id = filter_input(INPUT_POST, 'athlete_id', FILTER_VALIDATE_INT);
        if ($athlete_id) {
            if ($this->athleteModel->deleteRepresentative($athlete_id)) {
                UtilController::flashSuccess('ถอดถอนสำเร็จ', 'ถอดถอนตำแหน่งตัวแทนคณะสีเรียบร้อยแล้ว');
            } else {
                UtilController::flashError('เกิดข้อผิดพลาด', 'ไม่สามารถถอดถอนตัวแทนคณะสีได้');
            }
        }
        header('Location: index.php?route=dashboard');
        exit();
    }

    /**
     * Assign a classroom to a house color
     */
    private function assignClassroom() {
        $grade_level = filter_input(INPUT_POST, 'grade_level', FILTER_VALIDATE_INT);
        $room_number = filter_input(INPUT_POST, 'room_number', FILTER_VALIDATE_INT);
        $house_id = filter_input(INPUT_POST, 'house_id', FILTER_VALIDATE_INT);

        if (!$grade_level || !$room_number || !$house_id) {
            UtilController::flashError('ข้อมูลไม่ครบถ้วน', 'กรุณากรอกข้อมูลระดับชั้น ห้องเรียน และคณะสีให้ครบถ้วน');
        } else {
            if ($this->houseModel->assignClassroom($grade_level, $room_number, $house_id)) {
                UtilController::flashSuccess('บันทึกสำเร็จ', "บันทึกการจัดคณะสีให้ห้องเรียน ม.{$grade_level}/{$room_number} สำเร็จเรียบร้อยแล้ว");
            } else {
                UtilController::flashError('เกิดข้อผิดพลาด', 'ไม่สามารถจัดห้องเรียนเข้าคณะสีได้');
            }
        }
        header('Location: index.php?route=dashboard');
        exit();
    }

    /**
     * Delete classroom mapping
     */
    private function deleteClassroom() {
        $mapping_id = filter_input(INPUT_POST, 'mapping_id', FILTER_VALIDATE_INT);
        if ($mapping_id) {
            if ($this->houseModel->deleteClassroom($mapping_id)) {
                UtilController::flashSuccess('ยกเลิกสำเร็จ', 'ยกเลิกการจัดคณะสีของห้องเรียนเรียบร้อยแล้ว');
            } else {
                UtilController::flashError('เกิดข้อผิดพลาด', 'ไม่สามารถยกเลิกการจัดคณะสีของห้องเรียนได้');
            }
        }
        header('Location: index.php?route=dashboard');
        exit();
    }

    /**
     * Create/Add a new sport
     */
    private function addSport() {
        $sport_name = trim(filter_input(INPUT_POST, 'sport_name', FILTER_SANITIZE_SPECIAL_CHARS));
        $category = trim(filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS));

        if (empty($sport_name) || empty($category)) {
            UtilController::flashError('ข้อมูลไม่ครบถ้วน', 'กรุณากรอกชื่อกีฬาและหมวดหมู่ให้ครบถ้วน');
        } else {
            if ($this->sportModel->addSport($sport_name, $category)) {
                UtilController::flashSuccess('เพิ่มกีฬาสำเร็จ', 'เพิ่มประเภทกีฬาใหม่เรียบร้อยแล้ว');
            } else {
                UtilController::flashError('เกิดข้อผิดพลาด', 'ไม่สามารถเพิ่มกีฬาได้');
            }
        }
        header('Location: index.php?route=dashboard');
        exit();
    }

    /**
     * Schedule a new match event
     */
    private function createMatch() {
        $sport_id = filter_input(INPUT_POST, 'sport_id', FILTER_VALIDATE_INT);
        $event_date = filter_input(INPUT_POST, 'event_date', FILTER_SANITIZE_SPECIAL_CHARS) ?: date('Y-m-d H:i:s');

        if (!$sport_id) {
            UtilController::flashError('ข้อมูลไม่ครบถ้วน', 'กรุณาเลือกประเภทกีฬา');
        } else {
            $formatted_date = date('Y-m-d H:i:s', strtotime($event_date));
            if ($this->matchModel->createMatch($sport_id, $formatted_date)) {
                UtilController::flashSuccess('กำหนดการแข่งขันสำเร็จ', 'บันทึกตารางการแข่งขันเรียบร้อยแล้ว');
            } else {
                UtilController::flashError('เกิดข้อผิดพลาด', 'ไม่สามารถบันทึกตารางการแข่งขันได้');
            }
        }
        header('Location: index.php?route=dashboard&tab=matches-tab');
        exit();
    }

    /**
     * Delete scheduled match event
     */
    private function deleteMatch() {
        $match_id = filter_input(INPUT_POST, 'match_id', FILTER_VALIDATE_INT);
        if ($match_id) {
            if ($this->matchModel->deleteMatch($match_id)) {
                UtilController::flashSuccess('ลบสำเร็จ', 'ลบรายการแข่งขันเรียบร้อยแล้ว');
            } else {
                UtilController::flashError('เกิดข้อผิดพลาด', 'ไม่สามารถลบรายการแข่งขันได้');
            }
        }
        header('Location: index.php?route=dashboard');
        exit();
    }

    /**
     * Record score points and medals for a match and close the event
     */
    private function recordMatchResult() {
        $match_id = filter_input(INPUT_POST, 'match_id', FILTER_VALIDATE_INT);
        $points_data = isset($_POST['points']) ? $_POST['points'] : [];
        $medal_data = isset($_POST['medal']) ? $_POST['medal'] : [];

        if (!$match_id) {
            UtilController::flashError('ข้อมูลไม่ถูกต้อง', 'ไม่พบรหัสการแข่งขัน');
            header('Location: index.php?route=dashboard');
            exit();
        }

        try {
            // Transaction-like recording loop
            foreach ($points_data as $house_id => $points) {
                $points = intval($points);
                $medal = isset($medal_data[$house_id]) ? $medal_data[$house_id] : null;
                $this->resultModel->saveResult($match_id, $house_id, $points, $medal);
            }

            // Set match status to Completed
            $this->matchModel->updateMatchStatus($match_id, 'Completed');
            UtilController::flashSuccess('บันทึกผลสำเร็จ', 'บันทึกผลการแข่งขันและปิดรายการเรียบร้อยแล้ว');
        } catch (Exception $e) {
            UtilController::flashError('เกิดข้อผิดพลาด', 'ไม่สามารถบันทึกผลได้: ' . $e->getMessage());
        }

        header('Location: index.php?route=dashboard');
        exit();
    }

    /**
     * Create bracket for selected sport
     */
    private function createBracket() {
        $sport_id = filter_input(INPUT_POST, 'sport_id', FILTER_VALIDATE_INT);
        $teams = isset($_POST['teams']) ? $_POST['teams'] : [];

        if (!$sport_id || count($teams) < 6) {
            UtilController::flashError('ข้อมูลไม่ครบถ้วน', 'กรุณาระบุชนิดกีฬาและทีมคณะสีแข่งขันให้ครบ 6 ทีม');
        } else {
            try {
                if ($this->bracketModel->create6TeamBracket($sport_id, $teams)) {
                    UtilController::flashSuccess('สร้างสำเร็จ', 'สร้างสายการแข่งขันแบบแพ้คัดออกสำเร็จแล้ว');
                } else {
                    UtilController::flashError('เกิดข้อผิดพลาด', 'สายการแข่งขันของกีฬานี้ถูกสร้างไว้แล้ว');
                }
            } catch (Exception $e) {
                UtilController::flashError('เกิดข้อผิดพลาด', $e->getMessage());
            }
        }
        header('Location: index.php?route=dashboard&bracket_sport_id=' . $sport_id . '&tab=bracket-tab');
        exit();
    }

    /**
     * Reset bracket for selected sport
     */
    private function resetBracket() {
        $sport_id = filter_input(INPUT_POST, 'sport_id', FILTER_VALIDATE_INT);

        if (!$sport_id) {
            UtilController::flashError('ข้อมูลไม่ถูกต้อง', 'ไม่พบรหัสชนิดกีฬา');
        } else {
            try {
                if ($this->bracketModel->resetBracket($sport_id)) {
                    UtilController::flashSuccess('ล้างข้อมูลสำเร็จ', 'ล้างข้อมูลสายการแข่งขันและผลลัพธ์ที่เกี่ยวข้องเรียบร้อยแล้ว');
                } else {
                    UtilController::flashError('เกิดข้อผิดพลาด', 'ไม่สามารถล้างข้อมูลได้');
                }
            } catch (Exception $e) {
                UtilController::flashError('เกิดข้อผิดพลาด', $e->getMessage());
            }
        }
        header('Location: index.php?route=dashboard&bracket_sport_id=' . $sport_id . '&tab=bracket-tab');
        exit();
    }

    /**
     * Record score/winner for bracket match
     */
    private function recordBracketResult() {
        $bracket_id = filter_input(INPUT_POST, 'bracket_id', FILTER_VALIDATE_INT);
        $sport_id = filter_input(INPUT_POST, 'sport_id', FILTER_VALIDATE_INT);
        $team1_score = filter_input(INPUT_POST, 'team1_score', FILTER_VALIDATE_INT);
        $team2_score = filter_input(INPUT_POST, 'team2_score', FILTER_VALIDATE_INT);
        $winner_house_id = filter_input(INPUT_POST, 'winner_house_id', FILTER_VALIDATE_INT);
        $points_winner = filter_input(INPUT_POST, 'points_winner', FILTER_VALIDATE_INT) ?: 0;
        $points_loser = filter_input(INPUT_POST, 'points_loser', FILTER_VALIDATE_INT) ?: 0;

        if (!$bracket_id || $team1_score === null || $team2_score === null || !$winner_house_id) {
            UtilController::flashError('ข้อมูลไม่ครบถ้วน', 'กรุณาระบุคะแนนดิบและเลือกผู้ชนะ');
        } else {
            try {
                if ($this->bracketModel->recordBracketResult($bracket_id, $team1_score, $team2_score, $winner_house_id, $points_winner, $points_loser)) {
                    UtilController::flashSuccess('บันทึกผลสำเร็จ', 'บันทึกคะแนนดิบและเลื่อนทีมผู้ชนะเข้ารอบถัดไปเรียบร้อยแล้ว');
                } else {
                    UtilController::flashError('เกิดข้อผิดพลาด', 'ไม่สามารถบันทึกผลการแข่งขันได้');
                }
            } catch (Exception $e) {
                UtilController::flashError('เกิดข้อผิดพลาด', $e->getMessage());
            }
        }
        header('Location: index.php?route=dashboard&bracket_sport_id=' . $sport_id . '&tab=bracket-tab');
        exit();
    }

    /**
     * Update scheduled match date/time
     */
    private function updateMatchDate() {
        $match_id = filter_input(INPUT_POST, 'match_id', FILTER_VALIDATE_INT);
        $event_date = filter_input(INPUT_POST, 'event_date', FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$match_id || empty($event_date)) {
            UtilController::flashError('ข้อมูลไม่ครบถ้วน', 'กรุณาระบุรหัสการแข่งขันและวันเวลาใหม่');
        } else {
            $formatted_date = date('Y-m-d H:i:s', strtotime($event_date));
            if ($this->matchModel->updateMatchDate($match_id, $formatted_date)) {
                UtilController::flashSuccess('แก้ไขสำเร็จ', 'แก้ไขกำหนดการแข่งขันเรียบร้อยแล้ว');
            } else {
                UtilController::flashError('เกิดข้อผิดพลาด', 'ไม่สามารถแก้ไขวันเวลาได้');
            }
        }
        
        header('Location: index.php?route=dashboard&tab=matches-tab');
        exit();
    }

    /**
     * Record final sport results (Gold, Silver, Bronze)
     */
    private function recordSportResults() {
        $sport_id = filter_input(INPUT_POST, 'sport_id', FILTER_VALIDATE_INT);
        $gold_house_id = filter_input(INPUT_POST, 'gold_house_id', FILTER_VALIDATE_INT) ?: null;
        $silver_house_id = filter_input(INPUT_POST, 'silver_house_id', FILTER_VALIDATE_INT) ?: null;
        $bronze_house_id = filter_input(INPUT_POST, 'bronze_house_id', FILTER_VALIDATE_INT) ?: null;

        if (!$sport_id) {
            UtilController::flashError('ข้อมูลไม่ครบถ้วน', 'กรุณาระบุชนิดกีฬา');
        } else {
            if ($this->resultModel->saveSportFinalResults($sport_id, $gold_house_id, $silver_house_id, $bronze_house_id)) {
                UtilController::flashSuccess('บันทึกผลสำเร็จ', 'บันทึกคะแนนและเหรียญรางวัลของกีฬาเรียบร้อยแล้ว');
            } else {
                UtilController::flashError('เกิดข้อผิดพลาด', 'ไม่สามารถบันทึกผลรางวัลได้');
            }
        }
        header('Location: index.php?route=dashboard&tab=matches-tab');
        exit();
    }
}
