<?php
class RecupererRappelIO extends DefaultIO
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
     * @var Rappel[]
     */
    private $_rappels = array();

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
     * @param Rappel $rappel
     * @throws Exceptiont
     */
    public function addRappel($rappel)
    {
        if($rappel instanceof Rappel)
        {
            $this->_rappels[] = $rappel;
        }
        else
        {
            throw new Exceptiont("Objet rappel requis");
        }
    }

    private function rappelsToArray()
    {
        $result = array();
        foreach($this->_rappels as $rappel)
        {
            $result[] = $rappel->toArray();
        }
        return $result;
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
            "Rappels" => $this->rappelsToArray(),
            "Erreur" => $this->_erreur
        );

        return $result;
    }

    /**
     * @param nusoap_server $serveur
     */
    public static function addType($serveur)
    {
        Rappel::addType($serveur);

        $serveur->wsdl->addComplexType(
            'RappelArray',
            'complexType',
            'array',
            '',
            'SOAP-ENC:Array',
            array(),
            array(
                array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:Rappel[]')
            ),
            'tns:Rappel'
        );

        $serveur->wsdl->addComplexType(
            "RecupererRappelIO",
            "complexType",
            "struct",
            "all",
            "",
            array(
                "Resultat" => array("name" => "Resultat", "type" => "xsd:boolean"),
                "Token" => array("name" => "Token", "type" => "xsd:string"),
                "Rappels" => array("name" => "Rappels", "type" => "tns:RappelArray"),
                "Erreur" => array("name" => "Erreur", "type" => "xsd:string")
            )
        );
    }
}