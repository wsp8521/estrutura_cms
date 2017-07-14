<?php

/**
 * <b>Menu.class</b>[MODEL]
 * Descrição: classe responsavel pelo gerenciamento dos menus do Front End e painel
 * @author Wesley Santos Pereira 
 * @copyright (c) 2017, Wtec Soluções Web
 */
class Menu {

    private $MenuPainel;
    private $Level; //nivel do usuáiro. 1 - Assistente/ 2 - editor /3 - administrador

    /**
     * ****************************************
     * ************ PUBLICMETHODS *************
     * ****************************************
     */

    /**     *
     * <b>getMenu</b>
     * metodo responsavel pela listagem de menus cadastrado no banco. 
     * @return array -  retorna os menus que farão parte do font end
     */
    public function getMenu() {
        $read = new Read;
        $read->exeRead('wsp_menubar');
        if ($read->getResult()):
            return $read->getResult();
        endif;
    }

    /**     *
     * <b>getMenuPainel</b>
     * metodo responsavel pela listagem de menus cadastrado no banco. 
     * @return array -  retorna os menus que farão parte do painel de acordo com o nivel do usuario
     */
    public function getMenuPainel() {
        $ses = new Login;
        $read = new Read;
        $this->Level = (int) $ses->getLevel();
        if ($ses->CheckLogin()): //permite que o menu seja exibido apenas se for iniciado uma sesão;
            $join = "SELECT * FROM wsp_menubar_painel u INNER JOIN wsp_permission_page p ON u.id_menu=p.per_id_page WHERE user_nivel=:n";
            if ($this->Level == 3):
                $read->exeRead('wsp_menubar_painel');
            else:
                $read->FullRead($join, "n={$this->Level}");
            endif;
            if ($read->getResult()):
                $this->MenuPainel = $read->getResult();
            endif;
        endif;
        return $this->MenuPainel;
        
    } 

}
