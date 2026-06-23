<?php
/**
 * BracketModel - Handles Single Elimination Tournament Brackets operations
 */
class BracketModel {
    private $db_sports;

    public function __construct($db_sports) {
        $this->db_sports = $db_sports;
    }

    /**
     * Get all brackets and matches for a specific sport
     */
    public function getBracketsBySport($sport_id) {
        $stmt = $this->db_sports->prepare("
            SELECT b.*, 
                   m.status, m.event_date,
                   h1.house_name as team1_name, h1.color_code as team1_color,
                   h2.house_name as team2_name, h2.color_code as team2_color,
                   hw.house_name as winner_name, hw.color_code as winner_color
            FROM tournament_brackets b
            JOIN matches_events m ON b.match_id = m.id
            LEFT JOIN houses h1 ON b.team1_house_id = h1.id
            LEFT JOIN houses h2 ON b.team2_house_id = h2.id
            LEFT JOIN houses hw ON b.winner_house_id = hw.id
            WHERE b.sport_id = :sport_id
            ORDER BY b.round_number ASC, b.match_order ASC
        ");
        $stmt->execute([':sport_id' => $sport_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create a 6-team single elimination bracket for a sport
     * $team_ids array must contain 6 house IDs:
     * - Index 0, 1: Quarter-final 1 (team1, team2)
     * - Index 2, 3: Quarter-final 2 (team1, team2)
     * - Index 4: Semi-final 1 (team1, Bye)
     * - Index 5: Semi-final 2 (team1, Bye)
     */
    public function create6TeamBracket($sport_id, $team_ids) {
        // Check if bracket already exists
        $stmt = $this->db_sports->prepare("SELECT COUNT(*) FROM tournament_brackets WHERE sport_id = :sport_id");
        $stmt->execute([':sport_id' => $sport_id]);
        if ($stmt->fetchColumn() > 0) {
            return false;
        }

        try {
            $this->db_sports->beginTransaction();

            $event_date = date('Y-m-d H:i:s');

            // 1. Create general prepared statements
            $stmt_match = $this->db_sports->prepare("INSERT INTO matches_events (sport_id, event_date, status) VALUES (:sport_id, :event_date, 'Scheduled')");
            $stmt_bracket = $this->db_sports->prepare("
                INSERT INTO tournament_brackets (match_id, sport_id, round_name, round_number, match_order, team1_house_id, team2_house_id, next_match_id, next_match_position)
                VALUES (:match_id, :sport_id, :round_name, :round_number, :match_order, :team1_house_id, :team2_house_id, :next_match_id, :next_match_position)
            ");

            // 2. Create Finals Match (Round 3, Order 1)
            $stmt_match->execute([':sport_id' => $sport_id, ':event_date' => $event_date]);
            $finals_match_id = $this->db_sports->lastInsertId();

            $stmt_bracket->execute([
                ':match_id' => $finals_match_id,
                ':sport_id' => $sport_id,
                ':round_name' => 'Finals',
                ':round_number' => 3,
                ':match_order' => 1,
                ':team1_house_id' => null,
                ':team2_house_id' => null,
                ':next_match_id' => null,
                ':next_match_position' => null
            ]);
            $finals_bracket_id = $this->db_sports->lastInsertId();

            // 3. Create Third-place Match (Round 3, Order 2)
            $stmt_match->execute([':sport_id' => $sport_id, ':event_date' => date('Y-m-d H:i:s', strtotime($event_date . ' + 30 minutes'))]);
            $third_place_match_id = $this->db_sports->lastInsertId();

            $stmt_bracket->execute([
                ':match_id' => $third_place_match_id,
                ':sport_id' => $sport_id,
                ':round_name' => 'Third-place',
                ':round_number' => 3,
                ':match_order' => 2,
                ':team1_house_id' => null,
                ':team2_house_id' => null,
                ':next_match_id' => null,
                ':next_match_position' => null
            ]);

            // 4. Create Semi-finals Matches (Round 2)
            // Semi-final 1 (references Finals as team1)
            $stmt_match->execute([':sport_id' => $sport_id, ':event_date' => date('Y-m-d H:i:s', strtotime($event_date . ' + 1 hour'))]);
            $sf1_match_id = $this->db_sports->lastInsertId();

            $stmt_bracket->execute([
                ':match_id' => $sf1_match_id,
                ':sport_id' => $sport_id,
                ':round_name' => 'Semi-finals',
                ':round_number' => 2,
                ':match_order' => 1,
                ':team1_house_id' => $team_ids[4], // Bye team 1
                ':team2_house_id' => null,
                ':next_match_id' => $finals_bracket_id,
                ':next_match_position' => 'team1'
            ]);
            $sf1_bracket_id = $this->db_sports->lastInsertId();

            // Semi-final 2 (references Finals as team2)
            $stmt_match->execute([':sport_id' => $sport_id, ':event_date' => date('Y-m-d H:i:s', strtotime($event_date . ' + 1.5 hours'))]);
            $sf2_match_id = $this->db_sports->lastInsertId();

            $stmt_bracket->execute([
                ':match_id' => $sf2_match_id,
                ':sport_id' => $sport_id,
                ':round_name' => 'Semi-finals',
                ':round_number' => 2,
                ':match_order' => 2,
                ':team1_house_id' => $team_ids[5], // Bye team 2
                ':team2_house_id' => null,
                ':next_match_id' => $finals_bracket_id,
                ':next_match_position' => 'team2'
            ]);
            $sf2_bracket_id = $this->db_sports->lastInsertId();

            // 5. Create Quarter-finals Matches (Round 1)
            // Quarter-final 1 (references Semi-final 1 as team2)
            $stmt_match->execute([':sport_id' => $sport_id, ':event_date' => date('Y-m-d H:i:s', strtotime($event_date . ' + 2 hours'))]);
            $qf1_match_id = $this->db_sports->lastInsertId();

            $stmt_bracket->execute([
                ':match_id' => $qf1_match_id,
                ':sport_id' => $sport_id,
                ':round_name' => 'Quarter-finals',
                ':round_number' => 1,
                ':match_order' => 1,
                ':team1_house_id' => $team_ids[0],
                ':team2_house_id' => $team_ids[1],
                ':next_match_id' => $sf1_bracket_id,
                ':next_match_position' => 'team2'
            ]);

            // Quarter-final 2 (references Semi-final 2 as team2)
            $stmt_match->execute([':sport_id' => $sport_id, ':event_date' => date('Y-m-d H:i:s', strtotime($event_date . ' + 2.5 hours'))]);
            $qf2_match_id = $this->db_sports->lastInsertId();

            $stmt_bracket->execute([
                ':match_id' => $qf2_match_id,
                ':sport_id' => $sport_id,
                ':round_name' => 'Quarter-finals',
                ':round_number' => 1,
                ':match_order' => 2,
                ':team1_house_id' => $team_ids[2],
                ':team2_house_id' => $team_ids[3],
                ':next_match_id' => $sf2_bracket_id,
                ':next_match_position' => 'team2'
            ]);

            $this->db_sports->commit();
            return true;
        } catch (Exception $e) {
            $this->db_sports->rollBack();
            throw $e;
        }
    }

    /**
     * Delete/Reset bracket for a sport (deletes all matches and bracket nodes)
     */
    public function resetBracket($sport_id) {
        try {
            $this->db_sports->beginTransaction();

            // 1. Get all matches related to this bracket
            $stmt = $this->db_sports->prepare("SELECT match_id FROM tournament_brackets WHERE sport_id = :sport_id");
            $stmt->execute([':sport_id' => $sport_id]);
            $match_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (!empty($match_ids)) {
                // 2. Delete from matches_events (which cascades to results & tournament_brackets)
                $placeholders = implode(',', array_fill(0, count($match_ids), '?'));
                $stmt_del = $this->db_sports->prepare("DELETE FROM matches_events WHERE id IN ($placeholders)");
                $stmt_del->execute($match_ids);
            }

            $this->db_sports->commit();
            return true;
        } catch (Exception $e) {
            $this->db_sports->rollBack();
            throw $e;
        }
    }

    /**
     * Record bracket match results, set winner, and propagate to next round
     */
    public function recordBracketResult($bracket_id, $team1_score, $team2_score, $winner_house_id, $points_winner, $points_loser) {
        try {
            $this->db_sports->beginTransaction();

            // 1. Get bracket details
            $stmt = $this->db_sports->prepare("SELECT * FROM tournament_brackets WHERE id = :id");
            $stmt->execute([':id' => $bracket_id]);
            $bracket = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$bracket) {
                $this->db_sports->rollBack();
                return false;
            }

            $match_id = $bracket['match_id'];
            $team1_house_id = $bracket['team1_house_id'];
            $team2_house_id = $bracket['team2_house_id'];

            // Validate that winner is one of the competitors
            if ($winner_house_id != $team1_house_id && $winner_house_id != $team2_house_id) {
                $this->db_sports->rollBack();
                throw new Exception("ทีมผู้ชนะไม่อยู่ในคู่แข่งขันนี้");
            }

            // Determine loser
            $loser_house_id = ($winner_house_id == $team1_house_id) ? $team2_house_id : $team1_house_id;

            // 2. Save scores in tournament_brackets
            $stmt_upd = $this->db_sports->prepare("
                UPDATE tournament_brackets 
                SET team1_score = :team1_score, team2_score = :team2_score, winner_house_id = :winner_house_id 
                WHERE id = :id
            ");
            $stmt_upd->execute([
                ':team1_score' => $team1_score,
                ':team2_score' => $team2_score,
                ':winner_house_id' => $winner_house_id,
                ':id' => $bracket_id
            ]);

            // 3. Update main matches_events status
            $stmt_match = $this->db_sports->prepare("UPDATE matches_events SET status = 'Completed' WHERE id = :match_id");
            $stmt_match->execute([':match_id' => $match_id]);

            // 4. Save points for leaderboard in results table
            // Delete existing results first to prevent duplication
            $stmt_del_res = $this->db_sports->prepare("DELETE FROM results WHERE match_id = :match_id");
            $stmt_del_res->execute([':match_id' => $match_id]);

            // Determine points and medals based on round
            $winner_points = 0;
            $winner_medal = null;
            $loser_points = 0;
            $loser_medal = null;

            if ($bracket['round_name'] === 'Finals') {
                $winner_points = 3;
                $winner_medal = 'Gold';
                $loser_points = 2;
                $loser_medal = 'Silver';
            } elseif ($bracket['round_name'] === 'Third-place') {
                $winner_points = 1;
                $winner_medal = 'Bronze';
                $loser_points = 0;
                $loser_medal = null;
            }

            $stmt_ins_res = $this->db_sports->prepare("INSERT INTO results (match_id, house_id, points, medal) VALUES (:match_id, :house_id, :points, :medal)");
            
            // Insert winner
            $stmt_ins_res->execute([
                ':match_id' => $match_id,
                ':house_id' => $winner_house_id,
                ':points' => $winner_points,
                ':medal' => $winner_medal
            ]);

            // Insert loser
            if ($loser_house_id) {
                $stmt_ins_res->execute([
                    ':match_id' => $match_id,
                    ':house_id' => $loser_house_id,
                    ':points' => $loser_points,
                    ':medal' => $loser_medal
                ]);
            }

            // 5. Propagate winner to next match if there is one
            if ($bracket['next_match_id'] !== null) {
                $next_match_id = $bracket['next_match_id'];
                $position = $bracket['next_match_position']; // 'team1' or 'team2'

                if ($position === 'team1') {
                    $stmt_next = $this->db_sports->prepare("UPDATE tournament_brackets SET team1_house_id = :winner WHERE id = :id");
                } else {
                    $stmt_next = $this->db_sports->prepare("UPDATE tournament_brackets SET team2_house_id = :winner WHERE id = :id");
                }
                $stmt_next->execute([
                    ':winner' => $winner_house_id,
                    ':id' => $next_match_id
                ]);
            }

            // Propagate loser of Semi-finals to Third-place match
            if ($bracket['round_name'] === 'Semi-finals') {
                // Find the Third-place bracket for this sport
                $stmt_tp = $this->db_sports->prepare("SELECT id FROM tournament_brackets WHERE sport_id = :sport_id AND round_name = 'Third-place' LIMIT 1");
                $stmt_tp->execute([':sport_id' => $bracket['sport_id']]);
                $tp_bracket = $stmt_tp->fetch(PDO::FETCH_ASSOC);
                if ($tp_bracket) {
                    $tp_bracket_id = $tp_bracket['id'];
                    // If Semi-final 1 (match_order = 1), loser goes to team1_house_id of Third-place
                    // If Semi-final 2 (match_order = 2), loser goes to team2_house_id of Third-place
                    if ($bracket['match_order'] == 1) {
                        $stmt_tp_upd = $this->db_sports->prepare("UPDATE tournament_brackets SET team1_house_id = :loser WHERE id = :id");
                    } else {
                        $stmt_tp_upd = $this->db_sports->prepare("UPDATE tournament_brackets SET team2_house_id = :loser WHERE id = :id");
                    }
                    $stmt_tp_upd->execute([
                        ':loser' => $loser_house_id,
                        ':id' => $tp_bracket_id
                    ]);
                }
            }

            $this->db_sports->commit();
            return true;
        } catch (Exception $e) {
            $this->db_sports->rollBack();
            throw $e;
        }
    }

    /**
     * Get all active brackets grouped by sport ID
     */
    public function getAllActiveBrackets() {
        $stmt = $this->db_sports->query("
            SELECT b.*, 
                   s.sport_name, s.category as sport_category,
                   m.status, m.event_date,
                   h1.house_name as team1_name, h1.color_code as team1_color,
                   h2.house_name as team2_name, h2.color_code as team2_color,
                   hw.house_name as winner_name, hw.color_code as winner_color
            FROM tournament_brackets b
            JOIN sports s ON b.sport_id = s.id
            JOIN matches_events m ON b.match_id = m.id
            LEFT JOIN houses h1 ON b.team1_house_id = h1.id
            LEFT JOIN houses h2 ON b.team2_house_id = h2.id
            LEFT JOIN houses hw ON b.winner_house_id = hw.id
            ORDER BY b.sport_id ASC, b.round_number ASC, b.match_order ASC
        ");
        $all = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $grouped = [];
        foreach ($all as $b) {
            $sport_id = $b['sport_id'];
            if (!isset($grouped[$sport_id])) {
                $grouped[$sport_id] = [
                    'sport_name' => $b['sport_name'],
                    'sport_category' => $b['sport_category'],
                    'matches' => []
                ];
            }
            $grouped[$sport_id]['matches'][] = $b;
        }
        return $grouped;
    }
}
