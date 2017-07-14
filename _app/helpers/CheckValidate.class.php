<?php

/**
 * <b>CheckValidate.class</b>[TIPO]
 * Descrição: Classe responsavel pela validadação de dados no sistema
 * @author Wesley Santos Pereira 
 * @copyright (c) 2017, Wtec Soluções Web
 */
class CheckValidate {

    private static $Dados;
    private static $Format;

    /**
     * ****************************************
     * ************ PUBLICMETHODS *************
     * ****************************************
     */

    /**     *
     * <b>Email</b>
     * metótodo que verifica a validade do email
     * @param STRING $email
     * @return boolean
     */
    public static function Email($email) {
        self::$Dados = (string) $email;
        self::$Format = '/^[a-zA-Z0-9][a-zA-Z0-9\._-]+@([a-zA-Z0-9\._-]+\.)[a-zA-Z-0-9]{2,3}/';
        if (preg_match(self::$Format, self::$Dados)):
            return TRUE;
        else:
            return false;
        endif;
    }

    /**     *
     * <b>Friendlyurl</b>
     * Metodo que retorna uma string no formato de url amigavel
     * @param STRING $string
     * @return url amigavel;
     */
    public static function Friendlyurl($string) {
        self::$Format[0] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
        self::$Format[1] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';
        self::$Dados = strtr(utf8_decode($string), utf8_decode(self::$Format[0]), self::$Format[1]);
        self::$Dados = strip_tags(trim(self::$Dados));
        self::$Dados = str_replace(' ', '-', self::$Dados);
        $aux = array('-----', '----', '---', '--');
        self::$Dados = str_replace($aux, '-', self::$Dados);
        return strtolower(self::$Dados);
    }

    /**
     * <b>DateTimeStamp</b>
     * metodo que retorna uma data no formato Timestamp
     * @param DATA $data*
     */
    public static function DateTimeStamp($data) {
        self::$Format = explode(' ', $data); //separa a data da hora formando um array
        self::$Dados = explode('/', self::$Format[0]); //separa a data formando um array
        if (empty(self::$Format[1])): //verifica se foi informado uma hora
            self::$Format[1] = date('H:i:s'); //inseri a hora do sistema
        endif;
        //motando a data no formato timestamp
        self::$Dados = self::$Dados[2] . '-' . self::$Dados[1] . '-' . self::$Dados[0] . ' ' . self::$Format[1];
        return self::$Dados;
    }

   }
