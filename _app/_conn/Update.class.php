<?php

/**
 * <b>Update.class</b>[CONN]
 * Descrição: classe responsalve pelo atualização de registro no banco de dados
 * @author Wesley Santos Pereira 
 * @copyright (c) 2017, Wtec Soluções Web
 */
class Update extends Conn {

    private $Table; //recebe a tabela que terá seus registros atualizados
    private $Termos; //recebe os termos da atualização
    private $Data; //recebe os dados que serão atualzados
    private $Places; //recebe uma parseString
    private $Result; //retorna um resultado

    /** @var PDO * */
    private $Conn; //recebe conecção do tipo PDO

    /** @var PDOStatement */
    private $Update; //recebe uma query para a realização do PrepareStatement 

    /**
     * ****************************************
     * ************ PUBLIC METHODS ************
     * ****************************************
     */

    /**     *
     * <b>exeUpdate</b>
     * Metodo resposanvel pela execução da atualizações dos dados
     * @param STRING $Table: recebe uma tabela
     * @param ARRAY $Dados: recebe os dados a serem atualziados
     * @param STRING $Termos: recebe os termos para a atualização
     * @param STRING $Parsestring: recebe uma parsestring
     */
    public function exeUpdate($Table, array $Dados, $Termos, $Parsestring) {
        $this->Table = $Table;
        $this->Data = $Dados;
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
        return $this->Update->rowCount();
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
        $this->Update = $this->Conn->prepare($this->Update); //preparando a query
    }

    /**     *
     * <b>getSyntaxQuery</b>
     * Cria a sintaxe da query para Prepared Statements
     */
    private function getSyntaxQuery() {
        foreach ($this->Data as $key => $values):
            $places[] = $key . '=:' . $key; //cria os link para os bindevalues
        endforeach;
        $places = implode(', ', $places); //tranforma os array em uma string
        $this->Update = 'UPDATE '.$this->Table.' SET '.$places.' '.$this->Termos;
    }

    //Obtém a Conexão e a Syntax, executa a query!
    private function Execute() {
        $this->Conect();
        try {
            $this->Update->execute(array_merge($this->Data, $this->Places));
            $this->Result = true; //retorna true caso a atualização seja realizado com sucesso
        } catch (PDOException $e) {
            $this->Result = null;
            WSP_ERRO("<b>Erro ao atualizar:</b> {$e->getMessage()}", $e->getCode());
        }
    }

}
