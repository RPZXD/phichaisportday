<?php
/**
 * AuthModel - Handles authentication queries against the main student database
 */
class AuthModel {
    private $db_main;

    public function __construct($db_main) {
        $this->db_main = $db_main;
    }

    /**
     * Authenticate a student against phichaia_student.student
     *
     * @param string $stu_id
     * @param string $password
     * @return array|false
     */
    public function authenticateStudent($stu_id, $password) {
        $stmt = $this->db_main->prepare("SELECT Stu_id, Stu_name, Stu_sur, Stu_password FROM student WHERE Stu_id = :stu_id LIMIT 1");
        $stmt->execute([':stu_id' => $stu_id]);
        $student = $stmt->fetch();

        if ($student) {
            // Plain text check as per existing schema structure
            if ($student['Stu_password'] === $password) {
                return [
                    'id' => $student['Stu_id'],
                    'name' => trim($student['Stu_name'] . ' ' . $student['Stu_sur']),
                    'role' => 'student'
                ];
            }
        }
        return false;
    }

    /**
     * Authenticate a teacher against phichaia_student.teacher
     *
     * @param string $username
     * @param string $password
     * @return array|false
     */
    public function authenticateTeacher($username, $password) {
        $stmt = $this->db_main->prepare("SELECT Teach_id, Teach_name, Teach_password, password, Teach_status FROM teacher WHERE (Teach_id = :username_id OR Teach_name = :username_name) AND Teach_status = '1' LIMIT 1");
        $stmt->execute([
            ':username_id' => $username,
            ':username_name' => $username
        ]);
        $teacher = $stmt->fetch();

        if ($teacher) {
            $authenticated = false;
            
            // 1. Try bcrypt hash first if populated
            if (!empty($teacher['password'])) {
                if (password_verify($password, $teacher['password'])) {
                    $authenticated = true;
                }
            }
            
            // 2. Fall back to plain text check (Teach_password or Teach_id) if not verified by hash
            if (!$authenticated) {
                if ($password === $teacher['Teach_id'] || (!empty($teacher['Teach_password']) && $password === $teacher['Teach_password'])) {
                    $authenticated = true;
                }
            }

            if ($authenticated) {
                return [
                    'id' => $teacher['Teach_id'],
                    'name' => trim($teacher['Teach_name']),
                    'role' => 'teacher'
                ];
            }
        }
        return false;
    }
}
