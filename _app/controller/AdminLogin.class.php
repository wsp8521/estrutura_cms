<?php

/**
 * <b>AdminLogin</b>[CONTROLLER]
 * Descrição: classe responsavel pelo controle de autenticação de usuáiro bem como permissão de 
 * acesso aos butoens e páginas
 * @author Wesley Santos Pereira 
 * @copyright (c) 2017, Wtec Soluções Web
 */
class AdminLogin extends Login {

    private $Data;
    private $Result;

    /**
     * ****************************************
     * ************ PUBLICMETHODS *************
     * ****************************************
     */
    
    /** *
     *<b>getLogin</b> 
     * captura os dados do formulário login e faz a autenticação
     */
    public function getLogin() {
        $this->Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (!empty($this->Data['sendForm'])):
            $this->exeLogin($this->Data);
            if (!$this->getResult()):
                WSP_ERRO($this->getError()[0], $this->getError()[1]);
            else:
                header('location: ' . BASE . '/painel');
            endif;

        endif;
    }

    /**     *
     * <b>checkSession</b>
     * Metodo de segurança - verifica se foi inicializado uma sessão, caso não tenha sido retorna para o login
     */
    public function checkSession() {
        if (!$this->CheckLogin()):
            header('location: ' . BASE . '/painel/login');      
        endif;
    }
    
       /**     *
     * <b>redirectPainel</b>
     * Metodo de segurança - verifica se foi inicializado uma sessão, tenha sido, redirecionar par ao painel 
        * caso o usuario digite na barra de endereço painel/login;
     */
    public function redirectPainel() {
        if ($this->CheckLogin()):
            header('location: ' . BASE . '/painel');      
        endif;
    }
    
    
    /** *
     * <b>ShowButton</b>
     * metodo responsavel em exibir os botoes de acordo com o usuário
     *
     */
      public function ShowButton(){
        $read = new Read;
        $newArray = array();
        $where = 'WHERE niver_user=:n ';
        $read->exeRead('wsp_permission_button', $where,"n={$this->getLevel()}");
        if($read->getResult()):
            foreach ($read->getResult() as $b):
            $newArray[$b['button_action']] = $b['value_buttom'];
            endforeach;
            return $newArray;
        endif;
    }
    
    
    
  
}
