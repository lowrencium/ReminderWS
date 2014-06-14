<?php
class Contact
{
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
     * @param string $email
     */
    public function __construct($email)
    {
        if(!empty($email) && !is_null($email))
            $this->_email = $email;
        else
            throw new Exception("L'email est obligatoire");
    }

    /**
     * @param string$nom
     */
    public function setNom($nom)
    {
        $this->_nom = $nom;
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
     * @var array
     */
    public function toArray()
    {
        return array(
            "Email" => $this->_email,
            "Nom" => $this->_nom,
            "Telephone" => $this->_telephone,
            "Adresse" => $this->_adresse
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
                "Email" => array("name" => "Email", "type" => "xsd:string"),
                "Nom" => array("name" => "Nom", "type" => "xsd:string"),
                "Telephone" => array("name" => "Telephone", "type" => "xsd:string"),
                "Adresse" => array("name" => "Adresse", "type" => "xsd:string")
            )
        );
    }
}