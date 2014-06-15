<?php
class Contact
{
    /**
     * @var string Identifiant du contact
     */
    private $_id;

    /**
     * @var string Email du contact
     */
    private $_email;

    /**
     * @var string Nom du contact
     */
    private $_nom;

    /**
     * @var string Numero de telephone du contact
     */
    private $_telephone;

    /**
     * @var string Adresse du contact
     */
    private $_adresse;

    /**
     * @var string type de contact
     */
    private $_type;

    /**
     * @param string $id
     */
    public function __construct($id)
    {
        if(!empty($id) && !is_null($id))
            $this->_id = $id;
        else
            throw new Exception("L'id est obligatoire");
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->_nom = $nom;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * @param string $telephone
     */
    public function setTelephone($telephone)
    {
        $this->_telephone = $telephone;
    }

    /**+
     * @param string $adresse
     */
    public function setAdresse($adresse)
    {
        $this->_adresse = $adresse;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->_type = $type;
    }

    /**
     * @var array
     */
    public function toArray()
    {
        return array(
            "Id" => $this->_id,
            "Email" => $this->_email,
            "Nom" => $this->_nom,
            "Telephone" => $this->_telephone,
            "Adresse" => $this->_adresse,
            "Type" => $this->_type
        );
    }

    /**
     * @param nusoap_server $serveur
     */
    public static function addType($serveur)
    {
        $serveur->wsdl->addComplexType(
            "Contact",
            "complexType",
            "struct",
            "all",
            "",
            array(
                "Id" => array("name" => "Id", "type" => "xsd:string"),
                "Email" => array("name" => "Email", "type" => "xsd:string"),
                "Nom" => array("name" => "Nom", "type" => "xsd:string"),
                "Telephone" => array("name" => "Telephone", "type" => "xsd:string"),
                "Adresse" => array("name" => "Adresse", "type" => "xsd:string"),
                "Type" => array("name" => "Type", "type" => "xsd:string")
            )
        );
    }
}