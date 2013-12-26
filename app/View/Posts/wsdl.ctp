<?xml version='1.0' encoding='UTF-8'?>
<definitions name='YourTNS'
             targetNamespace='YourTNS'
             xmlns:tns='YourTNS'
             xmlns:soap='http://schemas.xmlsoap.org/wsdl/soap/'
             xmlns:xsd='http://www.w3.org/2001/XMLSchema'
             xmlns:soapenc='http://schemas.xmlsoap.org/soap/encoding/'
             xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'
             xmlns='http://schemas.xmlsoap.org/wsdl/'>
    <types><schema targetNamespace=".xsd"><element name="test"><complexType><all><element name="value" type="string"/></all></complexType></element><element name="resultat"><complexType><all><element name="value" type="string"/></all></complexType></element></schema></types><!-- response messages --><message name="returns_resultat"><part name="resultat" type="xsd:resultat"/></message><!-- request messages --><message name="firstMethod"><part name="test" type="xsd:test"/></message><!-- server's services --><portType name="Rappels"><operation name="firstMethod"><input message="tns:firstMethod"/><output message="tns:returns_resultat"/></operation></portType><!-- server encoding --><binding name="Rappels_webservices" type="tns:Rappels"><soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http"/><operation name="firstMethod"><soap:operation soapAction="urn:xmethods-delayed-quotes#firstMethod"/><input><soap:body use="encoded" namespace="urn:xmethods-delayed-quotes" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/></input><output><soap:body use="encoded" namespace="urn:xmethods-delayed-quotes" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/></output></operation></binding><!-- access to service provider --><service name="WebServer"><port name="WebServer_0" binding="Rappels_webservices"><soap:address location="http://86.74.103.173/reminder/reminderWS/Posts/service"/></port></service>
</definitions>