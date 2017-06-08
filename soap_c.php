<?php
$location = "http://localhost:8000/soap_rs.php";
$options = array(
    'location' => $location,
    'uri' => 'http://schemas.xmlsoap.org/soap/envelope/',
    'style' => SOAP_RPC,
    'use' => SOAP_ENCODED,
    'soap_version' => SOAP_1_1,
    'cache_wsdl' => WSDL_CACHE_NONE,
    'connection_timeout' => 15,
    'trace' => true,
    'encoding' => 'UTF-8',
    'exceptions' => true,
);

//Utworzenie obiektu klienta SOAP
$soap = new SoapClient(null, $options);

$raport_data = array();
$raport_data['client_name'] = "Max";
$raport_data['ip'] = "127.0.0.1";
$raport_data['OS'] = "Windows 98";
$raport_data['OS_ver'] = "98.1.1.2";
$raport_data['freespace'] = "53687091200";
$raport_data['lastbug'] = time();

//wykorzystanie funkcji udostępnionej przez serwer
$ret = $soap->putRaport($raport_data);

echo $ret;
