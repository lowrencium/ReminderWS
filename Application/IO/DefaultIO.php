<?php
class DefaultIO
{
    /**
     * @var string
     */
    protected $_erreur = null;

    /**+
     * @param Exception $erreur
     */
    public function setErreur($erreur)
    {
        $this->_erreur = $erreur->getMessage();
    }
}