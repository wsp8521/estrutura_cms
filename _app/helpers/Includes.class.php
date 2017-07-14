<?php

/**
 * <b>Includes</b>[TIPO]
 * Descrição: 
 * @author Wesley Santos Pereira 
 * @copyright (c) 2017, Wtec Soluções Web
 */
class Includes {
    /**
     * ****************************************
     * ************ PUBLICMETHODS *************
     * ****************************************
     */

    /**     *
     * <b>header</b>
     * inserir o header no documento web
     */
    public static function header() {
        require_once'_app/view/inc/header.php';
        ;
    }

    /**     *
     * <b>footer</b>
     * inserir o footer no documento web
     */
    public static function footer() {
        require_once '_app/view/inc/footer.php';
        ;
    }

    public static function MenuBar() {
        $read = new Read;
        $read->exeRead('wsp_categoria');
        if ($read->getResult()):
            return $read->getResult();
        endif;
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
}
