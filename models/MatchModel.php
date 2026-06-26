<?php
/**
 * MatchModel - Handles match scheduling, event registrations, and statuses
 */
class MatchModel {
    private $db_sports;
    private $db_main;

    public function __construct($db_sports, $db_main) {
        $this->db_sports = $db_sports;
        $this->db_main = $db_main;
    }

    /**
     * Get all matches along with their sports details
     */
    public function getAllMatches() {
        $stmt = $this->db_sports->query("
            SELECT m.*, s.sport_name, s.category,
                   tb.id as bracket_id, tb.round_name, tb.round_number, tb.match_order,
                   tb.team1_house_id, tb.team2_house_id, tb.winner_house_id,
                   tb.team1_score, tb.team2_score,
                   h1.house_name as team1_name, h1.color_code as team1_color,
                   h2.house_name as team2_name, h2.color_code as team2_color
            FROM matches_events m 
            JOIN sports s ON m.sport_id = s.id 
            LEFT JOIN tournament_brackets tb ON m.id = tb.match_id
            LEFT JOIN houses h1 ON tb.team1_house_id = h1.id
            LEFT JOIN houses h2 ON tb.team2_house_id = h2.id
            ORDER BY m.id DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Get a single match by ID
     */
    public function getMatchById($id) {
        $stmt = $this->db_sports->prepare("
            SELECT m.*, s.sport_name, s.category 
            FROM matches_events m 
            JOIN sports s ON m.sport_id = s.id 
            WHERE m.id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Schedule a new match
     */
    public function createMatch($sport_id, $event_date, $status = 'Scheduled') {
        $stmt = $this->db_sports->prepare("
            INSERT INTO matches_events (sport_id, event_date, status) 
            VALUES (:sport_id, :event_date, :status)
        ");
        return $stmt->execute([
            ':sport_id' => $sport_id,
            ':event_date' => $event_date,
            ':status' => $status
        ]);
    }

    /**
     * Update match status (Scheduled, Live, Completed)
     */
    public function updateMatchStatus($id, $status) {
        $stmt = $this->db_sports->prepare("UPDATE matches_events SET status = :status WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':status' => $status
        ]);
    }

    /**
     * Delete a scheduled match
     */
    public function deleteMatch($id) {
        $stmt = $this->db_sports->prepare("DELETE FROM matches_events WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Check if a student is already registered for a specific sport
     */
    public function isAthleteRegisteredForSport($student_id, $sport_id) {
        $stmt = $this->db_sports->prepare("
            SELECT COUNT(*) FROM event_registrations 
            WHERE student_id = :student_id AND sport_id = :sport_id
        ");
        $stmt->execute([':student_id' => $student_id, ':sport_id' => $sport_id]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Register a student for a specific sport
     */
    public function registerAthleteToSport($student_id, $sport_id) {
        if ($this->isAthleteRegisteredForSport($student_id, $sport_id)) {
            return false;
        }
        $stmt = $this->db_sports->prepare("
            INSERT INTO event_registrations (student_id, sport_id) 
            VALUES (:student_id, :sport_id)
        ");
        return $stmt->execute([
            ':student_id' => $student_id,
            ':sport_id' => $sport_id
        ]);
    }

    /**
     * Remove a student's registration from a sport
     */
    public function removeAthleteFromSport($registration_id) {
        $stmt = $this->db_sports->prepare("DELETE FROM event_registrations WHERE id = :id");
        return $stmt->execute([':id' => $registration_id]);
    }

    /**
     * Get all students registered for a particular sport
     */
    public function getSportRegistrations($sport_id) {
        $stmt = $this->db_sports->prepare("
            SELECT er.id as registration_id, er.student_id, er.sport_id,
                   s.Stu_name, s.Stu_sur,
                   ch.house_id, h.house_name, h.color_code,
                   ch.grade_level, ch.room_number
            FROM event_registrations er
            JOIN phichaia_student.student s ON er.student_id = s.Stu_id
            JOIN classroom_houses ch ON SUBSTRING(s.Stu_major, 1, 1) = ch.grade_level AND s.Stu_room = ch.room_number
            JOIN houses h ON ch.house_id = h.id
            WHERE er.sport_id = :sport_id
            ORDER BY h.house_name ASC, er.id DESC
        ");
        $stmt->execute([':sport_id' => $sport_id]);
        $registrations = $stmt->fetchAll();

        if (empty($registrations)) {
            return [];
        }

        foreach ($registrations as &$reg) {
            $reg['student_name'] = trim($reg['Stu_name'] . ' ' . $reg['Stu_sur']);
        }

        return $registrations;
    }

    /**
     * Get all sports registered by a specific student ID
     */
    public function getStudentRegistrations($student_id) {
        $stmt = $this->db_sports->prepare("
            SELECT er.id as registration_id, er.sport_id, s.sport_name, s.category
            FROM event_registrations er
            JOIN sports s ON er.sport_id = s.id
            WHERE er.student_id = :student_id
            ORDER BY s.sport_name ASC
        ");
        $stmt->execute([':student_id' => $student_id]);
        return $stmt->fetchAll();
    }

    /**
     * Update match date/time
     */
    public function updateMatchDate($id, $event_date) {
        $stmt = $this->db_sports->prepare("UPDATE matches_events SET event_date = :event_date WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':event_date' => $event_date
        ]);
    }
}
