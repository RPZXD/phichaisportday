<?php
/**
 * ResultModel - Manages scores, medals, leaderboard, and certificate logic
 */
class ResultModel {
    private $db_sports;
    private $db_main;

    public function __construct($db_sports, $db_main) {
        $this->db_sports = $db_sports;
        $this->db_main = $db_main;
    }

    /**
     * Get results recorded for a specific match
     */
    public function getMatchResults($match_id) {
        $stmt = $this->db_sports->prepare("
            SELECT r.*, h.house_name, h.color_code 
            FROM results r 
            JOIN houses h ON r.house_id = h.id 
            WHERE r.match_id = :match_id
        ");
        $stmt->execute([':match_id' => $match_id]);
        return $stmt->fetchAll();
    }

    /**
     * Save/record points and medals for a match and house
     */
    public function saveResult($match_id, $house_id, $points, $medal) {
        // Check if result already exists for this match and house
        $stmt = $this->db_sports->prepare("SELECT id FROM results WHERE match_id = :match_id AND house_id = :house_id");
        $stmt->execute([':match_id' => $match_id, ':house_id' => $house_id]);
        $existingId = $stmt->fetchColumn();

        if ($existingId) {
            $stmt_upd = $this->db_sports->prepare("
                UPDATE results 
                SET points = :points, medal = :medal 
                WHERE id = :id
            ");
            return $stmt_upd->execute([
                ':points' => $points,
                ':medal' => !empty($medal) ? $medal : null,
                ':id' => $existingId
            ]);
        } else {
            $stmt_ins = $this->db_sports->prepare("
                INSERT INTO results (match_id, house_id, points, medal) 
                VALUES (:match_id, :house_id, :points, :medal)
            ");
            return $stmt_ins->execute([
                ':match_id' => $match_id,
                ':house_id' => $house_id,
                ':points' => $points,
                ':medal' => !empty($medal) ? $medal : null
            ]);
        }
    }

    /**
     * Delete results of a match
     */
    public function deleteMatchResults($match_id) {
        $stmt = $this->db_sports->prepare("DELETE FROM results WHERE match_id = :match_id");
        return $stmt->execute([':match_id' => $match_id]);
    }

    /**
     * Get aggregate standings (leaderboard) of all houses
     */
    public function getLeaderboard() {
        $stmt = $this->db_sports->query("
            SELECT h.id, h.house_name, h.color_code, 
                   COALESCE(SUM(r.points), 0) as total_points,
                   SUM(CASE WHEN r.medal = 'Gold' THEN 1 ELSE 0 END) as gold_count,
                   SUM(CASE WHEN r.medal = 'Silver' THEN 1 ELSE 0 END) as silver_count,
                   SUM(CASE WHEN r.medal = 'Bronze' THEN 1 ELSE 0 END) as bronze_count
            FROM houses h 
            LEFT JOIN results r ON h.id = r.house_id 
            GROUP BY h.id 
            ORDER BY total_points DESC, gold_count DESC, silver_count DESC, h.house_name ASC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Get medals won by a specific student (athlete) for certificate display
     */
    public function getAthleteMedals($student_id) {
        $stmt = $this->db_sports->prepare("
            SELECT r.id as result_id, r.medal, r.points, m.id as match_id, m.event_date,
                   s.sport_name, s.category, h.house_name, h.color_code
            FROM results r
            JOIN matches_events m ON r.match_id = m.id
            JOIN sports s ON m.sport_id = s.id
            JOIN houses h ON r.house_id = h.id
            JOIN event_registrations er ON er.sport_id = s.id AND er.student_id = :student_id
            JOIN phichaia_student.student stud ON er.student_id = stud.Stu_id
            JOIN classroom_houses ch ON SUBSTRING(stud.Stu_major, 1, 1) = ch.grade_level AND stud.Stu_room = ch.room_number AND ch.house_id = r.house_id
            WHERE r.medal IS NOT NULL AND r.medal <> ''
            ORDER BY m.event_date DESC, r.id DESC
        ");
        $stmt->execute([':student_id' => $student_id]);
        return $stmt->fetchAll();
    }

    /**
     * Get details for a specific certificate by result ID and student ID
     */
    public function getCertificateDetails($result_id, $student_id) {
        $stmt = $this->db_sports->prepare("
            SELECT r.id as result_id, r.medal, r.points, m.event_date,
                   s.sport_name, s.category, h.house_name, h.color_code,
                   er.student_id
            FROM results r
            JOIN matches_events m ON r.match_id = m.id
            JOIN sports s ON m.sport_id = s.id
            JOIN houses h ON r.house_id = h.id
            JOIN event_registrations er ON er.sport_id = s.id AND er.student_id = :student_id
            JOIN phichaia_student.student stud ON er.student_id = stud.Stu_id
            JOIN classroom_houses ch ON SUBSTRING(stud.Stu_major, 1, 1) = ch.grade_level AND stud.Stu_room = ch.room_number AND ch.house_id = r.house_id
            WHERE r.id = :result_id
        ");
        $stmt->execute([
            ':result_id' => $result_id,
            ':student_id' => $student_id
        ]);
        $details = $stmt->fetch();

        if (!$details) {
            return false;
        }

        // Fetch student's name from main DB
        $stmt_stud = $this->db_main->prepare("SELECT Stu_name, Stu_sur FROM student WHERE Stu_id = :stu_id");
        $stmt_stud->execute([':stu_id' => $student_id]);
        $stud = $stmt_stud->fetch();

        if ($stud) {
            $details['student_name'] = trim($stud['Stu_name'] . ' ' . $stud['Stu_sur']);
        } else {
            $details['student_name'] = 'Unknown Student';
        }

        return $details;
    }

    /**
     * Save final results (Gold, Silver, Bronze) for an entire sport event
     */
    public function saveSportFinalResults($sport_id, $gold_house_id, $silver_house_id, $bronze_house_id) {
        try {
            $this->db_sports->beginTransaction();

            // 1. Find or create a completed match event to hold these results
            $stmt = $this->db_sports->prepare("
                SELECT id FROM matches_events 
                WHERE sport_id = :sport_id 
                  AND id NOT IN (SELECT match_id FROM tournament_brackets)
                LIMIT 1
            ");
            $stmt->execute([':sport_id' => $sport_id]);
            $match_id = $stmt->fetchColumn();

            if (!$match_id) {
                $stmt_ins_match = $this->db_sports->prepare("
                    INSERT INTO matches_events (sport_id, event_date, status) 
                    VALUES (:sport_id, NOW(), 'Completed')
                ");
                $stmt_ins_match->execute([':sport_id' => $sport_id]);
                $match_id = $this->db_sports->lastInsertId();
            } else {
                $stmt_upd_match = $this->db_sports->prepare("
                    UPDATE matches_events SET status = 'Completed' WHERE id = :id
                ");
                $stmt_upd_match->execute([':id' => $match_id]);
            }

            // 2. Clear any existing results for this summary match
            $stmt_del = $this->db_sports->prepare("DELETE FROM results WHERE match_id = :match_id");
            $stmt_del->execute([':match_id' => $match_id]);

            // 3. Insert Gold (3 points)
            if (!empty($gold_house_id)) {
                $stmt_ins = $this->db_sports->prepare("
                    INSERT INTO results (match_id, house_id, points, medal) 
                    VALUES (:match_id, :house_id, 3, 'Gold')
                ");
                $stmt_ins->execute([':match_id' => $match_id, ':house_id' => $gold_house_id]);
            }

            // 4. Insert Silver (2 points)
            if (!empty($silver_house_id)) {
                $stmt_ins = $this->db_sports->prepare("
                    INSERT INTO results (match_id, house_id, points, medal) 
                    VALUES (:match_id, :house_id, 2, 'Silver')
                ");
                $stmt_ins->execute([':match_id' => $match_id, ':house_id' => $silver_house_id]);
            }

            // 5. Insert Bronze (1 point)
            if (!empty($bronze_house_id)) {
                $stmt_ins = $this->db_sports->prepare("
                    INSERT INTO results (match_id, house_id, points, medal) 
                    VALUES (:match_id, :house_id, 1, 'Bronze')
                ");
                $stmt_ins->execute([':match_id' => $match_id, ':house_id' => $bronze_house_id]);
            }

            $this->db_sports->commit();
            return true;
        } catch (Exception $e) {
            $this->db_sports->rollBack();
            throw $e;
        }
    }

    /**
     * Get final medals for all sports
     */
    public function getAllSportMedals() {
        $stmt = $this->db_sports->query("
            SELECT r.points, r.medal, r.house_id, m.sport_id, h.house_name, h.color_code
            FROM results r
            JOIN matches_events m ON r.match_id = m.id
            JOIN houses h ON r.house_id = h.id
            WHERE m.id NOT IN (SELECT match_id FROM tournament_brackets)
        ");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $medals = [];
        foreach ($results as $r) {
            $sport_id = $r['sport_id'];
            if (!isset($medals[$sport_id])) {
                $medals[$sport_id] = [
                    'Gold' => null,
                    'Silver' => null,
                    'Bronze' => null
                ];
            }
            if ($r['medal'] === 'Gold') {
                $medals[$sport_id]['Gold'] = $r;
            } elseif ($r['medal'] === 'Silver') {
                $medals[$sport_id]['Silver'] = $r;
            } elseif ($r['medal'] === 'Bronze') {
                $medals[$sport_id]['Bronze'] = $r;
            }
        }
        return $medals;
    }

    /**
     * Get Top 1-3 results for each sport event grouped and ordered by sport categories:
     * 1. Volleyball (วอลเลย์บอล: ม.ต้น ญ, ม.ต้น ช, ม.ปลาย ญ, ม.ปลาย ช)
     * 2. Takraw (ตะกร้อ/เซปักตะกร้อ: ม.ต้น ญ, ม.ต้น ช, ม.ปลาย ญ, ม.ปลาย ช)
     * 3. Petanque (เปตอง: ม.ต้น ญ, ม.ต้น ช, ม.ปลาย ญ, ม.ปลาย ช)
     * 4. Woodball (วู้ดบอล: เดี่ยว (ม.ต้น ญ, ม.ต้น ช, ม.ปลาย ญ, ม.ปลาย ช), คู่ (ม.ต้น ญ, ม.ต้น ช, ม.ปลาย ญ, ม.ปลาย ช))
     * 5. Football 7-a-side (ฟุตบอล 7 คน: ญ. รวม, ม.ต้น ช, ม.ปลาย ช)
     * 6. Basketball (บาสเกตบอล: ญ. รวม, ม.ต้น ช, ม.ปลาย ช)
     * 7. Table Tennis (เทเบิลเทนนิส: ม.ต้น ญ, ม.ต้น ช, ม.ปลาย ญ, ม.ปลาย ช)
     * 8. Others (E-Sport, etc.)
     */
    public function getTopResultsBySport() {
        $stmt = $this->db_sports->query("
            SELECT 
                s.id as sport_id,
                s.sport_name,
                s.category,
                r.medal,
                r.points,
                h.id as house_id,
                h.house_name,
                h.color_code
            FROM sports s
            JOIN matches_events m ON s.id = m.sport_id
            JOIN results r ON m.id = r.match_id
            JOIN houses h ON r.house_id = h.id
            WHERE r.medal IN ('Gold', 'Silver', 'Bronze')
               OR r.points IN (3, 2, 1)
            ORDER BY s.id ASC, 
                     FIELD(r.medal, 'Gold', 'Silver', 'Bronze'), 
                     r.points DESC
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resultsBySport = [];
        foreach ($rows as $row) {
            $sport_id = $row['sport_id'];
            if (!isset($resultsBySport[$sport_id])) {
                $resultsBySport[$sport_id] = [
                    'sport_id' => $sport_id,
                    'sport_name' => $row['sport_name'],
                    'category' => $row['category'],
                    'top_results' => []
                ];
            }
            $resultsBySport[$sport_id]['top_results'][] = $row;
        }

        $list = array_values($resultsBySport);

        // Sorting helper based on specified group order and sub-categories
        usort($list, function($a, $b) {
            $getSortKey = function($sportName) {
                $name = trim($sportName);

                // Helper to score sub-category order: ม.ต้น ญ (1) -> ม.ต้น ช (2) -> ม.ปลาย ญ (3) -> ม.ปลาย ช (4) / ญ รวม (1)
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

                // 1. วอลเลย์บอล
                if (mb_strpos($name, 'วอลเลย์บอล') !== false) {
                    return [1, 0, $subScore];
                }
                // 2. ตะกร้อ / เซปักตะกร้อ
                if (mb_strpos($name, 'ตะกร้อ') !== false) {
                    return [2, 0, $subScore];
                }
                // 3. เปตอง
                if (mb_strpos($name, 'เปตอง') !== false) {
                    return [3, 0, $subScore];
                }
                // 4. วู้ดบอล
                if (mb_strpos($name, 'วู้ดบอล') !== false) {
                    $typeScore = 1; // เดี่ยว
                    if (mb_strpos($name, 'คู่') !== false) {
                        $typeScore = 2; // คู่
                    }
                    return [4, $typeScore, $subScore];
                }
                // 5. ฟุตบอล 7 คน
                if (mb_strpos($name, 'ฟุตบอล') !== false) {
                    return [5, 0, $subScore];
                }
                // 6. บาสเกตบอล
                if (mb_strpos($name, 'บาสเกตบอล') !== false) {
                    return [6, 0, $subScore];
                }
                // 7. เทเบิลเทนนิส
                if (mb_strpos($name, 'เทเบิลเทนนิส') !== false) {
                    return [7, 0, $subScore];
                }

                // 8. อื่นๆ (E-Sport ฯลฯ)
                return [8, 0, $subScore];
            };

            $keyA = $getSortKey($a['sport_name']);
            $keyB = $getSortKey($b['sport_name']);

            if ($keyA[0] !== $keyB[0]) return $keyA[0] <=> $keyB[0];
            if ($keyA[1] !== $keyB[1]) return $keyA[1] <=> $keyB[1];
            if ($keyA[2] !== $keyB[2]) return $keyA[2] <=> $keyB[2];

            return strcmp($a['sport_name'], $b['sport_name']);
        });

        return $list;
    }
}


