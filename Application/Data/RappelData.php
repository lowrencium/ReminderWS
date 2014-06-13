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
     * @param string $titre
     * @param string $lieu
     * @param int $debut
     * @param int $fin
     * @return PDOStatement
     * @throws Exception
     */
    public function creerRappel($titre, $lieu, $debut, $fin)
    {
        $sql = "INSERT INTO `rappel`(`id`, `description`, `cycle`, `lieu`, `begin`, `end`, `type`, `lastUpdate`) ";
        $sql .= 'VALUES ("","'.mysql_real_escape_string($titre).'","","'.mysql_real_escape_string($lieu).'","'.date("Y-m-d", $debut).'","'.date("Y-m-d", $fin).'","","'.date("Y-m-d").'");';

        try {
            return $this->_db->query($sql);
        }
        catch(Exception $e)
        {
            throw new Exception("Erreur lors de l'execution de la requête");
        }
    }
}