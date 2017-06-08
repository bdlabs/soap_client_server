<?php
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 900);
ini_set('default_socket_timeout', 15);
class RaportSQL
{

    public static function getIdOS($os, $ver)
    {
        $id_os = 0;
        $sql = sprintf("SELECT id_OS
                FROM soap_OS
                WHERE name = \"%s\" and version = \"%s\";",
            $os, $ver);
        /*$result = mysql_query($sql);

        if ($row = mysql_fetch_row($result)) {
        $id_os = $row[0];
        } else {
        //insert to database
        $id_os = mysql_insert_id();
        }*/
        return $id_os;
    }

    public static function addUserData($name, $ip, $id_os, $freespace, $lastbug)
    {
        $freespaceGB = $freespace / 1024 / 1204 / 1024; // convert B -> GB
        $sql = sprintf("INSERT INTO soap_raport (username, ip, id_OS, freespace, freespaceGB, lastbug) VALUES
                (\"%s\", \"%s\", \"%u\", \"%u\", \"%.2F\", \"%u\");",
            $name, $ip, $id_os, $freespace, $freespaceGB, $lastbug);
        //mysql_query($sql);
    }
}

class SoapRaportLog
{
    public function __construct()
    {

    }

    public function putRaport($raport)
    {
        $this->saveRaport($raport);
        return "STATUS_OK";
    }

    private function saveRaport($data)
    {
        $id_os = RaportSQL::getIdOS($data['OS'], $data['OS_ver']);
        RaportSQL::addUserData($data['client_name'], $data['ip'], $id_os, $data['freespace'], $data['lastbug']);
        file_put_contents("save_result.txt", serialize($data));
    }

}

//Utworzenie serwera SOAP i wyeksportowanie funkcji remoteToUpper()
$soap = new SoapServer(null, array('uri' => ''));
$soap->setClass('SoapRaportLog');
$soap->handle();
//Użycie żądania POST dla wywołania usługi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
} else {
    //Bez podanych parametrów - wypisana zostanie lista dostępnych funkcji serwera SOAP
    echo "Funkcje udostępnione:<br>\n";
    foreach ($soap->getFunctions() as $func) {
        echo $func . "<br>\n";
    }
}
