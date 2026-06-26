<?php
/**
 * TeacherCertificateController - Manages certificate templates, positions, layouts, and previewing
 */
class TeacherCertificateController {
    private $db_main;
    private $db_sports;
    private $certificateModel;

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

        $this->certificateModel = new CertificateModel($this->db_sports, $this->db_main);
    }

    /**
     * Route and handle incoming requests
     */
    public function handleRequest() {
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST') {
            switch ($action) {
                case 'save_settings':
                    $this->saveSettings();
                    break;
                default:
                    $this->showDesigner();
                    break;
            }
        } else {
            switch ($action) {
                default:
                    $this->showDesigner();
                    break;
            }
        }
    }

    /**
     * Display the template designer dashboard
     */
    private function showDesigner() {
        $settings = $this->certificateModel->getActiveSettings();
        
        // If settings don't exist, retrieve or seed a basic array
        if (!$settings) {
            $settings = [
                'bg_style' => 'classic-gold',
                'border_color' => '#d4af37',
                'header_title' => '🏆 การแข่งขันกีฬาสีโรงเรียน ประจำปี 2569',
                'cert_title' => 'เกียรติบัตรเหรียญรางวัล',
                'body_pattern_1' => 'ได้เข้าร่วมการแข่งขันและสร้างผลงานอันยอดเยี่ยมรุ่งโรจน์ในนามสังกัด',
                'body_pattern_2' => 'ได้รับรางวัลชนะเลิศอันดับเกียรติยศสูงสุด',
                'body_pattern_3' => 'ในประเภทกีฬา {sport_name} (หมวดหมู่: {category})',
                'sig_left_title' => 'ผู้อำนวยการจัดการแข่งขัน',
                'sig_right_title' => 'ประธานสภากีฬาโรงเรียน',
                'layout_json' => json_encode([
                    "header_text" => [ "top" => 12, "fontSize" => 18, "color" => "#8a6d1c", "fontWeight" => "black" ],
                    "main_title"  => [ "top" => 20, "fontSize" => 36, "color" => "#1e293b", "fontWeight" => "black" ],
                    "prefix_text" => [ "top" => 36, "fontSize" => 14, "color" => "#64748b", "fontWeight" => "semibold" ],
                    "student_name" => [ "top" => 42, "fontSize" => 44, "color" => "#0f172a", "fontWeight" => "black" ],
                    "body_line1"  => [ "top" => 54, "fontSize" => 16, "color" => "#475569", "fontWeight" => "normal" ],
                    "medal_badge" => [ "top" => 62, "fontSize" => 18, "color" => "#8a6d1c", "fontWeight" => "black" ],
                    "body_line2"  => [ "top" => 72, "fontSize" => 16, "color" => "#475569", "fontWeight" => "normal" ],
                    "date_text"   => [ "top" => 80, "fontSize" => 12, "color" => "#64748b", "fontWeight" => "semibold" ],
                    "signatures"  => [ "top" => 86, "fontSize" => 12, "color" => "#64748b", "fontWeight" => "semibold" ],
                    "seal"        => [ "top" => 78, "scale" => 1.0 ]
                ])
            ];
        }

        $layout = json_decode($settings['layout_json'], true) ?: [];
        $winners = $this->certificateModel->getMedalWinners();
        $presenter = new SportPresenter();

        require_once __DIR__ . '/../views/teacher_certificate.php';
    }

    /**
     * Save certificate settings submitted via POST
     */
    private function saveSettings() {
        $bg_style = filter_input(INPUT_POST, 'bg_style', FILTER_SANITIZE_SPECIAL_CHARS) ?: 'classic-gold';
        $border_color = filter_input(INPUT_POST, 'border_color', FILTER_SANITIZE_SPECIAL_CHARS) ?: '#d4af37';
        $header_title = filter_input(INPUT_POST, 'header_title', FILTER_SANITIZE_SPECIAL_CHARS) ?: '🏆 การแข่งขันกีฬาสีโรงเรียน ประจำปี 2569';
        $cert_title = filter_input(INPUT_POST, 'cert_title', FILTER_SANITIZE_SPECIAL_CHARS) ?: 'เกียรติบัตรเหรียญรางวัล';
        $body_pattern_1 = filter_input(INPUT_POST, 'body_pattern_1', FILTER_SANITIZE_SPECIAL_CHARS) ?: '';
        $body_pattern_2 = filter_input(INPUT_POST, 'body_pattern_2', FILTER_SANITIZE_SPECIAL_CHARS) ?: '';
        $body_pattern_3 = filter_input(INPUT_POST, 'body_pattern_3', FILTER_SANITIZE_SPECIAL_CHARS) ?: '';
        $sig_left_title = filter_input(INPUT_POST, 'sig_left_title', FILTER_SANITIZE_SPECIAL_CHARS) ?: 'ผู้อำนวยการจัดการแข่งขัน';
        $sig_right_title = filter_input(INPUT_POST, 'sig_right_title', FILTER_SANITIZE_SPECIAL_CHARS) ?: 'ประธานสภากีฬาโรงเรียน';

        // Extract slider coordinate settings
        $positions = isset($_POST['pos']) ? $_POST['pos'] : [];
        $layout = [];
        foreach ($positions as $key => $vals) {
            $layout[$key] = [
                'top' => isset($vals['top']) ? intval($vals['top']) : 0,
                'fontSize' => isset($vals['fontSize']) ? intval($vals['fontSize']) : 12,
                'color' => isset($vals['color']) ? $vals['color'] : '#000000',
                'fontWeight' => isset($vals['fontWeight']) ? $vals['fontWeight'] : 'normal'
            ];
        }
        
        // Handle seal separately since it uses scale
        if (isset($_POST['seal_top'])) {
            $layout['seal'] = [
                'top' => intval($_POST['seal_top']),
                'scale' => isset($_POST['seal_scale']) ? floatval($_POST['seal_scale']) : 1.0
            ];
        }

        $data = [
            'bg_style' => $bg_style,
            'border_color' => $border_color,
            'header_title' => $header_title,
            'cert_title' => $cert_title,
            'body_pattern_1' => $body_pattern_1,
            'body_pattern_2' => $body_pattern_2,
            'body_pattern_3' => $body_pattern_3,
            'sig_left_title' => $sig_left_title,
            'sig_right_title' => $sig_right_title,
            'layout_json' => json_encode($layout)
        ];

        try {
            if ($this->certificateModel->saveSettings($data)) {
                UtilController::flashSuccess('บันทึกสำเร็จ', 'อัปเดตเทมเพลตและตำแหน่งเกียรติบัตรเรียบร้อยแล้ว');
            } else {
                UtilController::flashError('เกิดข้อผิดพลาด', 'ไม่สามารถบันทึกการตั้งค่าได้');
            }
        } catch (Exception $e) {
            UtilController::flashError('เกิดข้อผิดพลาด', $e->getMessage());
        }

        header('Location: index.php?route=teacher_certificate');
        exit();
    }
}
