<?php
class Rappel
{
    /**
     * @var string
     */
    private $_id;

    /**
     * @var string
     */
    private $_titre;

    /**
     * @var string
     */
    private $_lieu;

    /**
     * @var int
     */
    private $_begin;

    /**
     * @var int
     */
    private $_end;

    /**
     * @var int
     */
    private $_lastUpdate;

    /**
     * @param string $id
     * @throws Exception
     */
    public function __construct($id)
    {
        if(!empty($id) && !is_null($id))
        {
            $this->_id = $id;
        }
        else
        {
            throw new Exception("Toutes les données sont requises");
        }
    }

    /**
     * @param string $titre
     */
    public function setTitre($titre)
    {
        $this->_titre = $titre;
    }

    /**
     * @param string $lieu
     */
    public function setLieu($lieu)
    {
        $this->_lieu = $lieu;
    }

    /**
     * @param int $begin
     * @throws Exception
     */
    public function setBegin($begin)
    {
        if(is_numeric($begin))
        {
            $this->_begin = substr($begin, 0, 10);
        }
        else
        {
            throw new Exception("Donnée incorrecte, entier requis");
        }
    }

    /**
     * @param int $end
     * @throws Exception
     */
    public function setEnd($end)
    {
        if(is_numeric($end))
        {
            $this->_end = substr($end, 0, 10);
        }
        else
        {
            throw new Exception("Donnée incorrecte, entier requis");
        }
    }

    /**
     * @param int $lastUpdate
     * @throws Exception
     */
    public function setLastUpdate($lastUpdate)
    {
        if(is_numeric($lastUpdate))
        {
            $this->_lastUpdate = substr($lastUpdate, 0, 10);
        }
        else
        {
            throw new Exception("Donnée incorrecte, entier requis");
        }
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @var array
     */
    public function toArray()
    {
        return array(
            "Id" => $this->_id,
            "Titre" => $this->_titre,
            "Lieu" => $this->_lieu,
            "Debut" => $this->_begin,
            "Fin" => $this->_end,
            "DerniereModification" => $this->_lastUpdate
        );
    }

    /**
     * @param nusoap_server $serveur
     */
    public static function addType($serveur)
    {
        $serveur->wsdl->addComplexType(
            "Rappel",
            "complexType",
            "struct",
            "all",
            "",
            array(
                "Id" => array("name" => "Id", "type" => "xsd:string"),
                "Titre" => array("name" => "Titre", "type" => "xsd:string"),
                "Lieu" => array("name" => "Lieu", "type" => "xsd:string"),
                "Debut" => array("name" => "Debut", "type" => "xsd:int"),
                "Fin" => array("name" => "Fin", "type" => "xsd:int"),
                "DerniereModification" => array("name" => "DerniereModification", "type" => "xsd:int")
            )
        );
    }
}