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
    public function recupererUserContacts($id)
    {
        $sql = "SELECT user.* ";
        $sql .= "FROM user, user_user ";
        $sql .= "WHERE ((user.id = user_user.user1_id AND user_user.user2_id = ".$id.") OR (user.id = user_user.user2_id AND user_user.user1_id = ".$id.")) ";
        $sql .= "AND user_user.validated = 1 ";

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
     * @return PDOStatement
     * @throws Exception
     */
    public function recupererGuestContacts($id)
    {
        $sql = "SELECT guest.* ";
        $sql .= "FROM guest, user_guest ";
        $sql .= "WHERE guest.id = user_guest.guest_id ";
        $sql .= "AND user_guest.user_id = ".$id;

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
     * @param string $email
     * @return PDOStatement
     * @throws Exception
     */
    public function checkContact($email)
    {
        $sql = "SELECT COUNT(id) as nbUser ";
        $sql .= "FROM user ";
        $sql .= "WHERE email = '".$email."'";

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
     * @param string $email
     * @return boolean
     * @throws Exception
     */
    public function addUserContact($id, $email)
    {
        $sql = "SELECT id ";
        $sql .= "FROM user ";
        $sql .= "WHERE email = '".$email."'";

        try
        {
            $data = $this->_db->query($sql);
        }
        catch(Exception $e)
        {
            throw new Exception("Erreur lors de l'execution de la requête");
        }

        $row = $data->fetch(PDO::FETCH_OBJ);
        if(!is_array($row) && $row->id != $id)
        {
            $userId = $row->id;

            $sql = "SELECT COUNT(*) as nbLigne ";
            $sql .= "FROM user_user ";
            $sql .= "WHERE (user1_id = ".$id." AND user2_id = ".$userId.") ";
            $sql .= "OR (user1_id = ".$userId." AND user2_id = ".$id.") ";

            try
            {
                $data = $this->_db->query($sql);
            }
            catch(Exception $e)
            {
                throw new Exception("Erreur lors de l'execution de la requête");
            }

            $row = $data->fetch(PDO::FETCH_OBJ);
            if($row->nbLigne == 0)
            {
                $sql = "INSERT INTO `user_user`(`user1_id`, `user2_id`, `validated`) VALUES ('".$id."', '".$userId."', 0)";

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
                throw new Exception("Vous ètes déjà lié à cet utilisateur ou celui-ci a refusé votre demande de contact");
            }

        }
        else
        {
            throw new Exception("Erreur lors de l'execution de la requête");
        }
    }

    /**
     * @param string $id
     * @param string $nom
     * @param string $email
     * @param string $telephone
     * @param string $adresse
     * @return boolean
     * @throws Exception
     */
    public function addGuestContact($id, $nom, $email, $telephone, $adresse)
    {
        $sql = "INSERT INTO `guest`(`id`, `name`, `email`, `phone`, `adress`) VALUES ('', '".$nom."', '".$email."', '".$telephone."', '".$adresse."')";

        try
        {
            $result = $this->_db->exec($sql);
        }
        catch(Exception $e)
        {
            throw new Exception("Erreur lors de l'execution de la requête");
        }

        if($result)
        {
            $sql = "INSERT INTO `user_guest`(`user_id`, `guest_id`) VALUES ('".$id."', '".$this->_db->lastInsertId()."')";

            try
            {
                return $result = $this->_db->exec($sql);
            }
            catch(Exception $e)
            {
                throw new Exception("Erreur lors de l'execution de la requête");
            }
        }
    }

    /**
     * @param string $id
     * @param string $contactId
     * @param string $type
     * @return boolean
     * @throws Exception
     */
    public function supprimerContact($id, $contactId, $type)
    {
        if($type == "User")
        {
            $sql = "DELETE FROM user_user WHERE (user1_id = ".$id." AND user2_id = ".$contactId.") OR (user2_id = ".$id." AND user1_id = ".$contactId.")";
        }
        elseif($type == "Guest")
        {
            $sql = "DELETE FROM user_guest WHERE user_id = ".$id." AND guest_id = ".$contactId;

            try
            {
                $this->_db->exec($sql);
            }
            catch(Exception $e)
            {
                throw new Exception("Erreur lors de l'execution de la requête");
            }

            $sql = "DELETE FROM guest WHERE id = ".$contactId;
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

    /**
     * @param string $id
     * @param string $contactId
     * @param boolean $valider
     * @return boolean
     * @throws Exception
     */
    public function validerContact($id, $contactId, $valider)
    {
        $sql = "UPDATE `user_user` SET `validated`= ".($valider ? 1 : 2)." WHERE user1_id = ".$contactId." AND user2_id = ".$id;

        try
        {
            return $this->_db->exec($sql);
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
    public function recupererDemandesContact($id)
    {
        $sql = "SELECT user.* ";
        $sql .= "FROM user_user, user ";
        $sql .= "WHERE user_user.user2_id = ".$id." ";
        $sql .= "AND user_user.validated = 0 ";
        $sql .= "AND user.id = user_user.user1_id";

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
     * @return PDOStatement
     * @throws Exception
     */
    public function recupererMesDemandesContact($id)
    {
        $sql = "SELECT user.* ";
        $sql .= "FROM user_user, user ";
        $sql .= "WHERE user_user.user1_id = ".$id." ";
        $sql .= "AND user_user.validated = 0 ";
        $sql .= "AND user.id = user_user.user2_id";

        try
        {
            return $this->_db->query($sql);
        }
        catch(Exception $e)
        {
            throw new Exception("Erreur lors de l'execution de la requête");
        }
    }
}