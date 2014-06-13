<?php
class RappelData
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
     * @param string $id
     * @param string $titre
     * @param string $lieu
     * @param int $debut
     * @param int $fin
     * @throws Exception
     */
    public function creerRappel($id, $titre, $lieu, $debut, $fin)
    {
        $sql = "INSERT INTO `rappel`(`id`, `description`, `cycle`, `lieu`, `begin`, `end`, `type`, `lastUpdate`) ";
        $sql .= 'VALUES ("","'.$titre.'","","'.$lieu.'","'.date("Y-m-d", $debut).'","'.date("Y-m-d", $fin).'","","'.date("Y-m-d").'");';

        try {
            $this->_db->query($sql);
        }
        catch(Exception $e)
        {
            throw new Exception("Erreur lors de l'execution de la requête");
        }

        $sql = "INSERT INTO `user_rappel`(`user_id`, `rappel_id`, `beginShare`, `endShare`) ";
        $sql .= "VALUES ('".$id."','".$this->_db->lastInsertId()."', '".date("Y-m-d")."', '')";

        try {
            $this->_db->query($sql);
        }
        catch(Exception $e)
        {
            throw new Exception("Erreur lors de l'execution de la requête");
        }
    }

    public function recupererRappel($id)
    {
        $sql = "SELECT * ";
        $sql .= "FROM rappel, user_rappel ";
        $sql .= "WHERE rappel.id = user_rappel.rappel_id ";
        $sql .= "AND user_rappel.user_id = ".$id;

        try {
            return $this->_db->query($sql);
        }
        catch(Exception $e)
        {
            throw new Exception("Erreur lors de l'execution de la requête");
        }
    }
}