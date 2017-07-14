<?php

/**
 * <b>Config.class</b>[MODEL]
 * Descrição: Classe responsavel em carregar as definições de conigurações do site
 * no banco de dados.
 * @author Wesley Santos Pereira 
 * @copyright (c) 2017, Wtec Soluções Web
 */
class Config {

    const table = 'wsp_config';

    /**
     * ****************************************
     * ************ PUBLICMETHODS *************
     * ****************************************
     */

    /**     *
     * <b>getConfig</b>
     * metodo responsavem em carregar as configuraçoes do banco
     * @return array de configurações
     */
    public function getConfig() {
        $newArray=array();
        $read = new Read;
        $read->exeRead(self::table);
        if ($read->getResult()):
            //criando um novo array
            foreach ($read->getResult() as $conf):
                $newArray[$conf['config_name']] = $conf['config_value'];
            endforeach;
        endif;
        return $newArray;
    }

    
    /**     *
     * <b>getConfigModulos</b>
     * metodo responsavem em carregar as configuraçoes dos modulos do banco
     * @return array de configurações
     */
    public function getConfigModulos() {
        $newArray=array();
        $read = new Read;
        $read->exeRead('wsp_mod');
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
