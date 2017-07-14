<?php

/**
 * <b>homeController.class</b>[TIPO]
 * Descrição: 
 * @author Wesley Santos Pereira 
 * @copyright (c) 2017, Wtec Soluções Web
 */
class homeController extends Controller {

    /**
     * ****************************************
     * ************ PUBLICMETHODS *************
     * ****************************************
     */
    public function index() {


        $this->LoadTemplate('home', array());
    }

  
}
