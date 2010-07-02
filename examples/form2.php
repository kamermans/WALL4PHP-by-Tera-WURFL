<?php include_once('../wall_prepend.php'); ?><wall:document>
<wall:xmlpidtd />

<wall:head>
   <wall:title>Form 2</wall:title>
</wall:head>

<wall:body>
 <wall:form action="url" method="post" enable_wml="true">
Pin Code:
   <wall:input type="text" name="pincode" value="" format="NNNN" maxlength="4" />
<wall:br />
Choose:
   <wall:select title="Day" name="day">
      <wall:option value="11/28/04">Yesterday
      </wall:option>
      <wall:option value="11/29/04" selected="selected">Today
      </wall:option>
      <wall:option value="11/30/04">Tomorrow
      </wall:option>
   </wall:select>
<wall:br />
  <wall:input type="hidden" name="session" value="gfsa87837" />

  <wall:input type="submit" value="Go" />
 </wall:form>
</wall:body>
</wall:document>
