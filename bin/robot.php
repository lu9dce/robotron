<?php
/*
 * --------------------------------------------------------------------------
 * +-+ ROBOTRON-2084 +-+
 * CREADO POR LU9DCE
 * Copyright 2022 Eduardo Castillo
 * castilloeduardo@outlook.com.ar
 * Creative Commons
 * Attribution-NonCommercial-NoDerivatives 4.0 International
 * --------------------------------------------------------------------------
 */
error_reporting(0);
date_default_timezone_set("UTC");
$sendcq = "0";
$zz = "   ";
$rxrx = "0";
$dxc = "";
$tdx = "0";
$tempo = "0000";
$tempu = "0000";
$exclu = "";
$mega = "0";
$robot = " -----< ROBOTRON-2084 :";
$decalld = "";
static $iaia;
static $exclu;
static $tropa;
$version = "Current";
$sysos = strtoupper(substr(PHP_OS, 0, 3));

function fg($text, $color)
{
    if ($color == "0") {
        $out = "[30m";
    }
    if ($color == "1") {
        $out = "[31m";
    }
    if ($color == "2") {
        $out = "[32m";
    }
    if ($color == "3") {
        $out = "[33m";
    }
    if ($color == "4") {
        $out = "[34m";
    }
    if ($color == "5") {
        $out = "[35m";
    }
    if ($color == "6") {
        $out = "[36m";
    }
    if ($color == "7") {
        $out = "[37m";
    }
    if ($color == "8") {
        $out = "[90m";
    }
    if ($color == "9") {
        $out = "[91m";
    }
    return chr(27) . "$out" . "$text" . chr(27) . "[0m\n\r";
}
echo fg("##################################################################", 1);
echo " Created by Eduardo Castillo - LU9DCE\n\r";
echo " (C) 2022 - castilloeduardo@outlook.com.ar\n\r";
echo fg("------------------------------------------------------------------", 1);
sleep(1);
echo "$robot Preparing wait ... ";
sleep(1);
echo " Version $version\n\r";
echo fg("------------------------------------------------------------------", 5);
sleep(1);
$portrx = "";
if ($sysos != 'WIN') {
    $command = 'lsof -i -P -n | grep jtdx';
    $output = shell_exec($command);
    $line = explode(":", $output);
    $portrx = $line[1];
    $soft = "jtdx";
    if ($portrx == "") {
        $command = 'lsof -i -P -n | grep wsjtx';
        $output = shell_exec($command);
        $line = explode(":", $output);
        $portrx = $line[1];
        if ($portrx == "") {
            echo "\n\r\n\rThere is no compatible software running\n\r";
            sleep(10);
            exit();
        }
        $soft = "wsjt-x";
    }
    $portrx = substr($portrx, 0, - 1);
    $adi = $_SERVER['HOME'];
    $adix = $adi . "/.local/share/" . strtoupper($soft) . "/wsjtx_log.adi";
}

if ($sysos == 'WIN') {
    $command = 'tasklist | findstr /i jtdx.exe';
    $output = shell_exec($command);
    $line = explode("Console", $output);
    $str = $line[0];
    $soft = "jtdx";
    if ($str == "") {
        $command = 'tasklist | findstr /i wsjtx.exe';
        $output = shell_exec($command);
        $line = explode("Console", $output);
        $str = $line[0];
        if ($str == "") {
            echo "\n\r\n\rThere is no compatible software running\n\r";
            sleep(10);
            exit();
        }
        $soft = "wsjt-x";
    }
    $str = preg_replace("/\s+/", "", $str);
    if ($soft == "jtdx") {
        $line = explode("jtdx.exe", $str);
    } else {
        $line = explode("wsjtx.exe", $str);
    }
    $pid = $line[1];
    $command = 'netstat -ano -p udp | find "' . $pid . '"';
    $output = shell_exec($command);
    $str = preg_replace("/[\*]+/", "", $output);
    $str = preg_replace("/\s+/", "", $str);
    $line = explode(":", $str);
    $portrx = $line[1];
    // $portrx = substr ( $portrx, 0, - 1 );
    $adi = substr_replace(shell_exec("echo %LOCALAPPDATA%"), "", - 1);
    $adix = $adi . "\\" . strtoupper($soft) . "\\wsjtx_log.adi";
}
echo " -----> Robotron $soft\n\r";
echo " -----> Ctrl + C to exit\n\r";
echo fg("##################################################################", 1);
sleep(1);
echo " -----> Info\n\r";
echo " -----> CQ active (0=NO/1=YES) - N\n\r";
echo " -----> Response time          - NNNN\n\r";
echo " -----> Time that ends         - NNNN\n\r";
echo " -----> Current time           - NNNN\n\r";
echo " -----> Contacts made          - NN\n\r";
echo fg("##################################################################", 1);
sleep(1);
echo " Soft   : $soft\n\r";
echo " ADI    : $adix\n\r";
echo " PortRx : $portrx\n\r";
echo fg("##################################################################", 4);

function sendcq()
{
    $portrx = $GLOBALS["portrx"];
    $magic = $GLOBALS["magic"];
    $ver = $GLOBALS["ver"];
    $largoid = $GLOBALS["largoid"];
    $id = $GLOBALS["id"];
    $time = $GLOBALS["time"];
    $snr = $GLOBALS["snr"];
    $deltat = $GLOBALS["deltat"];
    $deltaf = $GLOBALS["deltaf"];
    $lmode = $GLOBALS["lmode"];
    $mode = $GLOBALS["mode"];
    $ml = $GLOBALS["ml"];
    $message = $GLOBALS["message"];
    $low = $GLOBALS["low"];
    $off = $GLOBALS["off"];
    $fp = stream_socket_client("udp://127.0.0.1:$portrx", $errno, $errstr);
    $msg = "$magic$ver" . "00000004" . "$largoid$id$time$snr$deltat$deltaf$lmode$mode$ml$message$low$off";
    $msg = hex2bin($msg);
    fwrite($fp, $msg);
    fclose($fp);
    return $sendcq = "1";
}

function locate($licrx)
{
    $dirt = __DIR__ . '/cty.csv';
    $z = strlen($licrx);
    for ($i = $z; $i >= 1; $i --) {
        $licrx = substr($licrx, 0, $i);
        $handle = fopen($dirt, "r");
        $lineNumber = 1;
        while (($raw_string = fgets($handle)) !== false) {
            $row = str_getcsv($raw_string);
            $array = explode(' ', $row[9]);
            foreach ($array as &$value) {
                $value = str_replace(';', '', $value);
                if ($value == $licrx) {
                    fclose($handle);
                    return $row[1];
                }
            }
            $lineNumber ++;
        }
    }
    $koko = "??";
    return $koko;
    fclose($handle);
}

echo "$robot Watchdog = 90s\n\r";
echo "$robot Pls disable watchdog of $soft\n\r";
echo fg("##################################################################", 4);
sleep(1);
echo "$robot $soft udp 2237\n\r";
echo "$robot forward to udp 2277\n\r";
echo fg("##################################################################", 1);
sleep(1);
$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
socket_bind($socket, "127.0.0.1", 2237);
$read = [
    $socket
];
$socketx = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
$write = null;
$except = null;
goto trama;
trama:
socket_select($read, $write, $except, null);
$data = socket_read($socket, 512);
socket_sendto($socketx, $data, 512, 0, '127.0.0.1', 2277);
$lee = bin2hex($data);
$type = substr($lee, 16, 8);
if ($type == "00000000") {
    goto tcero;
}
if ($type == "00000001") {
    goto tuno;
}
if ($type == "00000002") {
    goto tdos;
}
if ($type == "00000005") {
    goto tcin;
}
goto trama;
tcero:
$info = strtotime("now");
$qq = "$robot Info = $sendcq-" . substr($tempo, - 4) . "-" . substr($tempu, - 4) . "-" . substr($info, - 4) . "-$mega";
echo fg($qq, 7);
if ($sendcq == "1" && $info > $tempu) {
    goto dog;
}
$txw = date("i");
if (($txw == "00") || ($txw == "30")) {
    unset($exclu);
}
goto trama;
tuno:
$magic = substr($lee, 0, 8);
$magicd = hexdec($magic);
$ver = substr($lee, 8, 8);
$verd = hexdec($ver);
$type = substr($lee, 16, 8);
$typed = hexdec($type);
$largoid = substr($lee, 24, 8);
$largoidd = hexdec($largoid);
$larg = hexdec($largoid) * 2;
$id = substr($lee, 32, $larg);
$idd = hex2bin($id);
$con = 32 + $larg;
$freq = substr($lee, $con, 16);
$freqd = hexdec($freq);
$con = $con + 16;
$lmode = substr($lee, $con, 8);
$lmoded = hexdec($lmode) * 2;
$con = $con + 8;
$mode = substr($lee, $con, $lmoded);
$moded = hex2bin($mode);
$con = $con + $lmoded;
$ldxcall = substr($lee, $con, 8);
$ldxcalld = hexdec($ldxcall) * 2;
if ($ldxcall == "ffffffff") {
    $ldxcalld = "0";
}
$con = $con + 8;
$dxcall = substr($lee, $con, $ldxcalld);
$dxcalld = hex2bin($dxcall);
$con = $con + $ldxcalld;
$lreport = substr($lee, $con, 8);
$lreportd = hexdec($lreport) * 2;
$con = $con + 8;
$report = substr($lee, $con, $lreportd);
$reportd = hex2bin($report);
$con = $con + $lreportd;
$ltxmode = substr($lee, $con, 8);
$ltxmoded = hexdec($ltxmode) * 2;
$con = $con + 8;
$txmode = substr($lee, $con, $ltxmoded);
$txmoded = hex2bin($txmode);
$con = $con + $ltxmoded;
$txenable = substr($lee, $con, 2);
$txenabled = hexdec($txenable);
$con = $con + 2;
$transmitting = substr($lee, $con, 2);
$transmittingd = hexdec($transmitting);
$con = $con + 2;
$decoding = substr($lee, $con, 2);
$decodingd = hexdec($decoding);
$con = $con + 2;
$rxdf = substr($lee, $con, 8);
$rxdfd = hexdec($rxdf);
$con = $con + 8;
$txdf = substr($lee, $con, 8);
$txdfd = hexdec($txdf);
$con = $con + 8;
$ldecall = substr($lee, $con, 8);
$ldecalld = hexdec($ldecall) * 2;
$con = $con + 8;
$decall = substr($lee, $con, $ldecalld);
$decalld = hex2bin($decall);
$con = $con + $ldecalld;
$ldegrid = substr($lee, $con, 8);
$ldegridd = hexdec($ldecall) * 2;
$con = $con + 8;
$degrid = substr($lee, $con, $ldegridd);
$degridd = hex2bin($degrid);
$con = $con + $ldegridd;
$ldxgrid = substr($lee, $con, 8);
$ldxgridd = hexdec($ldxgrid) * 2;
$con = $con + 8;
$dxgrid = substr($lee, $con, $ldxgridd);
$dxgridd = hex2bin($dxgrid);
$con = $con + $ldxgridd;
$watchdog = substr($lee, $con, 2);
$watchdogd = hexdec($watchdog);
if ($decodingd == "0" && $rxrx > "0") {
    $qq = "$robot " . date("d/m/Y H:i:s") . " >-=-< $rxrx Decodeds";
    echo fg($qq, 6);
    $rxrx = 0;
}
if ($txenabled == "1") {
    $tdx = $tdx + 1;
}
if ($tdx == "2") {
    echo fg("$robot Trasmiting @ $dxc", 9);
}
if ($txenabled == "1" && $sendcq == "0") {
    goto toch;
}
goto trama;
tdos:
$lee = bin2hex($data);
$type = substr($lee, 16, 8);
$magic = substr($lee, 0, 8);
$magicd = hexdec($magic);
$ver = substr($lee, 8, 8);
$verd = hexdec($ver);
$type = substr($lee, 16, 8);
$typed = hexdec($type);
$largoid = substr($lee, 24, 8);
$largoidd = hexdec($largoid);
$larg = hexdec($largoid) * 2;
$id = substr($lee, 32, $larg);
$idd = hex2bin($id);
$con = 32 + $larg;
$newdecode = substr($lee, $con, 2);
$newdecoded = hexdec($newdecode);
$con = $con + 2;
$time = substr($lee, $con, 8);
$mil = hexdec($time);
$seconds = ceil($mil / 1000);
$timed = date("His", $seconds);
$con = $con + 8;
$snr = substr($lee, $con, 8);
$snrd = unpack("l", pack("l", hexdec($snr)))[1];
$con = $con + 8;
$deltat = substr($lee, $con, 16);
$deltatd = number_format(round(unpack("d", pack("Q", hexdec($deltat)))[1], 1), 1);
$con = $con + 16;
$deltaf = substr($lee, $con, 8);
$deltafd = unpack("l", pack("l", hexdec($deltaf)))[1];
$con = $con + 8;
$lmode = substr($lee, $con, 8);
$lmoded = hexdec($lmode) * 2;
$con = $con + 8;
$mode = substr($lee, $con, $lmoded);
$moded = hex2bin($mode);
$con = $con + $lmoded;
$ml = substr($lee, $con, 8);
$mld = hexdec($ml) * 2;
$con = $con + 8;
$message = substr($lee, $con, $mld);
$messaged = hex2bin($message);
$con = $con + $mld;
$low = substr($lee, $con, 2);
$lowd = hex2bin($low);
$con = $con + 2;
$off = substr($lee, $con, 2);
$offd = hex2bin($off);
goto ptex;
utex:
$rxrx = $rxrx + 1;
$tdx = "0";
goto trama;
tcua:
if ($zz == ">> ") {
    sendcq();
}
$sendcq = "1";
$zz = "   ";
// $qio = locate( $dxc );
echo fg("$robot I see @ $dxc in $qio", 9);
$tempo = strtotime("now");
$tempu = $tempo + 90;
goto trama;
tcin:
echo fg("$robot Successful contact @ $dxc", 2);
$mega = $mega + 1;
$sendcq = "0";
$tempo = "0000";
$tempu = "0000";
goto trama;
toch:
$fp = stream_socket_client("udp://127.0.0.1:$portrx", $errno, $errstr);
$msg = "$magic$ver" . "00000008" . "$largoid$id" . "00";
$msg = hex2bin($msg);
fwrite($fp, $msg);
fclose($fp);
$sendcq = "0";
$zz = "   ";
$dxc = "";
$tdx = "0";
$tempo = "0000";
$tempu = "0000";
$dxc = "";
echo fg("$robot Halt Tx", 5);
goto trama;
dog:
echo "$robot $dxc Not respond to the call\n\r";
$exclu[$dxc] = $dxc;
$dxc = "";
goto toch;
ptex:
$mess = rtrim($messaged);
$lin = explode(" ", $mess);
$zz = "   ";
$fg = "8";
if (sizeof($lin) == 4) {
    unset($lin[1]);
    $lin = array_values($lin);
}
if (isset($iaia[$lin[1]]) && sizeof($lin) == 3 && $lin[1] != $decalld && ($lin[0] == "CQ" || $lin[2] == "73" || $lin[2] == "RR73")) {
    $zz = "-- ";
    $fg = "1";
    goto shsh;
}
$contents = file_get_contents($adix);
if ($soft == "wsjt" || $soft == "jtdx") {
    $searchfor = "<call:" . strlen($lin[1]) . ">" . $lin[1];
} else {
    $searchfor = $lin[1];
}
if (str_contains($contents, $searchfor) && sizeof($lin) == 3 && $lin[1] != $decalld && ($lin[0] == "CQ" || $lin[2] == "73" || $lin[2] == "RR73")) {
    $zz = "-- ";
    $fg = "1";
    $iaia[$lin[1]] = $lin[1];
}
if (! str_contains($contents, $searchfor) && sizeof($lin) == 3 && $lin[1] != $decalld && ($lin[0] == "CQ" || $lin[2] == "73" || $lin[2] == "RR73")) {
    $zz = "-> ";
    $fg = "7";
}
if (! str_contains($contents, $searchfor) && sizeof($lin) == 3 && $lin[1] != $decalld && $sendcq == "0" && ($lin[0] == "CQ" || $lin[2] == "73" || $lin[2] == "RR73")) {
    $zz = ">> ";
    $fg = "2";
}
if ($snrd <= "-20" && $zz == ">> ") {
    $zz = "Lo ";
    $fg = "3";
}
if (isset($exclu[$lin[1]])) {
    $zz = "XX ";
    $fg = "4";
}
if (str_contains($messaged, $dxc) && $sendcq == "1") {
    $fg = "2";
}
shsh:
// $qio = locate($lin[1]);
// $tropa[$lin[1]] = $qio;

if (isset($tropa[$lin[1]])) {
    $qio = $tropa[$lin[1]];
} else {
    $qio = locate($lin[1]);
    $tropa[$lin[1]] = $qio;
}

$timed = substr($timed . "                    ", 0, 6);
$snrd = substr($snrd . "                    ", 0, 3);
$deltatd = substr($deltatd . "                    ", 0, 4);
$deltafd = substr($deltafd . "                    ", 0, 4);
$moded = substr($moded . "                    ", 0, 4);
$messaged = substr($messaged . "                    ", 0, 18);
$qio = substr($qio . "                    ", 0, 25);
$qq = "$timed  $snrd  $deltatd  $deltafd  $moded$zz$messaged  - $qio";
echo fg($qq, $fg);
if ($lin[0] != $decalld && $lin[0] != "CQ" && $lin[1] == $dxc && ($lin[2] != "73" || $lin[2] != "RR73")) {
    echo "$robot Busy?\n\r";
    $dxc = "";
    goto toch;
}
if ($lin[0] == $decalld && $lin[2] == "73") {
    echo fg("$robot Qso confirmed successfully", 2);
    goto toch;
}
if ($lin[0] == $decalld && $lin[2] != "73" && $sendcq == "0") {
    echo "$robot Reply? @ $lin[1]\n\r";
    $zz = ">> ";
}
if ($zz == ">> " && $sendcq == "0") {
    $dxc = $lin[1];
    goto tcua;
}
goto utex;
