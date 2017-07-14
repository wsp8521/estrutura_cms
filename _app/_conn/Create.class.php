<?php

/**
 * <b>Create.class</b>[CONN]
 * Descrição: classe responsalve pelo cadastro de registro no banco de dados
 * @author Wesley Santos Pereira 
 * @copyright (c) 2017, Wtec Soluções Web
 */
class Create extends Conn {

    private $Table; //recebe a tabela no banco dados
    private $Data; //recbe os dados a serem cadastrados
    private $Result; //retorna um resultado

    /** @var PDO * */
    private $Conn; //recebe conecção do tipo PDO

    /** @var PDOStatement */
    private $Create; //recebe uma query para a realização do PrepareStatement 

    /**
     * ****************************************
     * ************ PUBLIC METHODS ************
     * ****************************************
     */

    /**     *
     * <b>ExeCreate</b>
     * Metódo responsavel pela execução do cadastro de dados
     * @param STRING $Table - recebe uma tabela do banco de dados
     * @param ARRAY $Dados - recebe os registro que serão cadastrados
     */
    public function exeCreate($Table, array $Dados) {
        $this->Table = $Table;
        $this->Data = $Dados;
        $this->getSyntaxQuery();
        $this->Execute();
    }

    /**     *
     *  <b>getResult</b>
     * Método responsavel por retornar um resultado
     */
    public function getResult() {
        return $this->Result;
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */

    /**     *
     * <b>Conect</b>
     * Metodo responsavel pela conexão e preparação da query no PrepareStatemant
     */
    private function Conect() {
        $this->Conn = parent::getConn(); //propriedade Conn recebe a conecção
        
        //propriedade Creat responsavel por receber uma query e depois ser colocada no PrepareStatement
        $this->Create = $this->Conn->prepare($this->Create);
    }

    /**     *
     * <b>getSyntaxQuery</b>
     * Cria a sintaxe da query para Prepared Statements
     */
    private function getSyntaxQuery() {
        $Fileds = implode(',', array_keys($this->Data)); //isolando as keys do array dados
        $Places = ':' . implode(', :', array_keys($this->Data)); //transformando os keys do array em links
        $this->Create = "INSERT INTO {$this->Table} ({$Fileds}) VALUES ({$Places})";
    }

    //Obtém a Conexão e a Syntax, executa a query!
    private function Execute() {
        $this->Conect();
        try {
            $this->Create->execute($this->Data);
            $this->Result = $this->Conn->lastInsertId(); //retorna um id do ultimo resitro inserido
        } catch (PDOException $e) {
            $this->Result = null;
            WSP_ERRO("<b>Erro ao cadastrar:</b> {$e->getMessage()}", $e->getCode());
        }
    }

}
