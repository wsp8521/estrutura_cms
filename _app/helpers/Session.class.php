<?php

/**
 * <b>Session.class</b>[HELPER]
 * Descrição: classe autocontida responsavel pelo gerenciamento de estatisticas,
 * sessões e controle de trafego do sistema
 * @author Wesley Santos Pereira 
 * @copyright (c) 2017, Wtec Soluções Web
 */
class Session {

    private $Date; //recebe o dia de acesso
    private $Cache; //recebe o tempo de sessão;
    private $Traffic; //trafego de sistema
    private $Browser; //recebe os navegadores de onde o sistema esta sendo acessado

    //metodo construtor responsavel pela inicialização da sessão

    function __construct($Cache = null) {
        session_start();
        $this->checkSession($Cache);
    }

    //metodo resonsalve pela verificação do metodos da classe
    private function checkSession($cache = null) {
        $this->Date = date('Y-m-d'); //recebe a data da sessão
        $this->Cache = $cache ? $cache : 20; //recebe o tempo da sesessão. valor padrão = 20 segundos
        if (empty($_SESSION['user_online'])): //verifica se foi iniciada uma sessão. se True cria a sessão
            $this->setTraffic(); //cria um novo trafico
            $this->setSession(); //cria uma nova sessão
            $this->setBrowser(); //inicia a contagem dos dados do navegador
            $this->setUser();

        else: //caso a sessão esteja inicializada, atualiza sessao e trafico
            $this->updateTraffic(); //atualizando o trafego
            $this->updateSession(); //atualizando a sessão
            $this->checkBrowser();
            $this->updateUser(); //atualiza dados do usuário
        endif;
        $this->Date = null;
    }

    /*
     * ***************************************
     * ********   SESSÃO DO USUÁRIO   ********
     * ***************************************
     */

    //metodo responsalve em iniciar uma sessão utilizando a tabela ws_siteviews_online
    private function setSession() {
        $_SESSION['user_online'] = [
            'online_session' => session_id(),
            'online_startview' => date('Y-m-d H:i:s'),
            'online_endview' => date('Y-m-d H:i:s', strtotime("+{$this->Cache}minutes")),
            'online_ip' => filter_input(INPUT_SERVER, 'SERVER_ADDR', FILTER_VALIDATE_IP),
            'online_url' => filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_DEFAULT),
            'online_agent' => filter_input(INPUT_SERVER, 'HTTP_USER_AGENT'),
        ];
    }

    //metodo responsavel pela atualização da sessão
    private function updateSession() {
        //atualizando o endview
        $_SESSION['user_online']['online_endview'] = date('Y-m-d H:i:s', strtotime("+{$this->Cache}minutes"));
        $_SESSION['user_online']['online_url'] = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_DEFAULT);
    }

    /*
     * ***************************************
     * *** USUÁRIOS, VISITAS, ATUALIZAÇÕES ***
     * ***************************************
     */

    //metode resposanvel em inicializar um trafico e registrar na tabela ws_siteviews
    private function setTraffic() {
        $this->getTraffic();
        if (!$this->Traffic): //verifica se há trafego, se não hover, cria o trafego do dia
            $traffic = ['siteviews_date' => $this->Date, 'siteviews_users' => 1, 'siteviews_views' => 1, 'siteviews_pages' => 1];
            $insert = new Create;
            $insert->exeCreate('ws_siteviews', $traffic);
        //se existir o trafego, atualiza os dados
        else:
            if (!$this->getCoockie()): //verifica se há o coockie, se não tiver, cria
                $trafficc = ['siteviews_users' => $this->Traffic['siteviews_users'] + 1, 'siteviews_views' => $this->Traffic['siteviews_views'] + 1, 'siteviews_pages' => $this->Traffic['siteviews_pages'] + 1];
            else:
                $trafficc = ['siteviews_views' => $this->Traffic['siteviews_views'] + 1, 'siteviews_pages' => $this->Traffic['siteviews_pages'] + 1];
            endif;
            //plicando atualizações do traffico
            $updateTraffic = new Update;
            $updateTraffic->exeUpdate('ws_siteviews', $trafficc, 'WHERE siteviews_date = :date', "date={$this->Date}");
        endif;
    }

    //atualização do trafego da pagesviews
    private function updateTraffic() {
        $this->getTraffic();
        $trafficc = ['siteviews_pages' => $this->Traffic['siteviews_pages'] + 1];
        $updateTraffic = new Update;
        $updateTraffic->exeUpdate('ws_siteviews', $trafficc, 'WHERE siteviews_date = :date', "date={$this->Date}");
        //$this->Traffic = null;  //limpa os dados na memoria
    }

    //obtem os dados do trafego na tabela ws_siteviews
    private function getTraffic() {
        $read = new Read();
        $read->exeRead('ws_siteviews', 'WHERE siteviews_date = :date', "date={$this->Date}");
        if ($read->getResult()):
            $this->Traffic = $read->getResult()[0]; //armazena os dados da tabela no atributo traffic
        endif;
    }

    //metodo responsalve pela criação e atualização de coock
    private function getCoockie() {
        $coockie = filter_input(INPUT_COOKIE, 'user_online', FILTER_DEFAULT);
        setcookie('user_online', base64_decode('wtec'), time() + 86400); //criando coockie que dura um 24 horas
        if (!$coockie):
            return false;
        else:
            return true;
        endif;
    }

    /*
     * ***************************************
     * *******  NAVEGADORES DE ACESSO   ******
     * ***************************************
     */

    //metodo responsavel em setar e atualizar dados de navegadores na tabela ws_siteviews_agent
    private function setBrowser() {
        $this->checkBrowser();
        $readAgent = new Read;
        $readAgent->exeRead('ws_siteviews_agent', 'WHERE agent_name=:name', "name=$this->Browser");
        if (!$readAgent->getResult()): //se não existir regitro, inserir dados na tabela
            $dados = ['agent_name' => $this->Browser, 'agent_views' => 1];
            $cadastro = new Create;
            $cadastro->exeCreate('ws_siteviews_agent', $dados);
        else: //se existir, atualizar tabela
            $dados = ['agent_views' => $readAgent->getResult()[0]['agent_views'] + 1];
            $update = new Update;
            $update->exeUpdate('ws_siteviews_agent', $dados, 'WHERE agent_name=:name', "name=$this->Browser");
        endif;
    }

    //metodo responsavel em checar o tipo o navegador
    private function checkBrowser() {
        $this->Browser = $_SESSION['user_online']['online_agent'];
        if (strpos($this->Browser, 'Chrome')):
            $this->Browser = 'Chrome';
        elseif (strpos($this->Browser, 'Firefox')):
            $this->Browser = 'Firefox';
        elseif (strpos($this->Browser, 'MSIE') || strpos($this->Browser, 'Trident/')):
            $this->Browser = 'IE';
        elseif (strpos($this->Browser, 'Safari')):
            $this->Browser = 'Safari';
        else:
            $this->Browser = 'outros';
        endif;
    }

    /*
     * ***************************************
     * *********   USUÁRIOS ONLINE   *********
     * ***************************************
     */

    //metodo respnsavel pelo inserão de dados do usuário
    private function setUser() {
        $dados = $_SESSION['user_online']; //iniciando a variavel com os dados da sessão
        $dados['agent_name'] = $this->Browser;
        $createUser = new Create();
        $createUser->exeCreate('ws_siteviews_online', $dados);
    }

    //metodo responsavel pela atualização dos dados do usuário
    private function updateUser() {
        $updateUser = [
            'online_endview' => $_SESSION['user_online']['online_endview'],
            'online_url' => $_SESSION['user_online']['online_url'],
        ];
        $update = new Update;
        $update->exeUpdate('ws_siteviews_online', $updateUser, 'WHERE online_session=:idsess', "idsess={$_SESSION['user_online']['online_session']}");

        //verificando se a sessão foi encerrada. 
        //se não houver atualização é porque a sessão foi encerrda
        if (!$update->getRowCount()):
            //verificando se a sessão ainda existe
            $readSess = new Read;
            $readSess->exeRead('ws_siteviews_online', 'WHERE online_session=:idsess', "idsess={$_SESSION['user_online']['online_session']}");
            if (!$readSess->getResult()):
                $this->setUser();
            endif;
        endif;
    }

}
