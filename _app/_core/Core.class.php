<?php

/**
 * <b>Core.class</b>[CONTROLLER]
 * Descrição: Classe responsavel em definir as rotas dos controladores
 * @author Wesley Santos Pereira 
 * @copyright (c) 2017, Wtec Soluções Web
 */
class Core {

//metodo responsavelm pela execução do controller
    public function RunController() {
        $getUrl = filter_input(INPUT_GET, 'url', FILTER_DEFAULT);
        $getUrl = isset($getUrl) ? $getUrl : '';
        

        //verificando a existencia do paramentro na url
        if (!empty($getUrl)):
            $param = array();
            $getUrl = explode('/', $getUrl);
            $currentController = $getUrl[0] . 'Controller'; //pegando o controller na url
            array_shift($getUrl);

            //pegando a action na url
            if (!empty($getUrl[0])):
                $ActionContoller = $getUrl[0];
                array_shift($getUrl);
            else:
                $ActionContoller = 'index';
            endif;

            //pegando os paramentros
            if (count($getUrl) > 0):
                $param = $getUrl;
            endif;
        else:
            $currentController = 'homeController';
            $ActionContoller = 'index';
            $param[]=null;
        endif;
        $controller = new $currentController(); //criando um novo objeteo
        call_user_func_array(array($controller, $ActionContoller), $param);
    }

}
