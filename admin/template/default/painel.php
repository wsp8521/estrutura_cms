<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Painel de controle</title>
        <link href="<?= BASE ?>/admin/asset/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>

        <?php
        $this->LoadMenuPainel();
        ?>

        <?php
        $this->loadContentPainel($View, $Data)
        ?>
    </body>
</html>
