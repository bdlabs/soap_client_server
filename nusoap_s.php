<?php
require 'lib/nusoap.php';
$ns = 'http://localhost:8000/soap_s.php';

class RaportSQL
{

    public static function getIdOS($os, $ver)
    {
        $sql = sprintf("SELECT id_OS
                FROM soap_OS
                WHERE name = \"%s\" and version = \"%s\";",
            $os, $ver);
        $result = mysql_query($sql);

        if ($row = mysql_fetch_row($result)) {
            $id_os = $row[0];
        } else {
            //insert to database
            $id_os = mysql_insert_id();
        }
        return $id_os;
    }

    public static function addUserData($name, $ip, $id_os, $freespace, $lastbug)
    {
        $freespaceGB = $freespace / 1024 / 1204 / 1024; // convert B -> GB
        $sql = sprintf("INSERT INTO soap_raport (username, ip, id_OS, freespace, freespaceGB, lastbug) VALUES
                (\"%s\", \"%s\", \"%u\", \"%u\", \"%.2F\", \"%u\");",
            $name, $ip, $id_os, $freespace, $freespaceGB, $lastbug);
        mysql_query($sql);
    }
}

class SoapRaportLog
{
    public function __construct()
    {

    }

    public function putRaport($raport)
    {
        $this->saveRaport(json_decode($raport, true));
        return new soapval('return', 'xsd:string', "STATUS_OK");
    }

    private function saveRaport($data)
    {
        $id_os = RaportSQL::getIdOS($data['OS'], $data['OS_ver']);
        RaportSQL::addUserData($data['client_name'], $data['ip'], $id_os, $data['freespace'], $data['lastbug']);

        file_put_contents("save_result.txt", $sql);
    }

}

$server = new soap_server();
$server->configureWSDL('SoapRaportLog.putRaport', $ns);
$server->wsdl->schemaTargetNamespace = $ns;

$server->register('SoapRaportLog.putRaport',
    array('raport' => 'xsd:string'),
    array('return' => 'xsd:string'),
    $ns);

$server->service($HTTP_RAW_POST_DATA);
