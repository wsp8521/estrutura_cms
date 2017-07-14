
<?php
if ($menu):
?>

<nav class="menuBar">
    <?php
    foreach ($menu as $m):
    extract($m);
    $base = BASE . '/painel/';
    echo "<p><a href='{$base}{$menu_file}'>{$menu_label}</a></p>";
    endforeach;
    ?>
    <p><a href="<?= BASE . '/painel/logoff' ?>">Logoff</a></p>
</nav>

<?php endif; ?>



