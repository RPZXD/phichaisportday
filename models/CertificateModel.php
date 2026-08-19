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
        $this->ensureTableExists();
    }

    /**
     * Ensure certificate_settings table exists in database
     */
    private function ensureTableExists() {
        try {
            $this->db_sports->exec("
                CREATE TABLE IF NOT EXISTS `certificate_settings` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `template_name` VARCHAR(100) DEFAULT 'Canva Template 2569',
                    `bg_style` VARCHAR(50) DEFAULT 'canva-2569',
                    `border_color` VARCHAR(20) DEFAULT '#f97316',
                    `header_title` VARCHAR(255) DEFAULT 'โรงเรียนพิชัย',
                    `cert_title` VARCHAR(255) DEFAULT 'ขอมอบเกียรติบัตรนี้ให้ไว้เพื่อแสดงว่า',
                    `body_pattern_1` TEXT DEFAULT NULL,
                    `body_pattern_2` TEXT DEFAULT 'เนื่องในกิจกรรมกีฬาสีโรงเรียนพิชัย Phichai Games 2026',
                    `body_pattern_3` TEXT DEFAULT 'ระหว่างวันที่ 5 – 7 สิงหาคม 2569',
                    `sig_left_title` VARCHAR(255) DEFAULT 'นางสาวรสสุคนธ์ วินชัยเหงา',
                    `sig_right_title` VARCHAR(255) DEFAULT 'ผู้อำนวยการโรงเรียนพิชัย',
                    `layout_json` LONGTEXT DEFAULT NULL,
                    `font_style` VARCHAR(50) DEFAULT 'Kanit',
                    `show_logos` TINYINT(1) DEFAULT 1,
                    `is_active` TINYINT(1) DEFAULT 1
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");
        } catch (Throwable $e) {
            // Silently continue if permissions restrict creation
        }
    }

    /**
     * Get the active certificate template settings
     */
    public function getActiveSettings() {
        try {
            $stmt = $this->db_sports->query("SELECT * FROM certificate_settings WHERE is_active = 1 LIMIT 1");
            return $stmt ? $stmt->fetch() : false;
        } catch (Throwable $e) {
            return false;
        }
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
                    layout_json = :layout_json,
                    font_style = :font_style,
                    show_logos = :show_logos
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
                ':font_style' => $data['font_style'],
                ':show_logos' => $data['show_logos'],
                ':id' => $id
            ]);
        } else {
            $stmt_ins = $this->db_sports->prepare("
                INSERT INTO certificate_settings 
                (template_name, bg_style, border_color, header_title, cert_title, body_pattern_1, body_pattern_2, body_pattern_3, sig_left_title, sig_right_title, layout_json, font_style, show_logos, is_active)
                VALUES ('Custom Template', :bg_style, :border_color, :header_title, :cert_title, :body_pattern_1, :body_pattern_2, :body_pattern_3, :sig_left_title, :sig_right_title, :layout_json, :font_style, :show_logos, 1)
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
                ':layout_json' => $data['layout_json'],
                ':font_style' => $data['font_style'],
                ':show_logos' => $data['show_logos']
            ]);
        }
    }

    /**
     * Get list of all athletes who won any medal or placement
     */
    public function getMedalWinners() {
        return $this->getCanvaCertificatesList(4329, '2569');
    }

    /**
     * Get full list of medal winners (Gold, Silver, Bronze) with running certificate numbers for Canva and printing
     */
    public function getCanvaCertificatesList($startNo = 4329, $year = '2569') {
        $stmt = $this->db_sports->query("
            SELECT 
                s.id as sport_id,
                s.sport_name,
                s.category,
                r.id as result_id,
                r.medal,
                r.points,
                h.id as house_id,
                h.house_name,
                h.color_code,
                er.student_id,
                st.Stu_name,
                st.Stu_sur,
                ch.grade_level,
                ch.room_number,
                m.event_date
            FROM results r
            JOIN matches_events m ON r.match_id = m.id
            JOIN sports s ON m.sport_id = s.id
            JOIN houses h ON r.house_id = h.id
            JOIN event_registrations er ON er.sport_id = s.id
            JOIN phichaia_student.student st ON er.student_id = st.Stu_id
            JOIN classroom_houses ch ON SUBSTRING(st.Stu_major, 1, 1) = ch.grade_level 
                                    AND st.Stu_room = ch.room_number 
                                    AND ch.house_id = r.house_id
            WHERE r.medal IN ('Gold', 'Silver', 'Bronze') OR r.points IN (3, 2, 1)
            ORDER BY s.id ASC, FIELD(r.medal, 'Gold', 'Silver', 'Bronze'), r.points DESC, st.Stu_id ASC
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $presenter = new SportPresenter();
        $list = [];

        foreach ($rows as $row) {
            $medalType = $row['medal'];
            if (empty($medalType)) {
                if ($row['points'] == 3) $medalType = 'Gold';
                elseif ($row['points'] == 2) $medalType = 'Silver';
                elseif ($row['points'] == 1) $medalType = 'Bronze';
            }

            $awardName = 'รางวัลชนะเลิศ (เหรียญทอง)';
            $awardSimple = 'ชนะเลิศ (เหรียญทอง)';
            if ($medalType === 'Silver' || $row['points'] == 2) {
                $awardName = 'รางวัลรองชนะเลิศ อันดับ 1 (เหรียญเงิน)';
                $awardSimple = 'รองชนะเลิศอันดับที่ 1 (เหรียญเงิน)';
            } elseif ($medalType === 'Bronze' || $row['points'] == 1) {
                $awardName = 'รางวัลรองชนะเลิศ อันดับ 2 (เหรียญทองแดง)';
                $awardSimple = 'รองชนะเลิศอันดับที่ 2 (เหรียญทองแดง)';
            }

            $sportDisplay = trim($row['sport_name']);
            if (!empty($row['category'])) {
                $sportDisplay .= ' (' . trim($row['category']) . ')';
            }
            if (mb_strpos($sportDisplay, 'กีฬา') !== 0) {
                $sportDisplay = 'กีฬา' . $sportDisplay;
            }

            $list[] = [
                'result_id' => $row['result_id'],
                'student_id' => $row['student_id'],
                'fullname' => trim($row['Stu_name'] . ' ' . $row['Stu_sur']),
                'Stu_name' => $row['Stu_name'],
                'Stu_sur' => $row['Stu_sur'],
                'medal' => $medalType,
                'award' => $awardName,
                'award_simple' => $awardSimple,
                'sport_name' => $row['sport_name'],
                'category' => $row['category'],
                'sport' => $sportDisplay,
                'house_name' => $row['house_name'],
                'house_name_th' => $presenter->getHouseNameTh($row['house_name']),
                'color_code' => $row['color_code'],
                'grade_level' => $row['grade_level'],
                'room_number' => $row['room_number'],
                'event_date' => $row['event_date']
            ];
        }

        // Sort by sport categorization order
        usort($list, function($a, $b) {
            $getSortKey = function($sportName) {
                $name = trim($sportName);
                $subScore = 99;
                if (mb_strpos($name, 'ม.ต้น') !== false && (mb_strpos($name, 'หญิง') !== false || mb_strpos($name, 'ญ') !== false)) {
                    $subScore = 1;
                } elseif (mb_strpos($name, 'ม.ต้น') !== false && (mb_strpos($name, 'ชาย') !== false || mb_strpos($name, 'ช') !== false)) {
                    $subScore = 2;
                } elseif (mb_strpos($name, 'ม.ปลาย') !== false && (mb_strpos($name, 'หญิง') !== false || mb_strpos($name, 'ญ') !== false)) {
                    $subScore = 3;
                } elseif (mb_strpos($name, 'ม.ปลาย') !== false && (mb_strpos($name, 'ชาย') !== false || mb_strpos($name, 'ช') !== false)) {
                    $subScore = 4;
                } elseif (mb_strpos($name, 'หญิง') !== false || mb_strpos($name, 'ญ') !== false) {
                    $subScore = 1;
                }

                if (mb_strpos($name, 'วอลเลย์บอล') !== false) return [1, 0, $subScore];
                if (mb_strpos($name, 'ตะกร้อ') !== false) return [2, 0, $subScore];
                if (mb_strpos($name, 'เปตอง') !== false) return [3, 0, $subScore];
                if (mb_strpos($name, 'วู้ดบอล') !== false) {
                    $typeScore = (mb_strpos($name, 'คู่') !== false) ? 2 : 1;
                    return [4, $typeScore, $subScore];
                }
                if (mb_strpos($name, 'ฟุตบอล') !== false) return [5, 0, $subScore];
                if (mb_strpos($name, 'บาสเกตบอล') !== false) return [6, 0, $subScore];
                if (mb_strpos($name, 'เทเบิลเทนนิส') !== false) return [7, 0, $subScore];

                return [8, 0, $subScore];
            };

            $keyA = $getSortKey($a['sport_name']);
            $keyB = $getSortKey($b['sport_name']);

            if ($keyA[0] !== $keyB[0]) return $keyA[0] <=> $keyB[0];
            if ($keyA[1] !== $keyB[1]) return $keyA[1] <=> $keyB[1];
            if ($keyA[2] !== $keyB[2]) return $keyA[2] <=> $keyB[2];

            // Order by Medal Gold -> Silver -> Bronze
            $medalOrder = ['Gold' => 1, 'Silver' => 2, 'Bronze' => 3];
            $mA = $medalOrder[$a['medal']] ?? 4;
            $mB = $medalOrder[$b['medal']] ?? 4;
            if ($mA !== $mB) return $mA <=> $mB;

            return strcmp($a['fullname'], $b['fullname']);
        });

        // Assign running cert numbers starting at $startNo
        $currentNo = intval($startNo);
        foreach ($list as $idx => &$item) {
            $item['seq'] = $idx + 1;
            $item['cert_no_raw'] = $currentNo;
            $item['cert_no'] = $currentNo . '/' . $year;
            $item['no'] = $currentNo . '/' . $year; // For Canva column match
            $item['name'] = $item['fullname'];     // For Canva column match
            $currentNo++;
        }
        unset($item);

        return $list;
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
