<?php
/**
 * SportModel - Handles retrieval and creation of sports events
 */
class SportModel {
    private $db_sports;

    public function __construct($db_sports) {
        $this->db_sports = $db_sports;
    }

    /**
     * Get all sports sorted by name
     */
    public function getAllSports() {
        $stmt = $this->db_sports->query("
            SELECT s.*, 
                   (SELECT COUNT(*) FROM tournament_brackets b WHERE b.sport_id = s.id) as bracket_count 
            FROM sports s 
            ORDER BY s.sport_name ASC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Get single sport by ID
     */
    public function getSportById($id) {
        $stmt = $this->db_sports->prepare("SELECT * FROM sports WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Create a new sport
     */
    public function addSport($sport_name, $category) {
        $stmt = $this->db_sports->prepare("INSERT INTO sports (sport_name, category) VALUES (:sport_name, :category)");
        return $stmt->execute([
            ':sport_name' => $sport_name,
            ':category' => $category
        ]);
    }
}
