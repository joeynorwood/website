<?php

class DatabaseAccessor {

    var $pdo;

    function __construct(){
        $this->pdo = $this->db_connect();
    }

    function db_connect() {
        if(!isset($pdo)) {
             // Load configuration as an array. Use the actual location of your configuration file
            $config = parse_ini_file('./config.ini'); 
            $dbname = $config['dbname'];
            $username = $config['username'];
            $password = $config['password'];
            $dsn = "mysql:host=127.0.0.1;dbname=$dbname;";
            try {
                $pdo = new PDO($dsn, $username, $password);
            }
            catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
        }
        return $pdo;
    }
    
    //check if email exists in user table and return salt or null
    function isUser($email) {
        $salt = null;
        $stmt = $this->pdo->prepare("SELECT salt FROM users WHERE email = ?");
        $stmt->execute(array($email));
        
        if ($stmt->rowCount() > 0){
            $result = $stmt->fetchAll();
            $salt = $result[0]['salt'];
        }

        return $salt;
    }
    
    //check if user is a professor
    function isProf($userID){
        $stmt = $this->pdo->prepare("SELECT is_prof FROM users WHERE id = ?");
        $stmt->execute(array($userID));
        
        if ($stmt->rowCount() > 0){
            $result = $stmt->fetchAll();
            return ($result[0]['is_prof'] > 0 ? true : false);
        }

        return false;
    }
    
    //check if user is a TA
    function isTA($userID){
        $stmt = $this->pdo->prepare("SELECT is_ta FROM users WHERE id = ?");
        $stmt->execute(array($userID));
        
        if ($stmt->rowCount() > 0){
            $result = $stmt->fetchAll();
            return ($result[0]['is_ta'] > 0 ? true : false);
        }

        return false;
    }
    
    //check if credentials are correct and if so, return user id, otherwise null
    function login($email, $hashedPW){
        $id = null;
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ? and password = ?");
        $stmt->execute(array($email, $hashedPW));
        
        if ($stmt->rowCount() > 0){
            $result = $stmt->fetchAll();
            $id = $result[0]['id'];
        }
        
        return $id;
    }
    
    //called when create account form is submitted
    function createAccount($email, $pw, $first, $last){
        //check if theyre already awaiting verification
        $chk = $this->pdo->prepare("SELECT id, date_created FROM usersAwaitingVerification where email = ?"); 
        
        $chk->execute(array($email));
        
        if ($chk->rowCount() > 0){
            $result = $chk->fetchAll();
            $id = $result[0]['id'];
            $dt = strtotime($result[0]['date_created']);
            
            if( (time() - $dt) < (60*60*24) ){
                return null;
            }
            else{
                $del = $this->pdo->prepare("
                DELETE FROM usersAwaitingVerification
                WHERE id = ?");
                
                $del->execute(array($id));
            }
        }
        
        $stmt = $this->pdo->prepare("
            INSERT INTO usersAwaitingVerification (email, password, salt, verification_code, first_name, last_name) 
            VALUES(?, ?, ?, ?, ?, ?)");
        
        $salt = $this->createSalt();
        $verification = $this->createVerificationCode();
        $hashedPW = $this->createHashedPassword($pw, $salt);
        
        $stmt->execute(array($this->sanitize($email), $hashedPW, $salt, $verification, $first, $last));
        return $verification;
    }
    
    function verify($email, $verification_code){
        $chk = $this->pdo->prepare("SELECT id FROM usersAwaitingVerification WHERE email = ? and verification_code = ?");
        $chk->execute(array($email, $verification_code));

        if ($chk->rowCount() > 0){
            $result = $chk->fetchAll();
            $id = $result[0]['id'];
            $ins = $this->pdo->prepare("
                INSERT INTO users (email, password, salt, is_prof, is_super, first_name, last_name)
                SELECT email, password, salt, 0, 0 , first_name, last_name
                FROM usersAwaitingVerification
                WHERE id = ?
            ");
            $ins->execute(array($id));
            
            $del = $this->pdo->prepare("
                DELETE FROM usersAwaitingVerification
                WHERE id = ?
            ");
            $del->execute(array($id));
            
            return true;
        }
        
        return false;
    }
    
    function createSalt(){
        $chars = 'abcdefghijklmnopqrstuvqxyz1234567890';
        $salt = '';

        for ($i = 1; $i <= 20; $i++) {
            $num = rand(0, strlen($chars)-1);
            $salt = $salt . substr($chars, $num, 1);
        }
    
        return $salt;
    }
    
    function createVerificationCode(){
        $chars = 'abcdefghijklmnopqrstuvqxyz1234567890';
        $salt = '';

        for ($i = 1; $i <= 40; $i++) {
            $num = rand(0, strlen($chars)-1);
            $salt = $salt . substr($chars, $num, 1);
        }
    
        return $salt;
    }

    function createHashedPassword($pw, $salt){
        return hash("sha256", $salt . $pw);
    }
    
    //returns an object like [ {term:<term>, definition:<definition>} ] or null if somebody is using the get param for funny business
    function getDefsForLetter($l){
        if(strlen($l) == 1 && (strpos('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz', $l) !== false)){
            $l = $l . '%';
            $stmt = $this->pdo->prepare("
            SELECT d.id AS definition_id, d.term, d.definition, c.name, c.professor_id
            FROM definition AS d
                JOIN course AS c ON d.course_id = c.id
            WHERE term LIKE ? 
                AND d.is_verified = 1
                AND d.is_display = 1
            ORDER BY term ASC
            ");
            $stmt->execute(array($l));
            return $stmt->fetchAll();
        }
        return null;
    }
    
    //course: -1 = ALL, 0 = My Courses, else use the num
    function search($srch, $course, $userID){
        $p1 = $srch . '%';
        $p2 = '%' . $srch;
        $p3 = '%' . $srch . '%';
        
        //make sure if not logged in then we're not searching for a user or course
        if(is_null($userID)){
            $course = -1;
        }
        
        if($course == -1){
            $stmt = $this->pdo->prepare("
            SELECT 
                d.id AS definition_id ,
                d.term, 
                d.definition, 
                c.name,
                CASE 
                    WHEN d.term LIKE ? THEN 0 
                    WHEN d.term LIKE ? THEN 1 
                    WHEN d.term LIKE ? THEN 2 
                    ELSE 3 
                END AS r ,
                c.professor_id ,
                c.ta_1_id ,
                c.ta_2_id ,
                c.ta_3_id
            FROM definition AS d
                JOIN course AS c ON d.course_id = c.id
            WHERE d.term LIKE ?
                AND d.is_verified = 1
                AND d.is_display = 1
            ORDER BY r ASC, d.term ASC
            LIMIT 30;
            ");
            $stmt->execute(array($p1, $p2, $p3, $p3));
            return $stmt->fetchAll();
        }
        elseif($course == 0){
            $stmt = $this->pdo->prepare("
            SELECT 
                d.id AS definition_id ,
                d.term, 
                d.definition, 
                c.name,
                CASE 
                    WHEN d.term LIKE ? THEN 0 
                    WHEN d.term LIKE ? THEN 1 
                    WHEN d.term LIKE ? THEN 2 
                    ELSE 3 
                END AS r ,
                c.professor_id
            FROM definition AS d
                JOIN course AS c ON d.course_id = c.id
                JOIN courseMember AS cm ON c.id = cm.course_id
            WHERE d.term LIKE ? 
                AND cm.user_id = ?
                AND d.is_verified = 1
                AND d.is_display = 1
            ORDER BY r ASC, d.term ASC
            LIMIT 30;
            ");
            $stmt->execute(array($p1, $p2, $p3, $p3, $userID));
            return $stmt->fetchAll();
        }
        else{
            $stmt = $this->pdo->prepare("
            SELECT 
                d.id AS definition_id ,
                d.term, 
                d.definition, 
                c.name,
                CASE 
                    WHEN d.term LIKE ? THEN 0 
                    WHEN d.term LIKE ? THEN 1 
                    WHEN d.term LIKE ? THEN 2 
                    ELSE 3 
                END AS r ,
                c.professor_id
            FROM definition AS d
                JOIN course AS c ON d.course_id = c.id
            WHERE d.term LIKE ? 
                AND c.id = ?
                AND is_verified = 1
                AND d.is_display = 1
            ORDER BY r ASC, d.term ASC
            LIMIT 30;
            ");
            $stmt->execute(array($p1, $p2, $p3, $p3, $course));
            return $stmt->fetchAll();
        }
    }
    
    function getCoursesForUserID($id){
        $stmt = $this->pdo->prepare("
            SELECT c.id AS course_id, c.name, c.description, DATE(c.date_created) AS date_created, 
                DATE(cm.date_joined) AS date_joined, u.email, c.professor_id, c.ta_1_id, c.ta_2_id, c.ta_3_id,
                u.first_name, u.last_name, IF(c.professor_id = cm.user_id, 1, 0) AS am_i_prof
            FROM course AS c 
                JOIN courseMember AS cm ON c.id = cm.course_id 
                JOIN users AS u ON c.professor_id = u.id
            WHERE cm.user_id = ? 
                AND c.is_closed = 0
            ORDER BY c.name ASC
        ");
        $stmt->execute(array($id));
        return $stmt->fetchAll();
    }
    
    function getAssignmentsForCourse($courseID){
        $stmt = $this->pdo->prepare("
            SELECT a.id AS assignment_id, a.course_id, a.description, a.date_due, a.amount_required
            FROM assignment AS a
            WHERE a.course_id = ?
        ");
        $stmt->execute(array($courseID));
        return $stmt->fetchAll();
    }
    
    function getAssignmentsForUserID($id){
        $stmt = $this->pdo->prepare("
            SELECT a.id AS assignment_id, c.id AS course_id
            FROM course AS c 
                JOIN courseMember AS cm ON c.id = cm.course_id
                JOIN assignment AS a ON c.id = a.course_id
            WHERE cm.user_id = ?
        ");
        $stmt->execute(array($id));
        return $stmt->fetchAll();
    }
    
    function defineTerm($term, $definition, $assignmentID, $userID){
        $assignments = $this->getAssignmentsForUserID($userID);
        
        $valid = false;
        $courseID = '';
        
        foreach($assignments as $row){
            if($row['assignment_id'] == $assignmentID){
                $valid = true;
                $courseID = $row['course_id'];
                break;
            }
        }
        
        if($valid){
            $stmt = $this->pdo->prepare("
                INSERT INTO definition (term, definition, course_id, user_id, is_verified, is_display, assignment_id) 
                VALUES ( ?, ?, ?, ?, 0, 0, ? )
            ");
            return $stmt->execute(array($this->sanitize($term), $this->sanitize($definition), $courseID, $userID, $assignmentID));
        }
        
        return null;
    }
    
    function createCourse($name, $description, $userID){
        $courseInsert = $this->pdo->prepare("
            INSERT INTO course (name, description, professor_id) 
            VALUES ( ?, ?, ? )
        ");
        
        if(!$courseInsert->execute(array($this->sanitize($name), $this->sanitize($description), $userID))){
            return false;
        }
        
        $courseSelect = $this->pdo->prepare("
            SELECT id 
            FROM course 
            WHERE name = ?
                AND description = ?
                AND professor_id = ?
        ");
        
        $courseSelectResult = $courseSelect->execute(array($this->sanitize($name), $this->sanitize($description), $userID));
        
        if(!$courseSelectResult || $courseSelect->rowCount() <= 0){
            return false;
        }
        
        $courseID = $courseSelect->fetchAll()[0]['id'];
        
        $courseMemberInsert = $this->pdo->prepare("
            INSERT INTO courseMember (user_id, course_id) 
            VALUES ( ?, ? )
        ");
        
        $courseMemberInsertResult = $courseMemberInsert->execute(array($userID, $courseID));
        
        return $courseMemberInsertResult;
    }
    
    function getJoinableCourses($userID){
        $stmt = $this->pdo->prepare("
            SELECT c.id AS course_id, c.name, c.description, DATE(c.date_created) AS date_created, u.email,
                u.first_name, u.last_name
            FROM course AS c 
                JOIN users AS u ON c.professor_id = u.id 
                LEFT JOIN ( SELECT DISTINCT cm.course_id 
                            FROM courseMember AS cm 
                            WHERE cm.user_id = ? 
                        ) AS z ON z.course_id = c.id 
                LEFT JOIN ( SELECT DISTINCT cmaf.course_id 
                            FROM courseMemberAwaitingVerification AS cmaf 
                            WHERE cmaf.user_id = ? 
                        ) AS y ON y.course_id = c.id 
            WHERE z.course_id IS NULL AND y.course_id IS NULL and c.is_closed = 0
            ORDER BY c.name ASC
        ");
        $stmt->execute(array($userID, $userID));
        return $stmt->fetchAll();
    }
    
    function joinCourse($userID, $courseID){
        $courses = $this->getJoinableCourses($userID);
        
        $valid = false;
        
        foreach($courses as $row){
            if($row['course_id'] == $courseID){
                $valid = true;
                break;
            }
        }
        
        $chk = $this->pdo->prepare("
                SELECT 1 AS one 
                FROM courseMemberAwaitingVerification 
                WHERE user_id = ?
                    AND course_id = ?
            ");
        
        $chk->execute(array($userID, $courseID));
        
        if($chk->rowCount() > 0){
            $valid = false;
        }
                      
        if($valid){
            $stmt = $this->pdo->prepare("
                INSERT INTO courseMemberAwaitingVerification (user_id, course_id)
                VALUES ( ?, ? )
            ");
        return $stmt->execute(array($userID, $courseID));
        }
        
        return false;
    }
    
    function getOwnedCourses($profID){
        $stmt = $this->pdo->prepare("
            SELECT c.id AS course_id, c.name, c.description
            FROM course AS c 
            WHERE (c.professor_id = ?
                OR c.ta_1_id = ?
                OR c.ta_2_id = ?
                OR c.ta_3_id = ?
                )
                AND c.is_closed = 0
            ORDER BY c.name ASC
        ");
        $stmt->execute(array($profID, $profID, $profID, $profID));
        return $stmt->fetchAll();
    }
    
    function getOwnedAssignments($profID){
        $stmt = $this->pdo->prepare("
            SELECT a.id AS assignment_id, a.description, a.date_due, amount_required
            FROM course AS c 
                JOIN assignment AS a ON c.id = a.course_id
            WHERE (c.professor_id = ?
                OR c.ta_1_id = ?
                OR c.ta_2_id = ?
                OR c.ta_3_id = ?
                )
                AND c.is_closed = 0
        ");
        $stmt->execute(array($profID, $profID, $profID, $profID));
        return $stmt->fetchAll();
    }
    
    function getOwnedStudents($profID){
        $stmt = $this->pdo->prepare("
            SELECT u.id AS user_id, u.email
            FROM course AS c 
                JOIN courseMember AS cm ON cm.course_id = c.id
                JOIN users AS u ON cm.user_id = u.id
            WHERE (c.professor_id = ?
                OR c.ta_1_id = ?
                OR c.ta_2_id = ?
                OR c.ta_3_id = ?
                )
                AND c.is_closed = 0
        ");
        $stmt->execute(array($profID, $profID, $profID, $profID));
        return $stmt->fetchAll();
    }
    
    function getDefinitionsToVerify($profID){
        $stmt = $this->pdo->prepare("
            SELECT d.id AS definition_id, d.term, d.definition, d.course_id, c.name AS course_name,
                DATE(d.date_created) AS date_created, d.assignment_id, a.description AS assignment, 
				d.user_id, u.email, u.first_name, u.last_name
            FROM definition AS d
                JOIN course AS c ON d.course_id = c.id
                JOIN users AS u ON d.user_id = u.id
				JOIN assignment AS a ON d.assignment_id = a.id
            WHERE is_verified = 0
                AND (c.professor_id = ? OR c.ta_1_id = ? OR c.ta_2_id = ? OR c.ta_3_id = ?)
                AND d.date_created > DATE_SUB(CURRENT_DATE(), INTERVAL 10 DAY)
                AND c.is_closed = 0
            ORDER BY d.date_created DESC
        ");
        $stmt->execute(array($profID, $profID, $profID, $profID));
        return $stmt->fetchAll();
    }
    
    function getUnverifiedDefinitionIDs($profID){
        $stmt = $this->pdo->prepare("
            SELECT d.id AS definition_id
            FROM definition AS d
                JOIN course AS c ON d.course_id = c.id
                JOIN users AS u ON c.professor_id = u.id
            WHERE d.is_verified = 0
                AND (c.professor_id = ? OR c.ta_1_id = ? OR c.ta_2_id = ? OR c.ta_3_id = ?)
        ");
        $stmt->execute(array($profID, $profID, $profID, $profID));
        return $stmt->fetchAll();
    }
    
    function verifyDefinition($profID, $definitionID){
        
        $defs = $this->getUnverifiedDefinitionIDs($profID);
        
        $valid = false;
        
        foreach($defs as $row){
            if($row['definition_id'] == $definitionID){
                $valid = true;
                break;
            }
        }
        
        if($valid){
            $stmt = $this->pdo->prepare("
                UPDATE definition
                SET is_verified = 1, is_display = 1
                WHERE id = ?
            ");
            
        return $stmt->execute(array($definitionID));
        }
        
        return false;
    }
    
    function getStudentsToVerify($profID){
        $stmt = $this->pdo->prepare("
            SELECT cmv.id, c.name AS course, u.email, u.first_name, u.last_name
            FROM courseMemberAwaitingVerification AS cmv
                JOIN course AS c ON cmv.course_id = c.id
                JOIN users AS u ON cmv.user_id = u.id
            WHERE (c.professor_id = ? OR c.ta_1_id = ? OR c.ta_2_id = ? OR c.ta_3_id = ?)
                AND cmv.date_created > DATE_SUB(CURRENT_DATE(), INTERVAL 10 DAY)
                AND c.is_closed = 0
        ");
        $stmt->execute(array($profID, $profID, $profID, $profID));
        return $stmt->fetchAll();
    }
    
    function verifyStudent($profID, $cmvID){
        
        $studs = $this->getStudentsToVerify($profID);
        
        $valid = false;
        
        foreach($studs as $row){
            if($row['id'] == $cmvID){
                $valid = true;
                break;
            }
        }
        
        if($valid){
            $stmt = $this->pdo->prepare("
                INSERT INTO courseMember (user_id, course_id)
                SELECT user_id, course_id
                FROM courseMemberAwaitingVerification AS cmv
                WHERE cmv.id = ?
            ");
            
            if(!$stmt->execute(array($cmvID))){
                return false;
            }
            else{
                $del = $this->pdo->prepare("
                    DELETE FROM courseMemberAwaitingVerification
                    WHERE id = ?
                ");
                
                return $del->execute(array($cmvID));
            }
        }
        
        return false;
    }
    
    function createAssignment($profID, $courseID, $description, $dateDue, $amount){
        
        $myCourses = $this->getOwnedCourses($profID);
        
        $valid = false;
        foreach($myCourses as $row){
            if($courseID == $row['course_id']){
                $valid = true;
                break;
            }
        }
        
        if($valid){
            $stmt = $this->pdo->prepare("
                INSERT INTO assignment (course_id, description, date_due, amount_required) 
                VALUES ( ?, ?, ?, ? )
            ");
            
            return $stmt->execute(array($courseID, $this->sanitize($description), $dateDue, $this->sanitize($amount)));
        }
        
        return $false;
        
    }
    
    function getCourseAssignments($userID){
        $stmt = $this->pdo->prepare("
            SELECT a.id AS assignment_id, CONCAT(c.name, ' - ', a.description) AS assignment
            FROM assignment AS a
                JOIN course AS c ON a.course_id = c.id
                JOIN courseMember AS cm ON cm.course_id = c.id
                JOIN users AS u ON cm.user_id = u.id
            WHERE u.id = ? AND c.is_closed = 0
            ORDER BY CONCAT(c.name, ' - ', a.description)
        ");
        $stmt->execute(array($userID));
        return $stmt->fetchAll();
    }
    
    function getReportData($profID, $courseID, $assignmentID, $studentID){
        if(is_null($courseID)){
            $courseID = -1;
        }
        if(is_null($assignmentID)){
            $assignmentID = -1;
        }
        if(is_null($studentID)){
            $studentID = -1;
        }
        
        $stmt = $this->pdo->prepare("
            SELECT 
                SUM(IF(d.date_created <= a.date_due AND d.is_verified = 1, 1, 0)) AS good_submissions ,
                SUM(IF(d.date_created > a.date_due AND d.is_verified = 1, 1, 0)) AS late_submissions ,
                SUM(IF(d.is_verified = 0, 1, 0)) AS unverified_submissions ,
                c.id AS course_id ,
                c.name AS course ,
                a.id AS assignment_id ,
                a.description AS assignment ,
                a.amount_required ,
                (YEAR(a.date_due) * 10000) + (MONTH(a.date_due)*100) + DAY(a.date_due) AS sort_date ,
                DATE_FORMAT(a.date_due, '%c/%e/%Y %l:00 %p') AS date_due ,
                u.id AS user_id ,
                u.email ,
                u.first_name ,
                u.last_name ,
                c.professor_id
            FROM course AS c 
                LEFT JOIN assignment AS a ON c.id = a.course_id
                LEFT JOIN courseMember AS cm ON c.id = cm.course_id
                LEFT JOIN users AS u ON cm.user_id = u.id
                LEFT JOIN definition AS d ON a.id = d.assignment_id AND u.id = d.user_id
            WHERE (c.professor_id = ? OR c.ta_1_id =? OR c.ta_2_id =? OR c.ta_3_id =?)
                AND IFNULL(c.id, -1) = IFNULL(COALESCE(NULLIF(?, -1), c.id), -1)
                AND IFNULL(a.id, -1) = IFNULL(COALESCE(NULLIF(?, -1), a.id), -1)
                AND IFNULL(u.id, -1) = IFNULL(COALESCE(NULLIF(?, -1), u.id), -1)
                AND c.is_closed = 0
            GROUP BY
                c.id ,
                c.name ,
                a.id ,
                a.description ,
                a.amount_required ,
                u.id ,
                u.email ,
                c.professor_id ,
                IF(d.date_created <= a.date_due, 1, 0) ,
                IF(d.date_created > a.date_due, 1, 0)
            ORDER BY
                c.name ASC,
                a.date_due ASC,
                u.last_name ASC,
                u.first_name ASC
        ");
        
        $stmt->execute(array($profID, $profID, $profID, $profID, $courseID, $assignmentID, $studentID));
        return $stmt->fetchAll();
    }
    
    function hideDefinition($userID, $definitionID){
        $chk = $this->pdo->prepare("
            SELECT COUNT(*) AS cnt
            FROM definition AS d
                JOIN course AS c ON d.course_id = c.id
            WHERE (c.professor_id = ? OR c.ta_1_id = ? OR c.ta_2_id = ?  OR c.ta_3_id = ?) AND d.id = ?
        ");
        
        $chk->execute(array($userID, $userID, $userID, $userID, $definitionID));
        $row = $chk->fetchAll();
        
        if($row[0]['cnt'] > 0){
            $stmt = $this->pdo->prepare("
            UPDATE definition
            SET is_display = 0
            WHERE id = ?
            ");
            $stmt->execute(array($definitionID));
        }
        
        return;
    }
    
    //returns an object like [ {term:<term>, definition:<definition>} ] or null if somebody is using the get param for funny business
    function getHiddenDefinitions($profID){
        $stmt = $this->pdo->prepare("
            SELECT d.id AS definition_id, d.term, d.definition, c.name, c.professor_id, u.email
            FROM definition AS d
                JOIN course AS c ON d.course_id = c.id
                JOIN users AS u ON d.user_id = u.id
            WHERE (c.professor_id = ? OR ta_1_id = ? OR ta_2_id = ? OR ta_3_id = ?)
                AND d.is_verified = 1
                AND d.is_display = 0
            ORDER BY term ASC
            ");
        
        $stmt->execute(array($profID, $profID, $profID, $profID));
        return $stmt->fetchAll();
    }
    
    function unhideDefinition($userID, $definitionID){
        $chk = $this->pdo->prepare("
            SELECT COUNT(*) AS cnt
            FROM definition AS d
                JOIN course AS c ON d.course_id = c.id
            WHERE (c.professor_id = ? OR c.ta_1_id = ? OR c.ta_2_id = ? OR c.ta_3_id = ?) AND d.id = ?
        ");
        
        $chk->execute(array($userID, $userID, $userID, $userID, $definitionID));
        $row = $chk->fetchAll();
        
        if($row[0]['cnt'] > 0){
            $stmt = $this->pdo->prepare("
            UPDATE definition
            SET is_display = 1
            WHERE id = ?
            ");
            $stmt->execute(array($definitionID));
        }
        
        return;
    }
    
    function sanitize($str){
        $newstr = str_replace("<", "&lt;", $str);
        $newstr = str_replace(">", "&gt;", $newstr);
        
        return $newstr;
    }
    
    function getAssignmentInfoForCourse($userID, $courseID){
        $stmt = $this->pdo->prepare("
            SELECT 
                a.id AS assignment_id, 
                d.id AS definition_id, 
                d.term, d.definition, 
                c.name AS course, 
                a.description AS assignment, 
                IF(d.is_verified = 1, 'Yes', 'No') AS is_verified,
                IF(a.date_due < d.date_created, 'Yes', 'No') AS is_late,
                DATE_FORMAT(a.date_due, '%c/%e/%Y %l:00 %p') AS date_due, 
                d.date_created, 
                a.amount_required, 
                CONCAT(u.last_name, ', ', u.first_name) AS prof,
                COALESCE(z.amt_late, 0) AS amt_late,
                COALESCE(z.amt_ontime, 0) AS amt_ontime,
                COALESCE(z.amt_verified, 0) AS amt_verified
            FROM course AS c
                JOIN users AS u ON c.professor_id = u.id
                JOIN courseMember AS cm ON cm.course_id = c.id
                LEFT JOIN assignment AS a ON a.course_id = c.id
                LEFT JOIN definition AS d ON d.assignment_id = a.id AND d.user_id = cm.user_id
                LEFT JOIN (
                        SELECT 
                            SUM(IF(za.date_due < zd.date_created, 1, 0)) AS amt_late ,
                            SUM(IF(za.date_due < zd.date_created, 0, 1)) AS amt_ontime ,
                            SUM(zd.is_verified) AS amt_verified ,
                            zd.user_id ,
                            za.id AS assignment_id
                        FROM definition AS zd
                            LEFT JOIN assignment AS za ON zd.assignment_id = za.id
                        GROUP BY 
                            zd.user_id ,
                            za.id
                    ) AS z ON z.user_id = cm.user_id AND z.assignment_id = a.id
            WHERE c.id = ? AND cm.user_id = ?
            ORDER BY a.date_due ASC, d.term ASC, d.definition ASC
            ");
        
        $stmt->execute(array($courseID, $userID));
        return $stmt->fetchAll();
    }
    
    function getAddTAData($profID){
        $stmt = $this->pdo->prepare("
            SELECT c.ta_1_id, c.ta_2_id, c.ta_3_id, c.id, c.name, 
                CONCAT(u1.last_name, ', ', u1.first_name, ' (', u1.email, ')') AS ta1, 
                CONCAT(u2.last_name, ', ', u2.first_name, ' (', u2.email, ')') AS ta2, 
                CONCAT(u3.last_name, ', ', u3.first_name, ' (', u3.email, ')') AS ta3
            FROM course AS c 
                LEFT JOIN users AS u1 ON u1.id = c.ta_1_id
                LEFT JOIN users AS u2 ON u2.id = c.ta_2_id
                LEFT JOIN users AS u3 ON u3.id = c.ta_3_id
            WHERE c.professor_id = ?
                AND c.is_closed = 0
            ORDER BY c.name
        ");
        $stmt->execute(array($profID));
        
        $stmt2 = $this->pdo->prepare("
            SELECT u.id, CONCAT(u.last_name, ', ', u.first_name, ' (', u.email, ')') AS name
            FROM users AS u
            ORDER BY CONCAT(u.last_name, ', ', u.first_name, ' (', u.email, ')')
        ");
        $stmt2->execute(array());
        
        $data = array($stmt->fetchAll(), $stmt2->fetchAll());
        return $data;
    }
    
    function getIDforEmail($email){
        $stmt = $this->pdo->prepare("
            SELECT u.id
            FROM users AS u
            WHERE u.email = ?
        ");
        
        $stmt->execute(array($email));
        $result = $stmt->fetchAll();
        
        if(count($result) == 0){
            return -1;
        }
        else {
            return $result[0]['id'];
        }
    }
    
    function updateTA($courseID, $taID, $num){
        if($num == 1){
            $stmt = $this->pdo->prepare("
                UPDATE course
                SET ta_1_id = ?
                WHERE id = ?
            ");
        }
        elseif($num == 2){
            $stmt = $this->pdo->prepare("
                UPDATE course
                SET ta_2_id = ?
                WHERE id = ?
            ");
        }
        else{
            $stmt = $this->pdo->prepare("
                UPDATE course
                SET ta_3_id = ?
                WHERE id = ?
            ");
        }
        if($taID == -1){
            $taID = null;
        }
        else{
            $stmt2 = $this->pdo->prepare("
                SELECT 1 
                FROM courseMember
                WHERE user_id = ?
                    AND course_id = ?
            ");
            
            $stmt2->execute(array($taID, $courseID));
            
            if(count($stmt2->fetchAll()) == 0){
                $stmt3 = $this->pdo->prepare("
                    INSERT INTO courseMember (user_id, course_id)
                    VALUES (?, ?)
                ");
                $stmt3->execute(array($taID, $courseID));
            }
            
            $stmt4 = $this->pdo->prepare("
                UPDATE users
                SET is_ta = 1
                WHERE id = ?
            ");
            
            $stmt4->execute(array($taID));
        }
        $stmt->execute(array($taID, $courseID));
    }
    
    function isOkToEmailPasswordReset($email){
        $stmt = $this->pdo->prepare("
            SELECT 1
            FROM usersResettingPassword AS u
            WHERE u.email = ?
                AND COALESCE(u.date_modified, u.date_created) < DATE_ADD(now(), INTERVAL -1 HOUR)
        ");
        
        $stmt->execute(array($email));
        
        $stmt2 = $this->pdo->prepare("
            SELECT 1
            FROM usersResettingPassword AS u
            WHERE u.email = ?
        ");
        
        $stmt2->execute(array($email));
        
        return ((count($stmt->fetchAll()) > 0) || (count($stmt2->fetchAll()) == 0));
        
    }
    
    function refreshPasswordReset($email, $hash){
        $stmt = $this->pdo->prepare("
            SELECT 1
            FROM usersResettingPassword AS u
            WHERE u.email = ?
        ");
        
        $stmt->execute(array($email));
        
        if(count($stmt->fetchAll()) == 0){
            $stmt2 = $this->pdo->prepare("
                INSERT INTO usersResettingPassword (email, verification_code)
                VALUES (?, ?)
            ");

            $stmt2->execute(array($email, $hash));
        }
        else{
            $stmt3 = $this->pdo->prepare("
                UPDATE usersResettingPassword
                SET verification_code = ?
                WHERE email = ?
            ");
            
            $stmt3->execute(array($hash, $email));
        }
    }
    
    
    function checkPassResetCode($hash, $email){
        $stmt = $this->pdo->prepare("
            SELECT 1
            FROM usersResettingPassword AS u
            WHERE u.email = ? and verification_code = ?
        ");
        
        $stmt->execute(array($email, $hash));
        
        return (count($stmt->fetchAll()) == 1);
    }
    
    function updatePassword($email, $pass, $salt){
        $stmt = $this->pdo->prepare("
            UPDATE users 
            SET password = ?, salt = ?
            WHERE email = ?
        ");
        
        $stmt->execute(array($pass, $salt, $email));
        
        $stmt2 = $this->pdo->prepare("
            DELETE FROM usersResettingPassword
            WHERE email = ?
        ");
        
        $stmt2->execute(array($email));
    }
    
    function getFirstName($id){
        $stmt = $this->pdo->prepare("
            SELECT first_name
            FROM users
            WHERE id = ?
        ");
        
        $stmt->execute(array($id));
        
        return $stmt->fetchAll()[0]['first_name'];
    }
    
    function getLastName($id){
        $stmt = $this->pdo->prepare("
            SELECT last_name
            FROM users
            WHERE id = ?
        ");
        
        $stmt->execute(array($id));
        
        return $stmt->fetchAll()[0]['last_name'];
    }
    
    function canICloseCourse($userID, $courseID){
        $stmt = $this->pdo->prepare("
            SELECT 1
            FROM course AS c
            WHERE c.professor_id = ? AND c.id = ?
        ");
        
        $stmt->execute(array($userID, $courseID));
        return (count($stmt->fetchAll()) > 0);
    }
    
    function closeCourse($courseID){
        $stmt = $this->pdo->prepare("
            UPDATE course 
            SET is_closed = 1
            WHERE id = ?
        ");
        
        $stmt->execute(array($courseID));
    }
}


?>