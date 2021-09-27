<?php

namespace App\Http\Controllers;

use App\Http\Controllers\RouterOsAPI;

class BackboneController extends Controller
{

    public function interfaceStatus()
    {
        return view('traffic-monitor');
    }

    public function monitorTraffic($interface)
    {
        $iphost = "10.100.20.1";
        $userhost = "admin";
        $passwdhost = "Sshd_1234";
        $api_puerto = 8728;

        $API = new RouterosAPI();
        $API->debug = false;
        if ($API->connect($iphost, $userhost, $passwdhost)) {

            $getinterfacetraffic = $API->comm("/interface/monitor-traffic", array(
                "interface" => "$interface",
                "once" => "",
            ));

            $rows = array();
            $rows2 = array();

            $ftx = $getinterfacetraffic[0]['tx-bits-per-second'];
            $frx = $getinterfacetraffic[0]['rx-bits-per-second'];

            $rows['name'] = 'Tx';
            $rows['data'][] = $ftx;
            $rows2['name'] = 'Rx';
            $rows2['data'][] = $frx;
        } else {
            echo "<font color='#ff0000'>Connection Failed!!</font>";
        }

        $API->disconnect();

        $result = array();

        array_push($result, $rows);
        array_push($result, $rows2);
        print json_encode($result);
    }

    public function dhcpClient($dhcpServer)
    {
        $iphost = "10.100.20.1";
        $userhost = "admin";
        $passwdhost = "Sshd_1234";
        $api_puerto = 8728;

        $API = new RouterosAPI();
        $API->debug = false;
        if ($API->connect($iphost, $userhost, $passwdhost)) {

            $commandResult = $API->comm("/ip/dhcp-server/lease/print
            ?server=" . $dhcpServer);
        } else {
            echo "<font color='#ff0000'>Connection Failed!!</font>";
        }

        $API->disconnect();

        return ($commandResult[0]);
    }
};
