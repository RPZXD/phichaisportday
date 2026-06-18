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
}
