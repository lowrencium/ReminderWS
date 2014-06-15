<?php
class RappelData
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
     * @param string $titre
     * @param string $lieu
     * @param int $debut
     * @param int $fin
     * @throws Exception
     */
    public function creerRappel($id, $titre, $lieu, $debut, $fin)
    {
        $sql = "INSERT INTO `rappel`(`id`, `description`, `cycle`, `lieu`, `begin`, `end`, `type`, `lastUpdate`) ";
        $sql .= 'VALUES ("","'.$titre.'","","'.$lieu.'","'.date("Y-m-d h:i:s", $debut).'","'.date("Y-m-d h:i:s", $fin).'","","'.date("Y-m-d h:i:s").'");';

        try {
            $this->_db->query($sql);
        }
        catch(Exception $e)
        {
            throw new Exception("Erreur lors de l'execution de la requête");
        }

        $sql = "INSERT INTO `user_rappel`(`user_id`, `rappel_id`, `beginShare`, `endShare`) ";
        $sql .= "VALUES ('".$id."','".$this->_db->lastInsertId()."', '".date("Y-m-d h:i:s")."', '')";

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

    /**
     * @param string $id
     * @param string $rappelId
     * @return boolean
     * @throws Exception
     */
    public function supprimerRappel($id, $rappelId)
    {
        $sql = "SELECT COUNT(id) as nbRappel FROM rappel, user_rappel WHERE rappel.id = user_rappel.rappel_id AND user_id = ".$id." AND rappel_id = ".$rappelId;

        try {
            $result = $this->_db->query($sql);
            $result = $result->fetch(PDO::FETCH_OBJ);
        }
        catch(Exception $e)
        {
            throw new Exception("Erreur lors de l'execution de la requête");
        }

        if($result->nbRappel == 1)
        {
            $sql = "DELETE FROM user_rappel WHERE rappel_id = ".$rappelId;

            try
            {
                $this->_db->exec($sql);
            }
            catch(Exception $e)
            {
                throw new Exception("Erreur lors de l'execution de la requête");
            }

            $sql = "DELETE FROM rappel WHERE id = ".$rappelId;

            try
            {
                return $this->_db->exec($sql);
            }
            catch(Exception $e)
            {
                throw new Exception("Erreur lors de l'execution de la requête");
            }
        }
        else
        {
            throw new Exception("Ce rappel n'appartient pas à cet utilisateur");
        }
    }

    /**
     * @param string $id
     * @param string $rappelId
     * @param string $contactId
     * @param string $type
     * @return boolean
     * @throws Exception
     */
    public function partagerRappel($id, $rappelId, $contactId, $type)
    {
        $sql = "SELECT COUNT(rappel_id) as nbRappel FROM user_rappel WHERE rappel_id = ".$rappelId." AND user_id = ".$id;

        try
        {
            $data = $this->_db->query($sql);
        }
        catch(Exception $e)
        {
            throw new Exception("Erreur lors de l'execution de la requête");
        }

        $row = $data->fetch(PDO::FETCH_OBJ);
        if($row->nbRappel == 1)
        {
            if($type == "User")
            {
                $sql = "INSERT INTO `user_rappel`(`user_id`, `rappel_id`, `beginShare`, `endShare`) VALUES (".$contactId.", ".$rappelId.", ".date("Y-m-d h:i:s").", '')";
            }
            elseif($type == "Guest")
            {
                $sql = "INSERT INTO `guest_rappel`(`guest_id`, `rappel_id`, `beginShare`, `endShare`) VALUES (".$contactId.", ".$rappelId.", ".date("Y-m-d h:i:s").", '')";
            }
            else
            {
                throw new Exception("Type de contact inconnu");
            }

            try
            {
                return $this->_db->exec($sql);
            }
            catch(Exception $e)
            {
                throw new Exception("Erreur lors de l'execution de la requête");
            }
        }
        else
        {
            throw new Exception("Ce rappel n'appartient pas à cet utilisateur");
        }
    }
}