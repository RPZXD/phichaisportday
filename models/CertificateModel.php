<?php
/**
 * CertificateModel - Handles templates, positions, layouts, and issued medals
 */
class CertificateModel {
    private $db_sports;
    private $db_main;

    public function __construct($db_sports, $db_main) {
        $this->db_sports = $db_sports;
        $this->db_main = $db_main;
    }

    /**
     * Get the active certificate template settings
     */
    public function getActiveSettings() {
        $stmt = $this->db_sports->query("SELECT * FROM certificate_settings WHERE is_active = 1 LIMIT 1");
        return $stmt->fetch();
    }

    /**
     * Save/update certificate settings
     */
    public function saveSettings($data) {
        // Find existing active configuration id
        $stmt = $this->db_sports->query("SELECT id FROM certificate_settings WHERE is_active = 1 LIMIT 1");
        $id = $stmt->fetchColumn();

        if ($id) {
            $stmt_upd = $this->db_sports->prepare("
                UPDATE certificate_settings 
                SET bg_style = :bg_style,
                    border_color = :border_color,
                    header_title = :header_title,
                    cert_title = :cert_title,
                    body_pattern_1 = :body_pattern_1,
                    body_pattern_2 = :body_pattern_2,
                    body_pattern_3 = :body_pattern_3,
                    sig_left_title = :sig_left_title,
                    sig_right_title = :sig_right_title,
                    layout_json = :layout_json
                WHERE id = :id
            ");
            return $stmt_upd->execute([
                ':bg_style' => $data['bg_style'],
                ':border_color' => $data['border_color'],
                ':header_title' => $data['header_title'],
                ':cert_title' => $data['cert_title'],
                ':body_pattern_1' => $data['body_pattern_1'],
                ':body_pattern_2' => $data['body_pattern_2'],
                ':body_pattern_3' => $data['body_pattern_3'],
                ':sig_left_title' => $data['sig_left_title'],
                ':sig_right_title' => $data['sig_right_title'],
                ':layout_json' => $data['layout_json'],
                ':id' => $id
            ]);
        } else {
            $stmt_ins = $this->db_sports->prepare("
                INSERT INTO certificate_settings 
                (template_name, bg_style, border_color, header_title, cert_title, body_pattern_1, body_pattern_2, body_pattern_3, sig_left_title, sig_right_title, layout_json, is_active)
                VALUES ('Custom Template', :bg_style, :border_color, :header_title, :cert_title, :body_pattern_1, :body_pattern_2, :body_pattern_3, :sig_left_title, :sig_right_title, :layout_json, 1)
            ");
            return $stmt_ins->execute([
                ':bg_style' => $data['bg_style'],
                ':border_color' => $data['border_color'],
                ':header_title' => $data['header_title'],
                ':cert_title' => $data['cert_title'],
                ':body_pattern_1' => $data['body_pattern_1'],
                ':body_pattern_2' => $data['body_pattern_2'],
                ':body_pattern_3' => $data['body_pattern_3'],
                ':sig_left_title' => $data['sig_left_title'],
                ':sig_right_title' => $data['sig_right_title'],
                ':layout_json' => $data['layout_json']
            ]);
        }
    }

    /**
     * Get list of all athletes who won any medal or placement
     */
    public function getMedalWinners() {
        $stmt = $this->db_sports->prepare("
            SELECT r.id as result_id, r.medal, r.points, m.event_date,
                   s.sport_name, s.category, h.house_name, h.color_code,
                   er.student_id, stud.Stu_name, stud.Stu_sur,
                   ch.grade_level, ch.room_number
            FROM results r
            JOIN matches_events m ON r.match_id = m.id
            JOIN sports s ON m.sport_id = s.id
            JOIN houses h ON r.house_id = h.id
            JOIN event_registrations er ON er.sport_id = s.id
            JOIN phichaia_student.student stud ON er.student_id = stud.Stu_id
            JOIN classroom_houses ch ON SUBSTRING(stud.Stu_major, 1, 1) = ch.grade_level 
                                    AND stud.Stu_room = ch.room_number 
                                    AND ch.house_id = r.house_id
            ORDER BY m.event_date DESC, r.id DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get mapping details for award/placement types
     */
    public static function getAwardDetails($medal) {
        switch ($medal) {
            case 'Gold':
                return [
                    'name' => 'ชนะเลิศ',
                    'medal_text' => 'เหรียญทอง',
                    'emoji' => '🥇',
                    'color' => '#8a6d1c',
                    'badge_bg' => 'from-amber-500/10 to-yellow-500/15'
                ];
            case 'Silver':
                return [
                    'name' => 'รองชนะเลิศอันดับที่ 1',
                    'medal_text' => 'เหรียญเงิน',
                    'emoji' => '🥈',
                    'color' => '#475569',
                    'badge_bg' => 'from-slate-400/10 to-slate-500/15'
                ];
            case 'Bronze':
                return [
                    'name' => 'รองชนะเลิศอันดับที่ 2',
                    'medal_text' => 'เหรียญทองแดง',
                    'emoji' => '🥉',
                    'color' => '#7c2d12',
                    'badge_bg' => 'from-amber-700/10 to-amber-800/15'
                ];
            case 'RunnerUp3':
                return [
                    'name' => 'รองชนะเลิศอันดับที่ 3',
                    'medal_text' => 'รองชนะเลิศอันดับที่ 3',
                    'emoji' => '🏅',
                    'color' => '#334155',
                    'badge_bg' => 'from-slate-500/10 to-slate-600/15'
                ];
            case 'Participant':
            default:
                return [
                    'name' => 'เข้าร่วมการแข่งขัน',
                    'medal_text' => 'เข้าร่วมการแข่งขัน (ที่ 5 - 6)',
                    'emoji' => '🌟',
                    'color' => '#0369a1',
                    'badge_bg' => 'from-sky-500/10 to-sky-600/15'
                ];
        }
    }
}
