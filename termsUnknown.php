<?php
require_once('./base.php');
echo $htmlGen->createPageBeforeContent('Terms Unknown');
?>

<h4>This site was created as a way to crowd source scientific definitions encountered in scientific journal articles. Content created by students for students.</h4>

<p>Professors can assign students to define words while reading a scientific journal in order to help them better understand the reading. The Professor then verifies the definitions and they are displayed publicly for others to view when they find an unfamilar term.</p>


<p>Log in to Demo Site as a student or professor to try it out</p>
<table>
    <tr>
        <th>Email</th>
        <th>&nbsp;</th>
        <th>Password</th>
    </tr>
    <tr>
        <td>prof@school.edu</td>
        <td>&nbsp;</td>
        <td>aaa111</td>
    </tr>
    <tr>
        <td>student@school.edu</td>
        <td>&nbsp;</td>
        <td>aaa111</td>
    </tr>
</table>

<p>Demo Site:&nbsp;<a href='./tu/' target='_blank'>Link</a></p>

<p>Official Site:&nbsp;<a href='http://www.TermsUnknown.com' target='_blank'>Link</a></p>



<?php
echo $htmlGen->createPageAfterContent();
?>