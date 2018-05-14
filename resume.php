<?php
require_once('./base.php');
echo $htmlGen->createPageBeforeContent('Resume');
?>


    <a href='./images/JosephNorwoodResume2018.pdf' target='_blank'>PDF</a>
    <h3>Joseph Norwood</h3>
    <h3>2109 Maldon Pl. Austin, TX 78722</h3>
    <h3>JosephNorwood4@gmail.com</h3>
    <h3>Objective</h3>
        <p>To apply my knowledge of programming and databases to contribute in the creation and upkeep
of software products.</p>
    <h3>Education</h3>
        <p><b>Bachelor of Science, Computer Science</b> - May 2016</p>
        <p>Michigan State University. East Lansing, MI</p>
        <ul>
            <li>GPA: 3.6/4.0</li>
            <li>Classroom experience with C++, Python, Javascript, PHP</li>
        </ul>
    <h3>Experience</h3>
        <p><b>Great Lakes Wine &amp; Spirits. Highland Park, MI.</b> - June 2016 to February 2018</p>
        <ul>
            <li>Programmer Analyst</li>
            <li>Worked in Microsoft environment focusing on data and reporting</li>
            <li>Created a Data Warehouse using SQL, SSIS, and 3rd Party tools</li>
            <li>Assisted in standardizing reporting across all departments</li>
        </ul>
        <p><b>Consultants to Government and Industry (CGI). Lansing, MI.</b> - June 2015 to August 2015</p>
        <ul>
            <li>IT Consultant Intern</li>
            <li>Automated database/schema comparison tasks and reports using SQL, Java and Batch Scripting</li>
            <li>Performed various tasks to ensure the integrity of testing environments</li>
        </ul>
    <h3>Projects</h3>
        <p><b>Crowd-Sourced Scientific Terms and Definitions Website</b> - October 2017 to January 2018</p>
        <ul>
            <li>Created using PHP, Javascript, MySQL database and 3rd party hosting</li>
            <li>Created for Microbiology Professor at Michigan State University to use as a tool for collecting
and displaying student submissions</li>
            <li><a href='http://www.JosephNorwood.com/termsUnknown.php' target='_blank'>More Info and Links</a></li>
        </ul>
        <p><b>Dealership Inventory Solution</b> - January 2016 to May 2016</p>
        <ul>
            <li>Senior Capstone Project where teams are paired with a real world company (Urban Science)</li>
            <li>Online dashboards for dealership employees to view trends in stock and create purchase plans</li>
            <li>Created with PHP, Javascript, AngularJS, JQuery and C3/D3 graphing libraries</li>
            <li><a href='http://www.capstone.cse.msu.edu/2016-01/projects/urban-science/' target='_blank'>More Info</li>
        </ul>

<?php
echo $htmlGen->createPageAfterContent();
?>