<?php
/**
 * <b>Conn.class</b> [ CONEXÃO ]
 * Classe abstrata de conexão. Padrão SingleTon.
 * Retorna um objeto PDO pelo método estático getConn();
 * 
 * @copyright (c) 2017, Wesley Santos Pereira - WTEC SOLUÇÕES WEB
 */
abstract class Conn {

    //valores dos atributos vindos do config.php
    private static $Host = HOST;
    private static $Dbname = DBNAME;
    private static $User = USER;
    private static $Pass = PASS;

    /** @var PDO */
    private static $Connect = null;

    /** Retorna um objeto PDO Singleton Pattern. */
    public function getConn() {
        return self::conectar();
    }

    /**
     * Conecta com o banco de dados com o pattern singleton.
     * Retorna um objeto PDO!
     */
    private static function conectar() {
        try {
            if (self::$Connect == null): //verifica se h´ha uma conexção
                $dsn = 'mysql:host=' . self::$Host . ';dbname=' . self::$Dbname;
                $options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'];
                self::$Connect = new PDO($dsn, self::$User, self::$Pass, $options);
            endif;
        } catch (Exception $e) {
            PHPErro($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
            die;
        }
        self::$Connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return self::$Connect;
    }

}
