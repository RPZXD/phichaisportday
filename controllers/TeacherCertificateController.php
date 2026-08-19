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
                case 'export_canva_csv':
                    $this->exportCanvaCsv();
                    break;
                case 'bulk_print':
                    $this->bulkPrint();
                    break;
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
        $start_no = filter_input(INPUT_GET, 'start_no', FILTER_VALIDATE_INT) ?: 4329;
        $year = filter_input(INPUT_GET, 'year', FILTER_SANITIZE_SPECIAL_CHARS) ?: '2569';

        $settings = $this->certificateModel->getActiveSettings();
        
        // If settings don't exist, retrieve or seed a basic array
        if (!$settings) {
            $settings = [
                'bg_style' => 'canva-2569',
                'border_color' => '#f97316',
                'header_title' => 'โรงเรียนพิชัย',
                'cert_title' => 'ขอมอบเกียรติบัตรนี้ให้ไว้เพื่อแสดงว่า',
                'body_pattern_1' => '',
                'body_pattern_2' => 'เนื่องในกิจกรรมกีฬาสีโรงเรียนพิชัย Phichai Games 2026',
                'body_pattern_3' => 'ระหว่างวันที่ 5 – 7 สิงหาคม 2569',
                'sig_left_title' => 'นางสาวรสสุคนธ์ วินชัยเหงา',
                'sig_right_title' => 'ผู้อำนวยการโรงเรียนพิชัย',
                'font_style' => 'Kanit',
                'show_logos' => 1,
                'layout_json' => json_encode([
                    "header_text" => [ "top" => 16, "fontSize" => 26, "color" => "#d97706", "fontWeight" => "black" ],
                    "main_title"  => [ "top" => 23, "fontSize" => 22, "color" => "#3b0764", "fontWeight" => "bold" ],
                    "prefix_text" => [ "top" => 28, "fontSize" => 14, "color" => "#64748b", "fontWeight" => "semibold" ],
                    "student_name" => [ "top" => 33, "fontSize" => 28, "color" => "#ffffff", "fontWeight" => "black" ],
                    "body_line1"  => [ "top" => 47, "fontSize" => 20, "color" => "#4c1d95", "fontWeight" => "bold" ],
                    "medal_badge" => [ "top" => 54, "fontSize" => 18, "color" => "#3b0764", "fontWeight" => "bold" ],
                    "body_line2"  => [ "top" => 64, "fontSize" => 15, "color" => "#1e1b4b", "fontWeight" => "semibold" ],
                    "date_text"   => [ "top" => 71, "fontSize" => 13, "color" => "#475569", "fontWeight" => "normal" ],
                    "signatures"  => [ "top" => 84, "fontSize" => 14, "color" => "#0f172a", "fontWeight" => "semibold" ],
                    "seal"        => [ "top" => 78, "scale" => 1.0 ]
                ])
            ];
        }

        // Fill missing database keys for backward compatibility
        if (!isset($settings['font_style'])) $settings['font_style'] = 'Kanit';
        if (!isset($settings['show_logos'])) $settings['show_logos'] = 1;

        $layout = json_decode($settings['layout_json'], true) ?: [];
        $winners = $this->certificateModel->getCanvaCertificatesList($start_no, $year);
        $presenter = new SportPresenter();

        require_once __DIR__ . '/../views/teacher_certificate.php';
    }

    /**
     * Export medal-winning athletes for Canva Bulk Create (Data CSV)
     * Headers: no, name, award, sport
     */
    private function exportCanvaCsv() {
        $start_no = filter_input(INPUT_GET, 'start_no', FILTER_VALIDATE_INT) ?: 4329;
        $year = filter_input(INPUT_GET, 'year', FILTER_SANITIZE_SPECIAL_CHARS) ?: '2569';

        $winners = $this->certificateModel->getCanvaCertificatesList($start_no, $year);

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="Canva_BulkCreate_Certificates_' . $year . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $out = fopen('php://output', 'w');
        // UTF-8 BOM for Thai language compatibility in Excel & Canva
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

        // CSV Headers corresponding directly to Canva {no}, {name}, {award}, {sport}
        fputcsv($out, ['no', 'name', 'award', 'sport']);

        foreach ($winners as $row) {
            fputcsv($out, [
                $row['no'],
                $row['name'],
                $row['award'],
                $row['sport']
            ]);
        }

        fclose($out);
        exit();
    }

    /**
     * Bulk print all certificates for all medal winners
     */
    private function bulkPrint() {
        $start_no = filter_input(INPUT_GET, 'start_no', FILTER_VALIDATE_INT) ?: 4329;
        $year = filter_input(INPUT_GET, 'year', FILTER_SANITIZE_SPECIAL_CHARS) ?: '2569';

        $settings = $this->certificateModel->getActiveSettings();
        if (!$settings) {
            $settings = [
                'bg_style' => 'canva-2569',
                'border_color' => '#f97316',
                'header_title' => 'โรงเรียนพิชัย',
                'cert_title' => 'ขอมอบเกียรติบัตรนี้ให้ไว้เพื่อแสดงว่า',
                'font_style' => 'Kanit',
                'show_logos' => 1
            ];
        }

        $winners = $this->certificateModel->getCanvaCertificatesList($start_no, $year);
        $presenter = new SportPresenter();

        require_once __DIR__ . '/../views/bulk_certificate.php';
        exit();
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
        $font_style = filter_input(INPUT_POST, 'font_style', FILTER_SANITIZE_SPECIAL_CHARS) ?: 'Kanit';
        $show_logos = isset($_POST['show_logos']) ? 1 : 0;

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
            'layout_json' => json_encode($layout),
            'font_style' => $font_style,
            'show_logos' => $show_logos
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
