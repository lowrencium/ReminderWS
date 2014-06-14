<?php
class ContactData
{
    public $_db;

    public function __construct()
    {
        $_CONF = $GLOBALS["CONF"];
        try
        {
            $this->_db = new PDO($_CONF["BDD_TYPE"].":host=".$_CONF["BDD_URL"].";port=".$_CONF["BDD_PORT"].";dbname=".$_CONF["BDD_BASE"], $_CONF["BDD_USER"], $_CONF["BDD_PASSWORD"]);
        }
        catch(Exception $e)
        {
            throw new Exception("Erreur lors de la connexion à la BDD");
        }
    }

    /**
     * @param string $id
     * @return PDOStatement
     * @throws Exception
     */
    public function RecupererContacts($id)
    {
        $sql = "SELECT contacts ";
        $sql .= "FROM user ";
        $sql .= "WHERE id = ".$id;

        try
        {
            return $this->_db->query($sql);
        }
        catch(Exception $e)
        {
            throw new Exception("Erreur lors de l'execution de la requête");
        }
    }

    /**
     * @param string $id
     * @param string $contact
     * @return boolean
     * @throws Exception
     */
    public function SetContacts($id, $contact)
    {
        $sql = "UPDATE `user` SET `contacts`='".$contact."' WHERE id =".$id;

        try
        {
            return $this->_db->exec($sql);
        }
        catch(Exception $e)
        {
            throw new Exception("Erreur lors de l'execution de la requête");
        }
    }
}