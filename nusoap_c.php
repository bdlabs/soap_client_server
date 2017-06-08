<?
require_once 'lib/nusoap.php';

$wsdl = 'http://localhost:8000/soap_s.php?wsdl';
$client = new nusoap_client($wsdl, 'wsdl');

$raport_data = array();
$raport_data['client_name'] = "Max";
$raport_data['ip'] = "127.0.0.1";
$raport_data['OS'] = "Windows 98";
$raport_data['OS_ver'] = "98.1.1.2";
$raport_data['freespace'] = "53687091200";
$raport_data['lastbug'] = time();

$raport = array(
    'raport' => json_encode($raport_data),
);

$c = $client->call('SoapRaportLog.putRaport', $raport);
print_r($c);
