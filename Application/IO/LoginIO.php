<?php
class LoginIO extends DefaultIO
{
    /**
     * @var bool
     */
    private $_resultat = false;

    /**
     * @var User
     */
    private $_user;

    /**
     * @param bool $resultat
     * @throws Exception
     */
    public function setResultat($resultat)
    {
        if(is_bool($resultat))
        {
            $this->_resultat = $resultat;
        }
        else
        {
            throw new Exception("Booléen requis");
        }
    }

    /**
     * @return bool
     */
    public function getResultat()
    {
        return $this->_resultat;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        if($user instanceof User)
        {
            $this->_user = $user;
        }
        else
        {
            throw new Exception("User requis");
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = array(
            "Resultat" => $this->_resultat,
            "Token" => "",
            "Utilisateur" => $this->_user instanceof User ? $this->_user->toArray() : null,
            "Erreur" => $this->_erreur
        );

        if($this->_resultat)
        {
            $result["Token"] = session_id();
        }

        return $result;
    }

    /**
     * @param nusoap_server $serveur
     */
    public static function addType($serveur)
    {
        User::addType($serveur);

        $serveur->wsdl->addComplexType(
            "LoginIO",
            "complexType",
            "struct",
            "all",
            "",
            array(
                "Resultat" => array("name" => "Resultat", "type" => "xsd:boolean"),
                "Token" => array("name" => "Token", "type" => "xsd:string"),
                "Erreur" => array("name" => "Erreur", "type" => "xsd:string"),
                "Utilisateur" => array("name" => "Utilisateur", "type" => "tns:User")
            )
        );
    }
}