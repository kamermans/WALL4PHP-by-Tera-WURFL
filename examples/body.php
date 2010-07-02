<?php include_once('../wall_prepend.php'); ?><wall:document><wall:xmlpidtd />

<wall:head>
  <wall:title enforce_title="true">My Document</wall:title>
   <!--sent to all devices as it is -->
   <meta name="value" content="value" />
</wall:head>
<wall:body>
 <wall:block>
UA :
<wall:marquee>
   <?php echo getenv('HTTP_USER_AGENT'); ?>
</wall:marquee>
   <wall:br />
  Body part 2
 </wall:block>
</wall:body>
</wall:document>