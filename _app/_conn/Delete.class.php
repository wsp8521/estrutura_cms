<?php

/**
 * <b>Delete.class</b>[CONN]
 * Descrição: classe responsalve pelo exclusão de registro no banco de dados
 * @author Wesley Santos Pereira 
 * @copyright (c) 2017, Wtec Soluções Web
 */
class Delete extends Conn {

    private $Table; //recebe a tabela que terá seus registros atualizados
    private $Termos; //recebe os termos da atualização
    private $Places; //recebe uma parseString
    private $Result; //retorna um resultado

    /** @var PDO * */
    private $Conn; //recebe conecção do tipo PDO

    /** @var PDOStatement */
    private $Delete; //recebe uma query para a realização do PrepareStatement 

    /**
     * ****************************************
     * ************ PUBLIC METHODS ************
     * ****************************************
     */

    /**     *
     * <b>exeDelete</b>
     * Metodo resposanvel pela execução de exclusão dos dados
     * @param STRING $Table: recebe uma tabela
     * @param STRING $Termos: recebe os termos para a atualização
     * @param STRING $Parsestring: recebe uma parsestring
     */
    public function exeDelete($Table, $Termos, $Parsestring) {
        $this->Table = $Table;
        $this->Termos = $Termos;
        $this->Places = $Parsestring; //regras de leitura
        parse_str($Parsestring, $this->Places); //transformando a string em um array; 
        $this->getSyntaxQuery();
        $this->Execute();
    }

    /**     *
     * <b>setPlaces</b>
     * Recebe uma place. Utiliza-se pra storeProcedure
     * @param STRING $ParseString
     */
    public function setPlaces($ParseString) {
        parse_str($ParseString, $this->Places);
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

    /**     *
     * <b>getRowCount</>
     * Retorna a quantiade de registro encontrado
     */
    public function getRowCount() {
        return $this->Delete->rowCount();
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
        $this->Delete = $this->Conn->prepare($this->Delete); //preparando a query
    }

    /**     *
     * <b>getSyntaxQuery</b>
     * Cria a sintaxe da query para Prepared Statements
     */
    private function getSyntaxQuery() {
        $this->Delete =  "DELETE FROM ".$this->Table.' '.$this->Termos;
    }

    //Obtém a Conexão e a Syntax, executa a query!
    private function Execute() {
        $this->Conect();
        try {
            $this->Delete->execute($this->Places);
            $this->Result = true;
        } catch (PDOException $e) {
            $this->Result = null;
            WSP_ERRO("<b>Erro ao excluir:</b> {$e->getMessage()}", $e->getCode());
        }
    }
    

}
