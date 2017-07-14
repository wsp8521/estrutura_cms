<?php

/**
 * <b>Read.class</b>[CONN]
 * Descrição: classe responsalve pelo leitura de registro no banco de dados
 * @author Wesley Santos Pereira 
 * @copyright (c) 2017, Wtec Soluções Web
 */
class Read extends Conn {

    private $Select; //recebe a query
    private $Places; //recebe uma parseString
    private $Result; //retorna um resultado

    /** @var PDO * */
    private $Conn; //recebe conecção do tipo PDO

    /** @var PDOStatement */
    private $Read; //recebe uma query para a realização do PrepareStatement 

    /**
     * ****************************************
     * ************ PUBLIC METHODS ************
     * ****************************************
     */

    /**     *
     * <b>exeRead</b>
     * Metódo responsavel pela execução da leitura de registro
     * @param STRING $Table: tabela que será lida
     * @param STRING $Termos: critérios de leitura 
     * @param STRING $Parsestring: regras de leitura
     */
    public function exeRead($Table, $Termos = null, $Parsestring = null) {
        if ($Parsestring):
            $this->Places = $Parsestring;
            parse_str($Parsestring, $this->Places); //transformando a string em um array;  
        endif;
        $this->Select = 'SELECT * FROM ' . $Table . ' ' . $Termos;
        $this->Execute();
    }

    /**     *
     * <b>FullRead</b>
     * Metódo responsavel pela leitura personalizada de uma tabela. podendo ser utilizado
     * join e outros criterios de filtragem
     * @param STRING $Query: Query personalizada
     * @param STRING $ParseString
     */
    public function FullRead($Query, $ParseString = null) {
        $this->Select = $Query;
        if ($ParseString):
            $this->Places = $ParseString; //regras de leitura
            parse_str($ParseString, $this->Places); //transformando a string em um array;  
        endif;
        $this->Execute();
    }

  /** *
   * <b>setPlaces</b>
   * Recebe uma place. Utiliza-se pra storeProcedure
   * @param STRING $ParseString
   */
    public function setPlaces($ParseString) {
        parse_str($ParseString, $this->Places);
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
        return $this->Read->rowCount();
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
        $this->Read = $this->Conn->prepare($this->Select); //preparando a query
        $this->Read->setFetchMode(PDO::FETCH_ASSOC); //retorna um array
    }

    /**     *
     * <b>getSyntaxQuery</b>
     * Cria a sintaxe da query para Prepared Statements
     */
    private function getSyntaxQuery() {
        if ($this->Places):
            foreach ($this->Places as $link => $valor):
                if ($link == 'limit' || $link == 'offset'):
                    $valor = (int) $valor;
                endif;

                //montado os bidevalue
                $this->Read->bindValue(":{$link}", $valor, (is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR));
            endforeach;
        endif;
    }

    //Obtém a Conexão e a Syntax, executa a query!
    private function Execute() {
        $this->Conect();
        try {
            $this->getSyntaxQuery();
            $this->Read->execute();
            $this->Result = $this->Read->fetchAll();
        } catch (PDOException $e) {
            $this->Result = null;
            WSP_ERRO("<b>Erro ao Fazer a leitura:</b> {$e->getMessage()}", $e->getCode());
        }
    }

}
