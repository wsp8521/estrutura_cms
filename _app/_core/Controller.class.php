<?php

/**
 * <b>Controller.class</b>[CONTROLLER]
 * Descrição: classe responsavel pelo controle da View e do Model
 * @author Wesley Santos Pereira 
 * @copyright (c) 2017, Wtec Soluções Web
 */
class Controller {

    private $Config;
    private $Mod; //configurações dos modulos

    function __construct() {
        $config = new Config;
        $this->Config = $config->getConfig();
        $this->Mod = $config->getConfigModulos();
    }

    /**
     * ****************************************
     * ****** CONTROLLERS DO FRONT END ********
     * ****************************************
     */

    /**     *
     * <b>Loadview</b>
     * metodo responsavel pelo carregamento da view no template
     * @param array $View recebe os dados
     * @param type $Data - recebe o arquivo template
     */
    protected function LoadTemplate($View, array $Data = null) {
        $path = 'template' . DIRECTORY_SEPARATOR . $this->Config['template_site'] . DIRECTORY_SEPARATOR . 'index.php';
        if (isset($Data)) :
            extract($Data);
        endif;

        if (file_exists($path) && !is_dir($path)):
            require $path;
        else:
            WSP_ERRO("template {$this->Config['template_site']} não existe", WS_INFOR);
        endif;
    }

    /**     *
     * <b>loadContentView</b>
     * método responsavel em carregar o conteúdo da view no template
     * @param array $view - recebe dados
     * @param string $Data - recebe uma view
     */
    protected function loadContentView($view, array $Data = null) {
        $path = 'template' . DIRECTORY_SEPARATOR . $this->Config['template_site'] . DIRECTORY_SEPARATOR . $view . '.php';
        if (isset($Data)) :
            extract($Data);
        endif;

        if (file_exists($path) && !is_dir($path)):
            require ($path);
        else:
            WSP_ERRO("template {$this->Config['template_site'] } não existe", WS_INFOR);
        endif;
        
    }

    /**
     * ****************************************
     * ******** CONTROLLERS DO PAINEL**********
     * ****************************************
     */

    /**     *
     * <b>LoadTemplatePainel</b>
     * metodo responsavel pelo carregamento da view no template do painel
     * @param array $View recebe os dados
     * @param type $Data - recebe o arquivo template
     */
    public function LoadTemplatePainel($View, array $Data = null) {
        $path = 'admin' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . $this->Config['template_painel'] . DIRECTORY_SEPARATOR . 'painel.php';
        if (isset($Data)) :
            extract($Data);
        endif;
        if (file_exists($path) && !is_dir($path)):
            require $path;
        else:
            WSP_ERRO("template {$this->Config['template_painel']} não existe", WS_INFOR);
        endif;
    }

    /**     *
     * <b>loadContentPainel</b>
     * método responsavel em carregar o conteúdo da view no template do painel
     * @param array $view - recebe dados
     * @param string $Data - recebe uma view
     */
    protected function loadContentPainel($view, array $Data = null) {
        $path = 'admin' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . $this->Config['template_painel'] . DIRECTORY_SEPARATOR . $view . '.php';
        if (isset($Data)) :
            extract($Data);
        endif;

        if (file_exists($path) && !is_dir($path)):
            require ($path);
        else:
            WSP_ERRO("template {$this->Config['template_painel'] } não existe", WS_INFOR);
        endif;
    }

    /**
     * ****************************************
     * ******* CONTROLLERS DOs MENUS***********
     * ****************************************
     */
    
    
   //metodo responsavel pelo carregamento do menu no front-end 

     public function LoadMenu() {
        $dados = ['menu'];

        $this->loadContentView('inc/menu', $dados);
    }
    
    //metodo responsavel pelo carregamento do menu no painel
    public function LoadMenuPainel() {
       $result = array();
       $getMenu = new Menu;
       $result['menu']=$getMenu->getMenuPainel();
        $this->loadContentPainel('inc/menu', $result);
    }

    /**
     * ****************************************
     * ******* CONTROLLERS DOs MODULOS*********
     * ****************************************
     */
    /**     *
     * <b>LoadTemplateMod</b>
     * metodo responsavel pelo carregamento da view no template dos modulos
     * @param array $View recebe os dados
     * @param type $Data - recebe o arquivo template
     */
    protected function LoadTemplateMod($View, array $Data = null) {
        $path = 'modulos' . DIRECTORY_SEPARATOR . $this->Mod['mod_name'] . DIRECTORY_SEPARATOR . $this->Mod['mod_name'] . '.php';
        if (file_exists($path) && !is_dir($path)):
            require $path;
        else:
            WSP_ERRO("modulos {$this->Config['template_painel']} não existe", WS_INFOR);
        endif;
    }

    /**     *
     * <b>loadContentMod</b>
     * método responsavel em carregar o conteúdo da view no template do painel
     * @param array $view - recebe dados
     * @param string $Data - recebe uma view
     */
    protected function loadContentMod($view, array $Data = null) {
        $path = 'modulos' . DIRECTORY_SEPARATOR . $this->Mod['mod_name'] . DIRECTORY_SEPARATOR . $view . '.php';
        if (isset($Data)) :
            extract($Data);
        endif;

        if (file_exists($path) && !is_dir($path)):
            include ($path);
        else:
            WSP_ERRO("modulos {$this->Mod['mod_name']} não existe", WS_INFOR);
        endif;
    }
}
