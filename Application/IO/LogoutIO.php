<?php
class LogoutIO extends DefaultIO
{
    /**
     * @var bool
     */
    private $_resultat = false;

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
     * @return array
     */
    public function toArray()
    {
        $result = array(
            "Resultat" => $this->_resultat,
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
            "LogoutIO",
            "complexType",
            "struct",
            "all",
            "",
            array(
                "Resultat" => array("name" => "Resultat", "type" => "xsd:boolean"),
                "Erreur" => array("name" => "Erreur", "type" => "xsd:string")
            )
        );
    }
}