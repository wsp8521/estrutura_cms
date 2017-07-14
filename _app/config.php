<?php

/* definição do abimente para fins de conexção do banco de dados
 * development - ambiente de desenvolvimento
 * production - ambiente de produção
 *  */
define('AMBIENTE', 'development');

if (AMBIENTE == 'development'):
    /*     * *CONFIGURAÇÃO DE ACESSO AO BANCO DE DADOS** */
    define('HOST', 'localhost');
    define('DBNAME', 'wtec_cms');
    define('USER', 'root');
    define('PASS', '');
else:
    /*     * *CONFIGURAÇÃO DE ACESSO AO BANCO DE DADOS** */
    define('HOST', 'localhost');
    define('DBNAME', '');
    define('USER', '');
    define('PASS', '');
endif;



/* * *CONFIGURAÇÃO DE E-MAIL** */
define('EMAILUSER', 'atendimento@lojaconstrucenter.com.br');
define('EMAILPASS', 'Wsp@8521');
define('EMAILPORT', '587');
define('EMAILHOST', 'mail.lojaconstrucenter.com.br');


/* * *CONFIGURAÇÃO DO SITE** */
define('BASE', 'http://wtecinformatica.com'); //base do site
define('THEME', 'cidadeonline'); //tema do site
//Anexar e Inclur!
define('INCLUDE_PATH', BASE . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . THEME);
define('REQUIRE_PATH', 'themes' . DIRECTORY_SEPARATOR . THEME);

//carregamento automatico de classe
spl_autoload_register(function($Class) {

    $cDir = ['_core', 'controller', 'model', '_conn', 'helpers']; //pasta de armazenamento dos codigos
    $iDir = null;
    foreach ($cDir as $dirName):
        if (!$iDir && file_exists(__DIR__ . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $Class . ".class.php") && !is_dir(__DIR__ . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $Class . ".class.php")):
            require_once(__DIR__ . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $Class . ".class.php");
            $iDir = true;
        endif;
    endforeach;
    ;
    if (!$iDir):
        trigger_error("Não foi possível incluir {$Class}.class.php", E_USER_ERROR);
        die;
    endif;
});

// TRATAMENTO DE ERROS #####################
//CSS constantes :: Mensagens de Erro
define('WS_ACCEPT', 'accept');
define('WS_INFOR', 'infor');
define('WS_ALERT', 'alert');
define('WS_ERROR', 'error');

//WSErro :: Exibe erros lançados :: Front
function WSP_ERRO($ErrMsg, $ErrNo, $ErrDie = null) {
    $CssClass = ($ErrNo == E_USER_NOTICE ? WS_INFOR : ($ErrNo == E_USER_WARNING ? WS_ALERT : ($ErrNo == E_USER_ERROR ? WS_ERROR : $ErrNo)));
    echo "<p class=\"trigger {$CssClass}\">{$ErrMsg}</p>";

    if ($ErrDie):
        die;
    endif;
}

//PHPErro :: personaliza o gatilho do PHP
function PHPErro($ErrNo, $ErrMsg, $ErrFile, $ErrLine) {
    $CssClass = ($ErrNo == E_USER_NOTICE ? WS_INFOR : ($ErrNo == E_USER_WARNING ? WS_ALERT : ($ErrNo == E_USER_ERROR ? WS_ERROR : $ErrNo)));
    echo "<p class=\"trigger {$CssClass}\">";
    echo "<b>Erro na Linha: #{$ErrLine} ::</b> {$ErrMsg}<br>";
    echo "<small>{$ErrFile}</small>";
    echo "<span class=\"ajax_close\"></span></p>";

    if ($ErrNo == E_USER_ERROR):
        die;
    endif;
}

//set_error_handler('PHPErro');





