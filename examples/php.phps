<?php include_once('../wall_prepend.php'); ?><wall:document><wall:xmlpidtd />

<wall:head>
    <wall:title>My Document</wall:title>
</wall:head>
<wall:body>

    <wall:h1>PHP integration example</wall:h1>
    
    <wall:block>This example is not intended to teach you PHP or new coding techniques. I assume that you have experience in PHP programming.</wall:block>
    
    <wall:block>What time is it? Something like <wall:b><?php echo date('r')?></wall:b>.</wall:block>
    
    <wall:h2>Loops and conditionals</wall:h2>
    
    <wall:menu>
        <?php for ($i = 1; $i < 5; $i++) { ?>
        <wall:a href="php.php?id=<?php echo $i?>">Click me (#<?php echo $i?>)</wall:a>
        <?php } ?>
    </wall:menu>
    
    <?php if (isset($_GET['id'])) { ?>
    <wall:block>You chose menu item number <?php echo $_GET['id']?>.</wall:block>
    <?php } ?>
    
    <wall:h2>Retreiving WURFL capabilities from WALL object</wall:h2>
    
    <wall:block>
    WALL thinks, that your user agent string is "<?php echo $wall->ua?>"<wall:br />
    PHP thinks, that your user agent string is "<?php echo getenv('HTTP_USER_AGENT')?>"<wall:br />
    Your device <?php echo $wall->getCapa('wbmp') ? 'supports' : 'does not support'?> WBMPs<wall:br />
    Your device <?php echo $wall->getCapa('flash_lite') ? 'supports' : 'does not support'?> Flash Lite<wall:br />
    Your device <?php echo $wall->getCapa('basic_authentication_support') ? 'supports' : 'does not support'?> basic HTTP authentication<wall:br />
    Your device's preferred markup is "<?php echo $wall->getCapa('preferred_markup')?>"<wall:br />
	Your device's WURFL ID is: <wall:Wurfl_device_id /><wall:br />
    </wall:block>
    
    <wall:h2>Formatting text</wall:h2>
    
    <?php
    $text = 'Originally this text had enters.
Like this.

Or this.
They were automatically replaced with &lt;wall:br /&gt; elements, which further are replaced with corresponding line breaks.';

    echo str_replace("\n", '<wall:br />', $text);

    ?>

</wall:body>
</wall:document>