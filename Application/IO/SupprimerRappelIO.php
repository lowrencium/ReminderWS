<?php
class SupprimerRappelIO extends DefaultIO
{
    /**
     * @var bool
     */
    private $_resultat = false;

    /**
     * @var string
     */
    private $_token;

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
     * @param string $token
     */
    public function setToken($token)
    {
        $this->_token = $token;
    }

    /**
     * @return bool
     */
    public function getResultat()
    {
        return $this->_resultat;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = array(
            "Resultat" => $this->_resultat,
            "Token" => $this->_token,
            "Erreur" => $this->_erreur
        );

        return $result;
    }

    /**
     * @param nusoap_server $serveur
     */
    public static function addType($serveur)
    {
        $serveur->wsdl->addComplexType(
            "SupprimerRappelIO",
            "complexType",
            "struct",
            "all",
            "",
            array(
                "Resultat" => array("name" => "Resultat", "type" => "xsd:boolean"),
                "Token" => array("name" => "Token", "type" => "xsd:string"),
                "Erreur" => array("name" => "Erreur", "type" => "xsd:string")
            )
        );
    }
}