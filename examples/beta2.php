<? require_once('../wall_prepend.php'); ?>
<wall:document>
<wall:xmlpidtd />

<wall:head>
    <wall:title>My Document</wall:title>
</wall:head>
<wall:body>

    <wall:h1>New features in beta2</wall:h1>

    <wall:h2>Standalone img element</wall:h2>

    <wall:img alt="Alternate text" src="pix/heart_big.gif" />

    <wall:h2>Img with alternate_img elements</wall:h2>
    
    <?
    $id = isset($_GET['id']) ? $_GET['id'] : false;
    ?>
    
    <wall:block>
        <wall:a href="beta2.php">Default img (clouds)</wall:a><wall:br />
        <wall:a href="beta2.php?id=1">Test 1st alternate_img (dice)</wall:a><wall:br />
        <wall:a href="beta2.php?id=2">Test 2nd alternate_img (headphones)</wall:a>
    </wall:block>
    
    <wall:img alt="Alternate text" src="pix/clouds_big.gif">
        <wall:alternate_img test="<? echo (int)($id == 1)?>" src="pix/dice_big.gif" />
        <wall:alternate_img test="<? echo (int)($id == 2)?>" src="pix/headphones_big.gif" />
    </wall:img>


    <wall:h2>Headings:</wall:h2>

    <wall:h3>Heading 3</wall:h3>
    <wall:h4>Heading 4</wall:h4>
    <wall:h5>Heading 5</wall:h5>
    <wall:h6>Heading 6</wall:h6>
    
    <wall:h2>hr</wall:h2>
    
    <wall:hr />
    
    <wall:block xhtmlClass="class" xhtmlId="id">xhtmlClass and xhtmlId attributes set</wall:block>

</wall:body>
</wall:document>
