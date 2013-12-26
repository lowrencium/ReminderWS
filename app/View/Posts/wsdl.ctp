<definitions name='Reminder'
             targetNamespace='http://86.74.103.173/reminder/reminderWS/Posts/wsdl'
             xmlns:tns='http://86.74.103.173/reminder/reminderWS/Posts/wsdl'
             xmlns:soap='http://schemas.xmlsoap.org/wsdl/soap/'
             xmlns:xsd='http://www.w3.org/2001/XMLSchema'
             xmlns:soapenc='http://schemas.xmlsoap.org/soap/encoding/'
             xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'
             xmlns='http://schemas.xmlsoap.org/wsdl/'>
    <types>
        <schema xmlns="http://www.w3.org/2001/XMLSchema"
                targetNamespace="http://www.ecerami.com/schema"
                xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/"
                xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/">
            <complexType name="ArrayOfString">
                <complexContent>
                    <restriction base="soapenc:Array">
                        <attribute ref="soapenc:arrayType"
                                   wsdl:arrayType="string[]"/>
                    </restriction>
                </complexContent>
            </complexType>
        </schema>
    </types>
    <wsdl:message name="firstMethodRequest">
        <wsdl:part element="foo:bar" name="parameters"/>
    </wsdl:message>
    <wsdl:message name="firstMethodResponse">
        <wsdl:part element="foo:bar" name="parameters"/>
    </wsdl:message>
    <portType name='PostPortType'>
        <operation name='firstMethod'>
            <input message='tns:firstMethodRequest'/>
            <output message='tns:firstMethodResponse'/>
        </operation>
    </portType>
    <binding name='PostBinding' type='tns:PostPortType'>
        <soap:binding style='rpc' transport='http://schemas.xmlsoap.org/soap/http'/>
        <operation name='firstMethod'>
            <soap:operation soapAction='urn:your-urn'/>
            <input>
                <soap:body use='encoded' namespace='urn:your-urn'
                           encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'/>
            </input>
            <output>
                <soap:body use='encoded' namespace='urn:your-urn'
                           encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'/>
            </output>
        </operation>
    </binding>
    <service name='PostService'>
        <port name='PostPort' binding='PostBinding'>
            <soap:address location='http://86.74.103.173/reminder/reminderWS/posts/service'/>
        </port>
    </service>
</definitions>