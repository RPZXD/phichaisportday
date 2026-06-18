<?php
/**
 * HouseModel - Handles retrieval of sports day houses and classroom mappings
 */
class HouseModel {
    private $db_sports;

    public function __construct($db_sports) {
        $this->db_sports = $db_sports;
    }

    /**
     * Get all houses ordered by name
     */
    public function getAllHouses() {
        $stmt = $this->db_sports->query("SELECT * FROM houses ORDER BY house_name ASC");
        return $stmt->fetchAll();
    }

    /**
     * Get single house by ID
     */
    public function getHouseById($id) {
        $stmt = $this->db_sports->prepare("SELECT * FROM houses WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Get all classroom-to-house assignments
     */
    public function getClassroomMappings() {
        $stmt = $this->db_sports->query("
            SELECT ch.*, h.house_name, h.color_code 
            FROM classroom_houses ch 
            JOIN houses h ON ch.house_id = h.id 
            ORDER BY ch.grade_level ASC, ch.room_number ASC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Assign a classroom to a house color (inserts or updates mapping)
     */
    public function assignClassroom($grade, $room, $house_id) {
        $stmt = $this->db_sports->prepare("
            INSERT INTO classroom_houses (grade_level, room_number, house_id) 
            VALUES (:grade, :room, :house_id)
            ON DUPLICATE KEY UPDATE house_id = :house_id
        ");
        return $stmt->execute([
            ':grade' => $grade,
            ':room' => $room,
            ':house_id' => $house_id
        ]);
    }

    /**
     * Remove a classroom-to-house assignment
     */
    public function deleteClassroom($id) {
        $stmt = $this->db_sports->prepare("DELETE FROM classroom_houses WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
