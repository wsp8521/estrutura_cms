<?php

/**
 * <b>TamplateView.class</b>[HELPER MVC]
 * Descrição: classe responsavel pelo renderização da view segindo o padrão MVC
 * @author Wesley Santos Pereira 
 * @copyright (c) 2017, Wtec Soluções Web
 */
class TemplateView {

    private  $Data; //recebe os dados
    private  $Keys; //recebe as keys de um array
    private  $Value; //recebe os valores do array
    private  $Template; //recebe o aquivo que será renderizado

    /**
     * ****************************************
     * ************ PUBLICMETHODS *************
     * ****************************************
     */

    /**     *
     * <b>LoadTemplte</b>
     * Metodo responsavel em carregar o arquivo tpl.html
     * @param String $Template - carrega uma string file
     */
    public  function LoadTemplte($Template) {
        $this->Template = REQUIRE_PATH.DIRECTORY_SEPARATOR.'_tpl'.DIRECTORY_SEPARATOR.$Template;
        $this->Template = file_get_contents($this->Template . '.tpl.html');
        return $this->Template;
    }

    /**     *
     * <b>RenderTpl</b>
     * metodo responsavel pela rendereização da Veiws
     * @param Array $Data - recebe o arquivo contendo os dados
     * @param String $view  - recebe o arquvio tpl- 
     */
    public  function RenderTpl(array $Data, $view) {
        $this->setKeys($Data);
        $this->shwoView($view);
    }
    
    
    /** *
     * <b>IncludeTemplate</b>
     * Metodo responsavel pela inclusão de arquivo inc.php
     * @param String $File - nome do arquivo
     * @param array $Data - Dados
     */
    public  function IncludeTemplate($File, array $Data){
        extract($Data);
        require ("{$File}.inc.php");
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    //metodo responsavel em isolar o keys do array
    private  function setKeys(array $Data) {
        $this->Data = $Data;
        $this->Data['BASE']=BASE;
        $this->Keys = explode('&', '#' . implode('#&#', array_keys($Data)).'#');
        $this->Keys[] = '#BASE#';
    }

    //metodo responsavelm e obter o valor da array
    private  function shwoView($view) {
        $this->Template = $view;
        $this->Value = str_replace($this->Keys, array_values($this->Data), $this->Template);
         echo $this->Value;
        
    }

}
