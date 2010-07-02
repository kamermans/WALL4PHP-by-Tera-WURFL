<?php include_once('../wall_prepend.php'); ?><wall:document>
<wall:xmlpidtd />

<wall:head>
   <wall:title>Form 1</wall:title>
</wall:head>

<wall:body>
 <wall:form action="url" method="post">
   <!-- Greatly simplified under the assumption that -->
   <!-- WML is not supported -->
    Economy or Business:<br/>
    <input type="radio" name="course" value="economy" checked="checked"/>E
    <input type="radio" name="course" value="business"/>B<wall:br />

    Add comment: <wall:br />
    <textarea name="comment" rows="2" cols="10"></textarea>
    <wall:br />
    <input type="hidden" name="user_id" value="bhgfd65488769" />
    <input type="submit" value="Next"/>
 </wall:form>
</wall:body>
</wall:document>