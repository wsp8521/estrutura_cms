<?php

/**
 * <b>AuxClass.class</b>[HELPERS]
 * Descrição: classe auxiliares que contem metodos genericos. Classe filha da classe
 * CheckValidate.class
 * @author Wesley Santos Pereira 
 * @copyright (c) 2017, Wtec Soluções Web
 */
class AuxClass {

    private static $Dados;
    private static $Format;

    /**
     * ****************************************
     * ************ PUBLICMETHODS *************
     * ****************************************
     * 
     */

    /**     *
     * <b>SliceWords</b>
     *  metodo que delimita quantidade de palavras
     * @param STRING $string - string que será delimitada
     * @param INT $delimiter - quantidade de palavras que será cortada
     * @param SINTRING $pointer - caracteres de continuação
     * @return string
     */
    public static function CutWords($String, $Limite, $Pointer = null) {
      self::$Dados = strip_tags(trim($String));
        self::$Format = (int) $Limite;

        $ArrWords = explode(' ', self::$Dados);
        $NumWords = count($ArrWords);
        $NewWords = implode(' ', array_slice($ArrWords, 0, self::$Format));

        $Pointer = (empty($Pointer) ? ' (...)' : ' ' . $Pointer );
        $Result = ( self::$Format < $NumWords ? $NewWords . $Pointer : self::$Dados );
        return $Result;
    }

    /**     *
     * <b>CutLetter</b>
     *  metodo que delimita quantidade de caractere
     * @param STRING $string - string que será delimitada
     * @param INT $delimiter - quantidade de palavras que será cortada
     * @param SINTRING $pointer - caracteres de continuação
     * @return string
     */
    public static function CutLetter($string, $delimiter, $pointer = null) {
        self::$Dados = strip_tags(trim($string));
        self::$Format = -$delimiter;
        $pointer = (empty($pointer) ? '[...]' : $pointer);
        $NewString = substr(self::$Dados, 0, -self::$Format);
        $count = strlen($NewString);
        $Result = (self::$Format < $count ? $NewString . ' ' . $pointer : self::$Dados);
        return $Result;
    }

    /**     *
     * <b>CatByName</b>
     * Captura o id da categoria com base no nome
     * @param STRING $nameCat - nome da categoria
     * @return id
     */
    public static function CatByName($nameCat) {
        $read = new Read;
        $read->exeRead('wsp_categoria', 'WHERE cat_title=:name', "name={$nameCat}");
        if ($read->getResult()):
            return $read->getResult()[0]['id_cat'];
        else:
            echo 'categoria não encontrada';
            die;
        endif;
    }
    
    /**     *
     * <b>GetId</b>
     * Captura o id de um registro a partir de uma string
     * @param STRING $string - uma string
     * @return id
     */
    public static function GetId($string) {
        $read = new Read;
        $read->exeRead('wsp_produtos', 'WHERE pro_url=:name', "name={$string}");
        if ($read->getResult()):
            return $read->getResult()[0]['id_produtos'];
        else:
            echo 'registro não encontrada';
            die;
        endif;
    }

    /**     *
     * <b>UserOnline</b>
     * retorna a quantidade de usuáios online no sistema
     */
    public static function UserOnline() {
        date_default_timezone_set('America/Sao_Paulo');
        $now = date('Y-m-d H:i:s'); //capturando o time do sistema
        $delUserOnline = new Delete;
        //deletendo os usuários que ultrapassarm o tempo
        $delUserOnline->exeDelete('ws_siteviews_online', 'WHERE online_endview < :now', "now={$now}");
        $readUserOnline = new Read;
        $readUserOnline->exeRead('ws_siteviews_online');
        return $readUserOnline->getRowCount(); //retorna a quatidade de usuários online
    }

    /** *
     *<b>thumb</b> 
     * metodo resposanvel pela exibição de miniatura de imagens
     * @param STRING $imgUrl - nome do arquivo. (ex: arquivo.jpg)
     * @param STRIG $imagDesc - descirção da imagem
     * @param INT $imgW - largura da imagem
     * @param INT $imgH - altura da imagem 
     * @return retorna uma imagem em miniatura
     */
    public static function thumb($ImageUrl, $ImageDesc=null, $ImageW = null, $ImageH = null) {
       
        self::$Dados = $ImageUrl;

        if (file_exists(self::$Dados) && !is_dir(self::$Dados)):
            $patch = BASE;
            $imagem = self::$Dados;
            return "<img src=\"{$patch}/tim.php?src={$patch}/{$imagem}&w={$ImageW}&h={$ImageH}\" alt=\"{$ImageDesc}\" title=\"{$ImageDesc}\"/>";
        else:
            return false;
        endif;
    }

    
     /**     *
     * <b>Name</b>
     * Metodo que retorna uma string no formato de url amigavel
     * @param STRING $string
     * @return url amigavel;
     */
    public static function Name($string) {
        self::$Format[0] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
        self::$Format[1] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';
        self::$Dados = strtr(utf8_decode($string), utf8_decode(self::$Format[0]), self::$Format[1]);
        self::$Dados = strip_tags(trim(self::$Dados));
        self::$Dados = str_replace(' ', '-', self::$Dados);
        $aux = array('-----', '----', '---', '--');
        self::$Dados = str_replace($aux, '-', self::$Dados);
        return strtolower(self::$Dados);
    }
}
