<?php
/**
 * AthleteModel - Manages color representatives and links classrooms to house colors
 */
class AthleteModel {
    private $db_sports;
    private $db_main;

    public function __construct($db_sports, $db_main) {
        $this->db_sports = $db_sports;
        $this->db_main = $db_main;
    }

    /**
     * Check if a student is already appointed as a representative
     */
    public function isRepresentative($student_id) {
        $stmt = $this->db_sports->prepare("SELECT COUNT(*) FROM sports_day_athletes WHERE student_id = :student_id");
        $stmt->execute([':student_id' => $student_id]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Appoint a student as a color representative
     */
    public function appointRepresentative($student_id, $house_id) {
        if ($this->isRepresentative($student_id)) {
            return false;
        }

        // Verify the student actually belongs to this house color by classroom
        $student_house = $this->getStudentHouse($student_id);
        if (!$student_house || $student_house['house_id'] != $house_id) {
            return false;
        }

        $stmt = $this->db_sports->prepare("INSERT INTO sports_day_athletes (student_id, house_id) VALUES (:student_id, :house_id)");
        return $stmt->execute([
            ':student_id' => $student_id,
            ':house_id' => $house_id
        ]);
    }

    /**
     * Remove a representative appointment
     */
    public function deleteRepresentative($id) {
        $stmt = $this->db_sports->prepare("DELETE FROM sports_day_athletes WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Get a list of all appointed representatives with student details
     */
    public function getAllRepresentatives() {
        $stmt = $this->db_sports->query("
            SELECT a.id, a.student_id, a.house_id, h.house_name, h.color_code 
            FROM sports_day_athletes a 
            JOIN houses h ON a.house_id = h.id
            ORDER BY h.house_name ASC, a.id DESC
        ");
        $representatives = $stmt->fetchAll();

        if (empty($representatives)) {
            return [];
        }

        return $this->enrichAthletesWithStudentDetails($representatives);
    }

    /**
     * Get details of a single representative by student ID
     */
    public function getRepresentativeByStudentId($student_id) {
        $stmt = $this->db_sports->prepare("
            SELECT a.id, a.student_id, a.house_id, h.house_name, h.color_code 
            FROM sports_day_athletes a 
            JOIN houses h ON a.house_id = h.id 
            WHERE a.student_id = :student_id
            LIMIT 1
        ");
        $stmt->execute([':student_id' => $student_id]);
        $rep = $stmt->fetch();

        if (!$rep) {
            return false;
        }

        $enriched = $this->enrichAthletesWithStudentDetails([$rep]);
        return $enriched[0];
    }

    /**
     * Get student's house details dynamically based on their classroom (grade and room)
     */
    public function getStudentHouse($student_id) {
        $stmt = $this->db_main->prepare("
            SELECT Stu_id, Stu_name, Stu_sur, Stu_major, Stu_room, Stu_status 
            FROM student 
            WHERE Stu_id = :id LIMIT 1
        ");
        $stmt->execute([':id' => $student_id]);
        $stud = $stmt->fetch();

        if (!$stud || $stud['Stu_status'] != 1) {
            return false; // Student must be active
        }

        $grade = intval(substr($stud['Stu_major'], 0, 1));
        $room = intval($stud['Stu_room']);

        $stmt_ch = $this->db_sports->prepare("
            SELECT ch.house_id, h.house_name, h.color_code 
            FROM classroom_houses ch 
            JOIN houses h ON ch.house_id = h.id 
            WHERE ch.grade_level = :grade AND ch.room_number = :room
            LIMIT 1
        ");
        $stmt_ch->execute([':grade' => $grade, ':room' => $room]);
        $house = $stmt_ch->fetch();

        if (!$house) {
            return false;
        }

        return [
            'student_id' => $stud['Stu_id'],
            'student_name' => trim($stud['Stu_name'] . ' ' . $stud['Stu_sur']),
            'house_id' => $house['house_id'],
            'house_name' => $house['house_name'],
            'color_code' => $house['color_code'],
            'grade_level' => $grade,
            'room_number' => $room
        ];
    }

    /**
     * Get all active students belonging to a specific house ID based on classroom mapping
     */
    public function getStudentsByHouse($house_id) {
        $stmt_class = $this->db_sports->prepare("SELECT grade_level, room_number FROM classroom_houses WHERE house_id = :house_id");
        $stmt_class->execute([':house_id' => $house_id]);
        $classrooms = $stmt_class->fetchAll();
        
        if (empty($classrooms)) {
            return [];
        }

        $clauses = [];
        $params = [];
        $i = 0;
        foreach ($classrooms as $c) {
            $clauses[] = "(SUBSTRING(Stu_major, 1, 1) = :grade$i AND Stu_room = :room$i)";
            $params[":grade$i"] = $c['grade_level'];
            $params[":room$i"] = $c['room_number'];
            $i++;
        }
        
        $sql = "SELECT Stu_id, Stu_name, Stu_sur, Stu_major, Stu_room 
                FROM student 
                WHERE Stu_status = 1 AND (" . implode(' OR ', $clauses) . ")
                ORDER BY Stu_name ASC, Stu_sur ASC";
                
        $stmt = $this->db_main->prepare($sql);
        $stmt->execute($params);
        $students = $stmt->fetchAll();
        
        $result = [];
        foreach ($students as $s) {
            $result[] = [
                'id' => $s['Stu_id'],
                'student_id' => $s['Stu_id'],
                'student_name' => trim($s['Stu_name'] . ' ' . $s['Stu_sur']),
                'grade_level' => intval(substr($s['Stu_major'], 0, 1)),
                'room_number' => intval($s['Stu_room'])
            ];
        }
        return $result;
    }

    public function searchStudents($query, $house_id = null, $limit = 15) {
        $searchTerm = "%" . $query . "%";
        if ($house_id) {
            $stmt = $this->db_main->prepare("
                SELECT s.Stu_id, s.Stu_name, s.Stu_sur, s.Stu_major, s.Stu_room 
                FROM student s
                JOIN school_sports_day.classroom_houses ch 
                  ON SUBSTRING(s.Stu_major, 1, 1) = ch.grade_level 
                 AND s.Stu_room = ch.room_number
                WHERE s.Stu_status = 1 
                  AND ch.house_id = :house_id
                  AND (s.Stu_id LIKE :term1 OR s.Stu_name LIKE :term2 OR s.Stu_sur LIKE :term3) 
                LIMIT :limit
            ");
            $stmt->bindValue(':house_id', $house_id, PDO::PARAM_INT);
        } else {
            $stmt = $this->db_main->prepare("
                SELECT Stu_id, Stu_name, Stu_sur, Stu_major, Stu_room 
                FROM student 
                WHERE Stu_status = 1 AND (Stu_id LIKE :term1 OR Stu_name LIKE :term2 OR Stu_sur LIKE :term3) 
                LIMIT :limit
            ");
        }
        $stmt->bindValue(':term1', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':term2', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':term3', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $students = $stmt->fetchAll();

        $results = [];
        foreach ($students as $stud) {
            $student_id = $stud['Stu_id'];
            
            // Check if representative already appointed
            $stmt_rep = $this->db_sports->prepare("SELECT COUNT(*) FROM sports_day_athletes WHERE student_id = :id");
            $stmt_rep->execute([':id' => $student_id]);
            $is_registered = $stmt_rep->fetchColumn() > 0;

            // Get dynamic house color assignment
            $house_details = $this->getStudentHouse($student_id);

            $results[] = [
                'Stu_id' => $stud['Stu_id'],
                'Stu_name' => $stud['Stu_name'],
                'Stu_sur' => $stud['Stu_sur'],
                'grade_level' => intval(substr($stud['Stu_major'], 0, 1)),
                'room_number' => intval($stud['Stu_room']),
                'is_registered' => $is_registered,
                'house_id' => $house_details ? $house_details['house_id'] : null,
                'house_name' => $house_details ? $house_details['house_name'] : null,
                'color_code' => $house_details ? $house_details['color_code'] : null
            ];
        }

        return $results;
    }

    /**
     * Helper to batch enrich list with student names
     */
    private function enrichAthletesWithStudentDetails($athletes) {
        $studentIds = array_unique(array_column($athletes, 'student_id'));
        if (empty($studentIds)) {
            return $athletes;
        }

        $placeholders = implode(',', array_fill(0, count($studentIds), '?'));
        $stmt_students = $this->db_main->prepare("
            SELECT Stu_id, Stu_name, Stu_sur 
            FROM student 
            WHERE Stu_id IN ($placeholders)
        ");
        $stmt_students->execute(array_values($studentIds));
        $students = $stmt_students->fetchAll();

        $studentMap = [];
        foreach ($students as $student) {
            $studentMap[$student['Stu_id']] = trim($student['Stu_name'] . ' ' . $student['Stu_sur']);
        }

        foreach ($athletes as &$athlete) {
            $athlete['student_name'] = isset($studentMap[$athlete['student_id']]) 
                ? $studentMap[$athlete['student_id']] 
                : 'Unknown Student (' . $athlete['student_id'] . ')';
        }

        return $athletes;
    }
}
