<?php
class User
{
    /**
     * @var string
     */
    private $_id;

    /**
     * @var string
     */
    private $_username;

    /**
     * @var string
     */
    private $_firstName;

    /**
     * @var string
     */
    private $_lastName;

    /**
     * @var string
     */
    private $_email;

    /**
     * @param string $id
     * @param string $username
     */
    public function __construct($id, $username)
    {
        if(!empty($id) && !empty($username) && !is_null($id) && !is_null($username))
        {
            $this->_id = $id;
            $this->_username = $username;
        }
        else
        {
            throw new Exception("Toutes les données sont requises");
        }
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->_firstName = $firstName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->_lastName = $lastName;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->_email = $email;
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
            "Username" => $this->_username,
            "FirstName" => $this->_firstName,
            "LastName" => $this->_lastName,
            "Email" => $this->_email
        );
    }

    /**
     * @param nusoap_server $serveur
     */
    public static function addType($serveur)
    {
        $serveur->wsdl->addComplexType(
            "User",
            "complexType",
            "struct",
            "all",
            "",
            array(
                "Id" => array("name" => "Id", "type" => "xsd:string"),
                "Username" => array("name" => "Username", "type" => "xsd:string"),
                "FirstName" => array("name" => "FirstName", "type" => "xsd:string"),
                "LastName" => array("name" => "LastName", "type" => "xsd:string"),
                "Email" => array("name" => "Email", "type" => "xsd:string")
            )
        );
    }
}