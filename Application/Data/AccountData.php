<?php
class AccountData
{
    public $_db;

    public function __construct()
    {
        $_CONF = $GLOBALS["CONF"];
        try
        {
            $this->_db = new PDO($_CONF["BDD_TYPE"].":host=".$_CONF["BDD_URL"].";dbname=".$_CONF["BDD_BASE"], $_CONF["BDD_USER"], $_CONF["BDD_PASSWORD"]);
        }
        catch(Exception $e)
        {
            throw new Exception("Erreur lors de la connexion à la BDD");
        }
    }

    /**
     * @param string $username
     * @return PDOStatement
     * @throws Exception
     */
    public function login($username)
    {
        $sql = "SELECT id, password, salt ";
        $sql .= "FROM user ";
        $sql .= "WHERE username = '".$username."' ";

        try
        {
            return $this->_db->query($sql);;
        }
        catch(Exception $e)
        {
            throw new Exception("Erreur lors de l'execution de la requête");
        }
    }

    /**
     * @param string $id
     * @return PDOStatement
     * @throws Exception
     */
    public function getInfos($id)
    {
        $sql = "SELECT * ";
        $sql .= "FROM user ";
        $sql .= "WHERE id = '".$id."'";

        try
        {
            return $this->_db->query($sql);;
        }
        catch(Exception $e)
        {
            throw new Exception("Erreur lors de l'execution de la requête");
        }
    }
}