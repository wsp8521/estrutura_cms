<?php

/**
 * <b>painelController.class</b>[CONTROLLER]
 * Descrição: classe responsavel pelo conrtrole do painel
 * @author Wesley Santos Pereira 
 * @copyright (c) 2017, Wtec Soluções Web
 */
class painelController extends Controller {

    private $methodLogin; //instancia a classe AdminLogin

    /**
     * ****************************************
     * ************ PUBLICMETHODS *************
     * ****************************************
     */
    //home do painel

    public function index() {
        $this->getMethod()->checkSession();  //protegendo o painel contra acesso sem autenticação
        $result = [
            'showButtom' => $this->getMethod()->ShowButton(),
            'nivel' => $this->getMethod()->getLevel(),
        ];

        $this->LoadTemplatePainel('home', $result);
    }

    //page
    public function page() {
        $this->getMethod()->checkSession();  
        $this->LoadTemplatePainel('Adminpage/page', array());
    }

    //cat
    public function cat() {
        $result = array();
        $this->getMethod()->checkSession(); 
        //verificando se o usuário tem permissão para acessar a página
        if($this->getMethod()->getLevel()==1): //verifica se o usuário é assistente
            $result = ['msgErro'=>'você não tem pemissão para acessar essa área'];
        else:
             $result = ['msgErro'=>null];
        endif;


        $this->LoadTemplatePainel('Admincat/cat',$result);
    }

    //post
    public function post() {
        $this->getMethod()->checkSession();  
        $this->LoadTemplatePainel('Adminpost/post', array());
    }

    //user
    public function user() {
        $this->getMethod()->checkSession();  
        $this->LoadTemplatePainel('Adminuser/user', array());
    }

    //user
    public function config() {
        $this->getMethod()->checkSession();  
        //verificando se o usuário tem permissão para acessar a página
        if($this->getMethod()->getLevel()!=3): //verifica se o usuário não é administrador
            $result = ['msgErro'=>'você não tem pemissão para acessar essa área'];
        else:
             $result = ['msgErro'=>null];
        endif;
        $this->LoadTemplatePainel('Adminconfig/config', $result);
    }

    //login
    public function login() {
        $this->getMethod()->getLogin();
        $this->getMethod()->redirectPainel();
        $this->LoadTemplatePainel('../../login', array());
    }

    //logoff
    public function logoff() {
        if ($this->getMethod()->CheckLogin()):
            unset($_SESSION['user_login']);
            header('location: ' . BASE . '/painel/login');
        endif;
    }

    /**
     * ****************************************
     * ************ PRIVATE METHOD ************
     * ****************************************
     */
    //recebe os metodos da classe AdminLogin
    private function getMethod() {
        $this->methodLogin = new AdminLogin;
        return $this->methodLogin;
    }

}
