<?php

class htmlGenerator{

    var $email;
    var $loggedIn;
    var $id;
    var $courses;
    var $first;
    var $last;
    
    function __construct($loggedIn, $id, $email, $courses, $first, $last){
        $this->id = $id;
        $this->email = $email;
        $this->loggedIn = $loggedIn;
        $this->first = $first;
        $this->last = $last;
        $this->courses = $courses;
    }
    
    function createHeader($title){
        $HTML = "
            <script src='./control.js'></script>
            <script type='text/javascript'>window.onload=pageLoad;</script>
            <div class='hdr'>
                <img src='./logo.png' alt='Terms Unknown Logo' height='75' width='152'>
                <div class='search_container'>
                    <form method='post' action='./search.php'>
                        <input type='search' placeholder='Search...' name='s' required>
                        <button type='submit'>Search</button>";
         
        $HTML = $HTML . $this->createTopCourseSelector($this->courses);
        
        $HTML = $HTML . "
                    </form>
                </div>
                <h1>" . $title . "</h1>
                <div class='top_forms'>";
        if(!is_null($this->first) and !is_null($this->last)) {
            $HTML = $HTML . "
                    <form method='get' action='./profile.php'>
                        <button id='profile' type='submit'>" . $this->first . ' ' . $this->last . "</button>
                    </form>
                    <form method='get' action='./logout-post.php'>
                        <button type='submit'>Logout</button>
                    </form>
                ";
        }
        else{
            $HTML = $HTML . "
                    <form method='get' action='./login.php'>
                        <button type='submit'>Login</button>
                    </form>
                    <form method='get' action='./register.php'>
                        <button type='submit'>Register</button>
                    </form>
                ";
        }
        
        $HTML = $HTML . "
                </div>
            </div>";
        return $HTML;
    }
    
    function createTopCourseSelector($courses){
        $HTML = "
        <select class='search_select' name='c'>
            <option value='-1'>All</option>
        ";
        
        if(!is_null($this->id)){
            $HTML = $HTML . "<option value='0'>Only my courses</option>";
            foreach($courses as $row){
                $HTML = $HTML . "<option value='" . $row['course_id'] . "'>" . $row['name'] . "</option>";
            }
        }
        
        $HTML = $HTML . "</select>";
        
        return $HTML;
    }
    
    function createDefineForm($assignments){
        $HTML = "
        <form method='post' action='./define-post.php' id='define_form'>
            <table>
                <tr>
                    <td class='define-label'><span>Course - Assignment:</span></td>
                    <td class='define-input'>";
        $HTML = $HTML . $this->createCourseAssignmentSelector($assignments);
        $HTML = $HTML . "</td>
                </tr>
                <tr>
                    <td class='define-label'><span>Term:</span></td>
                    <td class='define-input'><input type='text' name='term'></td>
                </tr>
                <tr>
                    <td class='define-label'><span>Definition:</span></td>
                    <td class='define-input'><textarea rows='10' name='definition' form='define_form'></textarea></td>
                </tr>
            </table>
            <button type='submit'>Submit Definition</button>
        </form>";
        
        return $HTML;
    }
    
    function createCourseAssignmentSelector($courses){
        $HTML = "
        <select class='course_select' name='assignment'>";
        
        foreach($courses as $row){
            $HTML = $HTML . "<option value='" . $row['assignment_id'] . "'>" . $row['assignment'] . "</option>";
        }
        
        $HTML = $HTML . "</select>";
        
        return $HTML;
    }
    
    function createCourseSelector($courses){
        $HTML = "
        <select class='course_select' name='course'>";
        
        foreach($courses as $row){
            $HTML = $HTML . "<option value='" . $row['course_id'] . "'>" . $row['name'] . "</option>";
        }
        
        $HTML = $HTML . "</select>";
        
        return $HTML;
    }

    function createNavigation($isProf, $isTA){
        $HTML = "
            <nav class='leftNav' id='leftNav'>
                
                <ul title='Links'>
                    <li><a href='./index.php'>Home</a></li>";
        
        if($this->loggedIn){
            $HTML = $HTML . "<li><a href='./define.php'>Define Word</a></li>";
            $HTML = $HTML . "<li><a href='./join.php'>Join Course</a></li>";

        }
        if($isProf){
            $HTML = $HTML . "<li></li>";
            $HTML = $HTML . "<li><a href='./report.php'>Summary</a></li>";
            $HTML = $HTML . "<li><a href='./verifyDefinitions.php'>Verify Definitions</a></li>";
            $HTML = $HTML . "<li><a href='./hiddenDefinitions.php'>Hidden Definitions</a></li>";
            $HTML = $HTML . "<li></li>";
            $HTML = $HTML . "<li><a href='./verifyStudents.php'>Add Students</a></li>";
            $HTML = $HTML . "<li><a href='./createCourse.php'>Create Course</a></li>";
            $HTML = $HTML . "<li><a href='./createAssignment.php'>Create Assignment</a></li>";
            $HTML = $HTML . "<li><a href='./addTAs.php'>Add TAs to Course</a></li>";
        }
        if($isTA && !$isProf){
            $HTML = $HTML . "<li></li>";
            $HTML = $HTML . "<li><a href='./report.php'>Summary</a></li>";
            $HTML = $HTML . "<li><a href='./verifyDefinitions.php'>Verify Definitions</a></li>";
            $HTML = $HTML . "<li><a href='./hiddenDefinitions.php'>Hidden Definitions</a></li>";
            $HTML = $HTML . "<li></li>";
            $HTML = $HTML . "<li><a href='./verifyStudents.php'>Add Students</a></li>";
            $HTML = $HTML . "<li><a href='./createAssignment.php'>Create Assignment</a></li>";
        }
        
/*        $HTML = $HTML . "
                    <li><a href='./letter.php?l=A'>A</a></li>
                    <li><a href='./letter.php?l=B'>B</a></li>
                    <li><a href='./letter.php?l=C'>C</a></li>
                    <li><a href='./letter.php?l=D'>D</a></li>
                    <li><a href='./letter.php?l=E'>E</a></li>
                    <li><a href='./letter.php?l=F'>F</a></li>
                    <li><a href='./letter.php?l=G'>G</a></li>
                    <li><a href='./letter.php?l=H'>H</a></li>
                    <li><a href='./letter.php?l=I'>I</a></li>
                    <li><a href='./letter.php?l=J'>J</a></li>
                    <li><a href='./letter.php?l=K'>K</a></li>
                    <li><a href='./letter.php?l=L'>L</a></li>
                    <li><a href='./letter.php?l=M'>M</a></li>
                    <li><a href='./letter.php?l=N'>N</a></li>
                    <li><a href='./letter.php?l=O'>O</a></li>
                    <li><a href='./letter.php?l=P'>P</a></li>
                    <li><a href='./letter.php?l=Q'>Q</a></li>
                    <li><a href='./letter.php?l=R'>R</a></li>
                    <li><a href='./letter.php?l=S'>S</a></li>
                    <li><a href='./letter.php?l=T'>T</a></li>
                    <li><a href='./letter.php?l=U'>U</a></li>
                    <li><a href='./letter.php?l=V'>V</a></li>
                    <li><a href='./letter.php?l=W'>W</a></li>
                    <li><a href='./letter.php?l=X'>X</a></li>
                    <li><a href='./letter.php?l=Y'>Y</a></li>
                    <li><a href='./letter.php?l=Z'>Z</a></li>
*/
        $HTML = $HTML . "
                </ul>
            </nav>
            <div id='expand_nav' onclick='hideNav()'>
                <div>&#8942;</div>
            </div>
            ";
        return $HTML;
    }
    
    function createFooter(){
        return "<footer id='foot'>Copyright &copy; Dr. P</footer>";
    }
    
    //expects pdo results like array(0 => array( 'definition_id' => '3', 'term' => 'abcdefg', 'definition' => 'zyxwvutsrq', 'name' => 'CSE101', 'professor_id' => '2' ) )
    function createDefs($defs, $isProf, $isTA){
        $tableID = '"term_def_table"';
        $HTML = "<table class='term_def_table' id='term_def_table'>
                    <tr>
                        <th class='term_th' onclick='sortTable(0, " . $tableID . ")'>Term</th>
                        <th class='def_th' onclick='sortTable(1, " . $tableID . ")'>Definition</th>
                    </tr>";
        
        foreach($defs as $row){
            $HTML = $HTML . 
                "<tr class='term_def'>
                    <td class='term_td'>
                        <div class='td_div'>
                            <p>" . $row["term"] . "</p>
                            <p class='smaller_text'>Course: " . $row["name"] . "</p>";
            
            if(($isProf || $isTA) && ($row["professor_id"] == $this->id || $row["ta_1_id"] == $this->id || $row["ta_2_id"] == $this->id || $row["ta_3_id"] == $this->id)){
                $HTML = $HTML . "<a href=./hide.php?h=" . $row["definition_id"] . ">Hide Definition</a>";
            }
            
            $HTML = $HTML . 
                "</div>
                    </td>
                    <td class='def_td'>
                        <div class='td_div'>
                            <p>" . $row["definition"] . "</p>
                        </div>
                    </td>
                </tr>";
        }
        
        $HTML = $HTML . "</table>";
        return $HTML;
    }
    
    //expects pdo results like 
    //array(0 => array( 'course_id' => '1', 'name' => 'aaa101', 'description' => 'a class',
    //                  'date_created' => '1900-01-01', 'email' => 'email@email.email') )
    function createJoinCourses($courses){
        $tableID = '"join_course_table"';
        $HTML = "
            <form id='join_form' method='post' action='./join-post.php'> 
                <table class='join_course_table' id='join_course_table'>
                    <tr class='join_course_tr'>
                        <th class='join_course_th'>
                            <div class='td_div'>
                                <button type='submit'>Join Checked</button>
                            </div>
                        </th>
                        <th class='course_name_th'>
                            <div class='td_div' onclick='sortTable(1, " . $tableID . ")'>Course</div>
                        </th>
                        <th class='course_desc_th'>
                            <div class='td_div' onclick='sortTable(2, " . $tableID . ")'>Description</div>
                        </th>
                        <th class='prof_th'>
                            <div class='td_div' onclick='sortTable(3, " . $tableID . ")'>Professor</div>
                        </th>
                        <th class='course_created_th'>
                            <div class='td_div' onclick='sortTable(4, " . $tableID . ")'>Created</div>
                        </th>
                    </tr>";
        
        foreach($courses as $row){
            $HTML = $HTML . "
                    <tr class='join_course_tr'>
                        <td class='join_course_td'>
                            <div class='td_div'>
                                Join: <input type='checkbox' name='" . $row['course_id'] . "' value='Yes' />
                            </div>
                        </td>
                        <td class='course_name_td'>
                            <div class='td_div'>
                                <p>" . $row['name'] . "</p>
                            </div>
                        </td>
                        <td class='course_desc_td'>
                            <div class='td_div'>
                                <p>" . $row['description'] . "</p>
                            </div>
                        </td>
                        <td class='prof_td'>
                            <div class='td_div'>
                                <p>" . $row['last_name'] . ', ' . $row['first_name'] . "</p>
                            </div>
                        </td>
                        <td class='course_created_td'>
                            <div class='td_div'>
                                <p>" . $row['date_created'] . "</p>
                            </div>
                        </td>
                    </tr>";
        }
        
        $HTML = $HTML . "
                </table>
            </form>";
        return $HTML;
    }
    
    //expects pdo results like 
    //array(0 => array( 'course_id' => '1', 'name' => 'aaa101', 'description' => 'a class',
    //      'date_created' => '1900-01-01', 'date_joined' => '1900-01-01', 'email' => 'email@email.email') )
    function createMyCourses($courses){
        $tableID = '"my_course_table"';
        $HTML = "
            <table class='my_course_table' id='my_course_table'>
                <tr class='my_course_tr'>
                    <th class='course_name_th'>
                        <div class='td_div' onclick='sortTable(0, " . $tableID . ")'>
                            Course
                        </div>
                    </th>
                    <th class='course_desc_th'>
                        <div class='td_div' onclick='sortTable(1, " . $tableID . ")'>
                            Description
                        </div>
                    </th>
                    <th class='course_joined_th' onclick='sortTable(2, " . $tableID . ")'>
                        <div class='td_div'>
                            Joined
                        </div>
                    </th>
                    <th class='course_created_th' onclick='sortTable(3, " . $tableID . ")'>
                        <div class='td_div'>
                            Created
                        </div>
                    </th>
                    <th class='prof_th'>
                        <div class='td_div' onclick='sortTable(4, " . $tableID . ")'>
                            Professor
                        </div>
                    </th>
                </tr>";
        
        foreach($courses as $row){
            $closeCourse = '';
            if($row['am_i_prof'] == '1'){
                $closeCourse = "<a href='./closeCourse-post.php?c=" . $row['course_id'] . "'>Close Course</a>";
            }
            
            $HTML = $HTML . "
                <tr class='my_course_tr'>
                    <td class='course_name_td'>
                        <div class='td_div'>
                            <a href='./profile.php?c=" . $row['course_id'] . "'>" . $row['name'] . "</a>
                        </div>
                    </td>
                    <td class='course_desc_td'>
                        <div class='td_div'>
                            <p>" . $row['description'] . "</p>
                        </div>
                    </td>
                    <td class='course_joined_td'>
                        <div class='td_div'>
                            <p>" . $row['date_joined'] . "</p>
                        </div>
                    </td>
                    <td class='course_created_td'>
                        <div class='td_div'>
                            <p>" . $row['date_created'] . "</p>
                        </div>
                    </td>
                    <td class='prof_td'>
                        <div class='td_div'>
                            <p>" . $row['last_name'] . ', ' . $row['first_name'] . "</p>
                            <p>" . $closeCourse . "</p>
                        </div>
                    </td>
                </tr>";
        }
        
        $HTML = $HTML . "
                </table>
            </form>";
        return $HTML;
    }
    
    function createCourseCreationForm(){
        $HTML = "
        <form method='post' action='./createCourse-post.php' id='create_course_form'>
            <table>
                <tr>
                    <td class='create_course_label'><span>Course Name:</span></td>
                    <td class='create_course_input'><input type='text' name='course_name' required></td>
                </tr>
                <tr>
                    <td class='create_course_label'><span>Description:</span></td>
                    <td class='create_course_input'><textarea rows='3' name='course_description' form='create_course_form'></textarea></td>
                </tr>
            </table>
            <button type='submit'>Create Course</button>
        </form>";
        
        return $HTML;
    }
    
    function createAssignmentCreationForm(){    
        $ownedCourses = array();
        
        foreach ($this->courses as $row){
            if($row['professor_id'] == $this->id || $row['ta_1_id'] == $this->id || $row['ta_2_id'] == $this->id || $row['ta_3_id'] == $this->id){
                $ownedCourses[] = $row;
            }
        }
        
        $HTML = "
            <form method='post' action='./createAssignment-post.php' id='create_assignment_form'>
                <table>
                    <tr>
                        <td class='create_assignment_label'>Course</td>
                        <td class='create_assignment_input'>";
        
        $HTML = $HTML . $this->createCourseSelector($ownedCourses);
        
        $HTML = $HTML . "</td>
                    </tr>
                    <tr>
                        <td class='create_assignment_label'>Assignment Name:</td>
                        <td class='create_assignment_input'><input type='text' name='assignment_name' required></td>
                    </tr>
                    <tr>
                        <td class='create_assignment_label'># Submissions Required Per Student:</td>
                        <td class='create_assignment_input'><input type='number' name='amount_required' required></td>
                    </tr>
                    <tr>
                        <td class='create_assignment_label'>Year Due:</td>
                        <td class='create_assignment_input'>";
        
        $HTML = $HTML . "<select class='year' name='year'>
                            <option value='" . date('Y') . "'>" . date('Y') . "</option>";
        $HTML = $HTML . "   <option value='" . date('Y', strtotime('+1 year')) . "'>" . date('Y', strtotime('+1 year')) . "</option>
                        </select>";
        
        $HTML = $HTML . "</td>
                    </tr>
                    <tr>
                        <td class='create_assignment_label'>Month Due:</td>
                        <td class='create_assignment_input'>
                            <select class='month' name='month'>
                                <option value='1'>Jan</option>
                                <option value='2'>Feb</option>
                                <option value='3'>Mar</option>
                                <option value='4'>Apr</option>
                                <option value='5'>May</option>
                                <option value='6'>Jun</option>
                                <option value='7'>Jul</option>
                                <option value='8'>Aug</option>
                                <option value='9'>Sep</option>
                                <option value='10'>Oct</option>
                                <option value='11'>Nov</option>
                                <option value='12'>Dec</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class='create_assignment_label'>Day Due:</td>
                        <td class='create_assignment_input'>
                            <select class='day' name='day'>
                                <option value='1'>1</option>
                                <option value='2'>2</option>
                                <option value='3'>3</option>
                                <option value='4'>4</option>
                                <option value='5'>5</option>
                                <option value='6'>6</option>
                                <option value='7'>7</option>
                                <option value='8'>8</option>
                                <option value='9'>9</option>
                                <option value='10'>10</option>
                                <option value='11'>11</option>
                                <option value='12'>12</option>
                                <option value='13'>13</option>
                                <option value='14'>14</option>
                                <option value='15'>15</option>
                                <option value='16'>16</option>
                                <option value='17'>17</option>
                                <option value='18'>18</option>
                                <option value='19'>19</option>
                                <option value='20'>20</option>
                                <option value='21'>21</option>
                                <option value='22'>22</option>
                                <option value='23'>23</option>
                                <option value='24'>24</option>
                                <option value='25'>25</option>
                                <option value='26'>26</option>
                                <option value='27'>27</option>
                                <option value='28'>28</option>
                                <option value='29'>29</option>
                                <option value='30'>30</option>
                                <option value='31'>31</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class='create_assignment_label'>Time Due:</td>
                        <td class='create_assignment_input'>
                            <select class='time_select' name='time_select'>
                                <option value='1'>01:00</option>
                                <option value='2'>02:00</option>
                                <option value='3'>03:00</option>
                                <option value='4'>04:00</option>
                                <option value='5'>05:00</option>
                                <option value='6'>06:00</option>
                                <option value='7'>07:00</option>
                                <option value='8'>08:00</option>
                                <option value='9'>09:00</option>
                                <option value='10'>10:00</option>
                                <option value='11'>11:00</option>
                                <option value='12'>12:00</option>
                            </select>
                            <select class='am_pm_select' name='am_pm_select'>
                                <option value='pm'>PM</option>
                                <option value='am'>AM</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <button type='submit'>Create Assignment</button>
            </form>";
        
        return $HTML;
    }
    
    function createProfessorCourseSelector($courses){
        $HTML = "
        <select class='course_select' name='c'>
        ";
        
        if(!is_null($this->id)){
            $HTML = $HTML . "<option value='0'>All my courses</option>";
            foreach($courses as $row){
                $HTML = $HTML . "<option value='" . $row['course_id'] . "'>" . $row['name'] . "</option>";
            }
        }
        
        $HTML = $HTML . "</select>";
        
        return $HTML;
    }
    //d.id AS definition_id, d.term, d.definition, d.course_id, c.name AS course_name, 
    //DATE(d.date_created) AS date_created, d.assignment_id, a.description AS assignment, d.user_id, u.email
    function createDefinitionsToVerify($rows){
        $tableID = '"verify_table"';
        $HTML = "
            <form id='verfiy_def_form' method='post' action='./verifyDefinitions-post.php'> 
                <table class='verify_table' id='verify_table'>
                    <tr class='verify_tr'>
                        <th class='verify_th'>
                            <div class='td_div'>
                                <button type='submit'>Verify</button>
                            </div>
                        </th>
                        <th class='verify_term_th'>
                            <div class='td_div' onclick='sortTable(1, " . $tableID . ")'>Term</div>
                        </th>
                        <th class='verify_def_th'>
                            <div class='td_div' onclick='sortTable(2, " . $tableID . ")'>Definition</div>
                        </th>
                        <th class='verify_course_th'>
                            <div class='td_div' onclick='sortTable(3, " . $tableID . ")'>Course</div>
                        </th>
                        <th class='verify_info_th'>
                            <div class='td_div' onclick='sortTable(4, " . $tableID . ")'>Info</div>
                        </th>
                    </tr>";
        
        foreach($rows as $row){
            $HTML = $HTML . "
                    <tr class='verify_tr'>
                        <td class='verify_td'>
                            <div class='td_div'>
                                Verify:
                                <input type='checkbox' name='" . $row['definition_id'] . "' value='Yes' />
                            </div>
                        </td>
                        <td class='verify_term_td'>
                            <div class='td_div'>
                                <p>" . $row['term'] . "</p>
                            </div>
                        </td>
                        <td class='verify_def_td'>
                            <div class='td_div'>
                                <p>" . $row['definition'] . "</p>
                            </div>
                        </td>
                        <td class='verify_course_td'>
                            <div class='td_div'>
                                <p>" . $row['course_name'] . "</p>
                            </div>
                        </td>
                        <td class='verify_info_td'>
                            <div class='td_div'>
                                <p>Submitted: " . $row['date_created'] . "</p>
                                <p>Student: " . $row['last_name'] . ', ' . $row['first_name'] . "</p>
                                <p>Assignment: " . $row['assignment'] . "</p>
                            </div>
                        </td>
                    </tr>";
        }
        
        $HTML = $HTML . "
                </table>
            </form>";
        return $HTML;
    }
    
    //cmv.id, c.name AS course, u.email
    function createStudentsToVerify($rows){
        $tableID = '"verify_cm_table"';
        $HTML = "
            <form id='verify_cm_form' method='post' action='./verifyStudents-post.php'>
                <table class='verify_cm_table' id='verify_cm_table'>
                <tr class='verify_cm_tr'>
                        <th class='verify_cm_th'>
                            <div class='td_div'>
                                <button type='submit'>Add Students</button> 
                            </div>
                        </th>
                        <th class='verify_cm_email_th'>
                            <div class='td_div' onclick='sortTable(1, " . $tableID . ")'>Student</div>
                        </th>
                        <th class='verify_cm_msg_th'>
                            <div class='td_div'>
                            </div>
                        </th>
                        <th class='verify_cm_course_th'>
                            <div class='td_div' onclick='sortTable(3, " . $tableID . ")'>Course</div>
                        </th>
                    </tr>";
        
        foreach($rows as $row){
            $HTML = $HTML . "
                    <tr class='verify_cm_tr'>
                        <td class='verify_cm_td'>
                            <div class='td_div'>
                                Add Student to Course:
                                <input type='checkbox' name='" . $row['id'] . "' value='Yes' />
                            </div>
                        </td>
                        <td class='verify_cm_email_td'>
                            <div class='td_div'>
                                <p>" . $row['last_name'] . ', ' . $row['first_name'] . "</p>
                            </div>
                        </td>
                        <td class='verify_cm_msg_td'>
                            <div class='td_div'>
                                <p>Would Like to Join:</p>
                            </div>
                        </td>
                        <td class='verify_cm_course_td'>
                            <div class='td_div'>
                                <p>" . $row['course'] . "</p>
                            </div>
                        </td>
                    </tr>";
        }
        
        $HTML = $HTML . "
                </table>
            </form>";
        return $HTML;
    }
    
    //c.id AS course_id, c.name, c.description
    function createReportCourseList($courses){
        $HTML = "<ul title='courses'>";
        
        foreach($courses as $course){
            $HTML = $HTML . "<li><a href='./report.php?c=" . $course['course_id'] . "'>";
            $HTML = $HTML . $course['name'] . "</a><span> : " . $course['description'] . "</span></li>";
        }
        
        $HTML = $HTML . '</u>';
        
        return $HTML;
    }
    
    //good_submissions, late_submissions, course_id, course, assignment_id, assignment, amount_required, user_id, email, professor_id
    function createReport($data){
        
        $courses = array();
        $assignments = array();
        $students = array();
        foreach($data as $row){
            $courseID = $row['course_id'];
            $course = $row['course'];
            
            $courses[$courseID] = $course;
            $assignments[$courseID] = array();
            $students[$courseID] = array();
        }
        
        foreach($data as $row){
            $assignmentID = $row['assignment_id'];
            $assignment = $row['assignment'];
            $amount = $row['amount_required'];
            $courseID = $row['course_id'];
            $date_due = $row['date_due'];
            
            $assignments[$courseID][$assignmentID] = array('assignment' => $assignment, 'amount' => $amount, 'date_due' => $date_due);
            $students[$courseID][$assignmentID] = array();
        }
        
        foreach($data as $row){
            $userID = $row['user_id'];
            $email = $row['email'];
            $first = $row['first_name'];
            $last = $row['last_name'];
            $good = $row['good_submissions'];
            $late = $row['late_submissions'];
            $unverif = $row['unverified_submissions'];
            $courseID = $row['course_id'];
            $assignmentID = $row['assignment_id'];
            
            $students[$courseID][$assignmentID][$userID] = array('email' => $email, 'good' => $good, 'late' => $late, 'unverif' => $unverif, 'first' => $first, 'last' => $last,);
        }
        
        $HTML = "<div class='report'>
                    <div class='full_rpt_link'><a href='./report.php'>Full Report</a></div>";
        
            foreach($courses as $cid => $c){
                $HTML = $HTML . "
                    <div class='report-course-div'>Course: <a href='./report.php?c=" . $cid . "'>" . $c . "</a></div>
                    <table class='report-course-table'>
                    ";
                
                foreach($assignments[$cid] as $aid => $a){
                    $HTML = $HTML . "
                    <tr class='report-course-tr'>
                        <td class='report-spacer'>
                        </td>
                        <td class='report-course-td'>
                            <div class='report-assign-div'>Assignment: <a href='./report.php?a=" . $aid . "'>" . $a['assignment'] . "</a><br>Due Date: " . $a['date_due'] . "</div>
                            <table class='report-assign-table'>
                                <tr class='report-assign-tr'>
                                    <th class='report-spacer'></th>
                                    <th class='student'>Student</th>
                                    <th class='unverified'>Unverified</th>
                                    <th class='late'>Late</th>
                                    <th class='on-time'>On Time</th>
                                    <th class='required'>Required</th>
                                    <th class='left'>Remaining</th>
                                    <th class='future'></th>
                                </tr>";
                    
                    foreach($students[$cid][$aid] as $sid => $s){
                        if($sid != $this->id || ($s['good'] + $s['late']) > 0){
                            $HTML = $HTML . "
                                    <tr class='report-assign-tr'>
                                        <td class='report-spacer'></td>
                                        <td class='student'><a href='./report.php?s=" . $sid . "'>" . $s['last'] . ', ' . $s['first'] . "</a></td>
                                        <td class='unverified'>" . $s['unverif'] . "</td>
                                        <td class='late'>" . $s['late'] . "</td>
                                        <td class='on-time'>" . $s['good'] . "</td>
                                        <td class='required'>" . $a['amount'] . "</td>"; 
                            
                            if($a['amount']-$s['good'] <= 0){
                                $HTML = $HTML . "<td class='left'>0";
                            }
                            else{
                                $HTML = $HTML . "<td class='left' style='background:#ff9e9e;'>" . ($a['amount']-$s['good']);
                            }
                                            
                            $HTML = $HTML . "</td>
                                        <td class='future'></td>
                                    </tr>";
                        }
                    }

                    $HTML = $HTML . "
                            </table>
                        </td>
                    </tr>";
                }
                $HTML = $HTML . "</table>";
            }
        $HTML = $HTML . "</div>";
        return $HTML;
    }
    
    function createHiddenDefs($defs){
        $tableID = '"term_def_table"';
        $HTML = "<table class='term_def_table' id='term_def_table'>
                    <tr>
                        <th class='term_th' onclick='sortTable(0, " . $tableID . ")'>Term</th>
                        <th class='def_th' onclick='sortTable(1, " . $tableID . ")'>Definition</th>
                    </tr>";
        
        foreach($defs as $row){
            $HTML = $HTML . 
                "<tr class='term_def'>
                    <td class='term_td'>
                        <div class='td_div'>
                            <p>" . $row["term"] . "</p>
                            <p class='smaller_text'>Course: " . $row["name"] . "</p>
                            <a href=./unhide.php?h=" . $row["definition_id"] . ">Unhide Definition</a>
                        </div>
                    </td>
                    <td class='def_td'>
                        <div class='td_div'>
                            <p>" . $row["definition"] . "</p>
                        </div>
                    </td>
                </tr>";
        }
        
        $HTML = $HTML . "</table>";
        return $HTML;
    }
    
    //assignment_id, definition_id, term, definition, 
    //course, assignment, is_verified,
    //IF(a.date_due < d.date_created, 1, 0) AS is_late,
    //a.date_due, d.date_created, a.amount_required, fullname AS prof
    function createAssignmentsForCourse($data){
        $assignments = array();
        foreach($data as $row){
            $assignID = $row['assignment_id'];
            if(!isset($assignments[$assignID])){
                $assignments[$assignID] = array('date_due' => $row['date_due'], 'defs' => array(), 'amt' => $row['amount_required'], 'prof' => $row['prof'], 'desc' => $row['assignment'], 'amt_late' => $row['amt_late'], 'amt_ontime' => $row['amt_ontime'], 'amt_verified' => $row['amt_verified']);
            }
        }
        
        foreach($data as $row){
            $assignID = $row['assignment_id'];
            $defID = $row['definition_id'];
            $assignments[$assignID]['defs'][$defID] = array('term' => $row['term'], 'def' => $row['definition'], 'is_verified' => $row['is_verified'], 'is_late' => $row['is_late']);
        }
        
        $HTML = "<h2>Course: " . $data[0]['course'] . "</h2>";
        $HTML = $HTML . "<h2>Professor: " . $data[0]['prof'] . "</h2>";
        $HTML = $HTML . "<a class='full_rpt_link' href=./profile.php>Back To Courses</a>";
        $HTML = $HTML . "
            <table class='assign_tbl'>
                <tr class='assign_tr'>
                    <th class='assign_expand_th' onclick='expandAllProfileAssignments()'>
                        <div class='td_div'>
                            <p>View Submissions</p>
                        </div>
                    </th>
                    <th class='assign_desc_th'>
                        <div class='td_div'>
                            <p>Assignment Description</p>
                        </div>
                    </th>
                    <th class='assign_due_th'>
                        <div class='td_div'>
                            <p>Due Date</p>
                        </div>
                    </th>
                    <th class='assign_amt_th'>
                        <div class='td_div'>
                            <p>Required</p>
                        </div>
                    </th>
                    <th class='assign_amt_th'>
                        <div class='td_div'>
                            <p>Verified</p>
                        </div>
                    </th>
                    <th class='assign_amt_th'>
                        <div class='td_div'>
                            <p>On Time</p>
                        </div>
                    </th>
                    <th class='assign_amt_last_th'>
                        <div class='td_div'>
                            <p>Late</p>
                        </div>
                    </th>
                </tr>
            </table>";
        foreach($assignments as $id => $info){
            $param = '"assign_' . $id . '"';
            $HTML = $HTML . "
            <table class='assign_tbl'>
                <tr class='assign_tr'>
                    <td class='assign_expand' onclick='expandProfileAssignment(" . $param . ")'>
                        <div class='td_div'>
                            <p class='exp_icon' id='assign_" . $id . "_icon'>+</p>
                        </div>
                    </td>
                    <td class='assign_desc'>
                        <div class='td_div'>
                            <p>" . $info['desc'] . "</p>
                        </div>
                    </td>
                    <td class='assign_due'>
                        <div class='td_div'>
                            <p>" . $info['date_due'] . "</p>
                        </div>
                    </td>
                    <td class='assign_amt'>
                        <div class='td_div'>
                            <p>" . $info['amt'] . "</p>
                        </div>
                    </td>
                    <td class='assign_amt'>
                        <div class='td_div'>
                            <p>" . $info['amt_verified'] . "</p>
                        </div>
                    </td>
                    <td class='assign_amt'>
                        <div class='td_div'>
                            <p>" . $info['amt_ontime'] . "</p>
                        </div>
                    </td>
                    <td class='assign_amt_last'>
                        <div class='td_div'>
                            <p>" . $info['amt_late'] . "</p>
                        </div>
                    </td>
                </tr>
            </table>";
            
            $HTML = $HTML . "
                <table class='def_tbl' id='assign_" . $id . "'>
                    <tr class='def_tr'>
                        <th class='def_term_th' onclick='sortTable(0, " . $param . ")'>
                            <div class='td_div'>
                                <p>Term</p>
                            </div>
                        </th>
                        <th class='def_def_th' onclick='sortTable(1, " . $param . ")'>
                            <div class='td_div'>
                                <p>Definition</p>
                            </div>
                        </th>
                        <th class='def_verif_th' onclick='sortTable(2, " . $param . ")'>
                            <div class='td_div'>
                                <p>Verified?</p>
                            </div>
                        </th>
                        <th class='def_late_th' onclick='sortTable(3, " . $param . ")'>
                            <div class='td_div'>
                                <p>Late?</p>
                            </div>
                        </th>
                    </tr>";
            
            foreach($info['defs'] as $defID => $info){
                $term = $info['term'];
                $def = $info['def'];
                $is_verif = $info['is_verified'];
                $is_late = $info['is_late'];
                
                if($term == ''){
                    continue;
                }
                
                $HTML = $HTML . "
                        <tr class='def_tr'>
                            <td class='def_term'>
                                <div class='td_div'>
                                    <p>" . $term . "</p>
                                </div>
                            </td>
                            <td class='def_def'>
                                <div class='td_div'>
                                    <p>" . $def . "</p>
                                </div>
                            </td>
                            <td class='def_verif'>
                                <div class='td_div'>
                                    <p>" . $is_verif . "</p>
                                </div>
                            </td>
                            <td class='def_late'>
                                <div class='td_div'>
                                    <p>" . $is_late . "</p>
                                </div>
                            </td>
                        </tr>";
            }
            $HTML = $HTML . "</table>";   
        }
        
        return $HTML;
    }

    function createTAForm($data){
        
        
        $users = "";
        foreach($data[1] as $user){
            $users = $users . "<option value='";
            $users = $users . $user['name'];
            $users = $users . "'>";
        }
            
        $HTML = "
        <form id='ta_form' method='post' action='./addTAs-post.php'> 
                <table class='ta_table' id='ta_table'>
                    <tr class='ta_tr'>
                        <th class='ta_course_th'><button type='submit'>Submit</button></th>
                        <th class='ta1_th'>TA 1</th>
                        <th class='ta2_th'>TA 2</th>
                        <th class='ta3_th'>TA 3</th>
                    </tr>
                ";
        
        foreach($data[0] as $course){
            $HTML = $HTML . "
                        <tr class='ta_tr'>
                            <td class='ta_course_td'>
                                <p>";
            
            $HTML = $HTML . $course['name'];
            
            $HTML = $HTML . "
                                </p>
                            </td>
                            <td class='ta1_td'>
                                <input name='" . $course['id'] . "_ta1_inp' id='" . $course['id'] . "_ta1_inp' list='suggestions1' ";
            $HTML = $HTML . "value='";
            $HTML = $HTML . $course['ta1'];
            $HTML = $HTML . "'>
                                <datalist id='suggestions1'>";
            
            $HTML = $HTML . $users;
            
            $HTML = $HTML . "   </datalist>
                            </td>
                            <td class='ta2_td'>
                                <input name='" . $course['id'] . "_ta2_inp' id='" . $course['id'] . "_ta2_inp' list='suggestions2' ";
            $HTML = $HTML . "value='";
            $HTML = $HTML . $course['ta2'];
            $HTML = $HTML . "'>
                                
                                <datalist id='suggestions2'>";
            
            $HTML = $HTML . $users;
            
            $HTML = $HTML . "   </datalist>
                            </td>
                            <td class='ta3_td'>
                                <input name='" . $course['id'] . "_ta3_inp' id='" . $course['id'] . "_ta3_inp' list='suggestions3' ";
            $HTML = $HTML . "value='";
            $HTML = $HTML . $course['ta3'];
            $HTML = $HTML . "'>
                                
                                <datalist id='suggestions3'>";
            
            $HTML = $HTML . $users;
            
            $HTML = $HTML . "
                                </datalist>
                            </td>
                        </tr>";
        }
        
        $HTML = $HTML . "</table></form>";
        
        return $HTML;
    }
    
}

?>