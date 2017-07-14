<html>
    <head>
        <meta charset="UTF-8">
        <title><?=$this->Config['name_site']?></title>
    </head>
    <body >
        
         <?php
        $this->LoadMenu();
        ?>
        <?php
        $this->loadContentView($View, $Data);
        ?>
    </body>
</html>
