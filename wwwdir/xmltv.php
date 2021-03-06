<?php
/*Rev:26.09.18r0*/

set_time_limit(0);
require './init.php';
$f0ac6ad2b40669833242a10c23cad2e0 = true;
if (!empty(ipTV_lib::$request['username']) && !empty(ipTV_lib::$request['password'])) {
    $username = ipTV_lib::$request['username'];
    $password = ipTV_lib::$request['password'];
    $cc787cb8dcdf96d84151c7a73aa831bf = empty(ipTV_lib::$request['prev_days']) ? 1 : abs(intval(ipTV_lib::$request['prev_days']));
    $E9bd18f1acef0191a216cfc27a1fcfce = empty(ipTV_lib::$request['next_days']) ? 1 : abs(intval(ipTV_lib::$request['next_days']));
    ini_set('memory_limit', -1);
    if ($result = ipTV_streaming::GetUserInfo(null, $username, $password, true, true, true)) {
        if ((is_null($result['exp_date']) or $result['exp_date'] > time()) and $result['admin_enabled'] == 1 and $result['enabled'] == 1) {
            $f0ac6ad2b40669833242a10c23cad2e0 = false;
            header('Content-Type: application/xml; charset=utf-8');
            $B1f3e7b388cc5e0a6305e957f3319444 = htmlspecialchars(ipTV_lib::$settings['server_name'], ENT_XML1 | ENT_QUOTES | ENT_DISALLOWED, 'UTF-8');
            echo '<?xml version="1.0" encoding="utf-8" ?><!DOCTYPE tv SYSTEM "xmltv.dtd">';
            echo "<tv generator-info-name=\"{$B1f3e7b388cc5e0a6305e957f3319444}\" generator-info-url=\"" . ipTV_lib::$StreamingServers[SERVER_ID]['site_url'] . '">';
            $ipTV_db->query('SELECT `stream_display_name`,`stream_icon`,`channel_id`,`epg_id` FROM `streams` WHERE `epg_id` IS NOT NULL');
            $rows = $ipTV_db->get_rows();
            $Bbb7af082729bf21d97453279778fdee = array();
            foreach ($rows as $row) {
                $Fe45388c8d13b941318458fa095983c3 = htmlspecialchars($row['stream_display_name'], ENT_XML1 | ENT_QUOTES | ENT_DISALLOWED, 'UTF-8');
                $stream_icon = htmlspecialchars($row['stream_icon'], ENT_XML1 | ENT_QUOTES | ENT_DISALLOWED, 'UTF-8');
                $e818ebc908da0ee69f4f99daba6a1a18 = htmlspecialchars($row['channel_id'], ENT_XML1 | ENT_QUOTES | ENT_DISALLOWED, 'UTF-8');
                echo "<channel id=\"{$e818ebc908da0ee69f4f99daba6a1a18}\">";
                echo "<display-name>{$Fe45388c8d13b941318458fa095983c3}</display-name>";
                if (!empty($row['stream_icon'])) {
                    echo "<icon src=\"{$stream_icon}\" />";
                }
                echo '</channel>';
                $Bbb7af082729bf21d97453279778fdee[] = $row['epg_id'];
            }
            $Bbb7af082729bf21d97453279778fdee = array_unique($Bbb7af082729bf21d97453279778fdee);
            $query = mysqli_query($ipTV_db->dbh, 'SELECT * FROM `epg_data` WHERE `epg_id` IN(' . implode(',', $Bbb7af082729bf21d97453279778fdee) . ') AND `start` BETWEEN \'' . date('Y-m-d H:i:00', strtotime("-{$cc787cb8dcdf96d84151c7a73aa831bf} day")) . '\' AND \'' . date('Y-m-d H:i:00', strtotime("+{$E9bd18f1acef0191a216cfc27a1fcfce} day")) . '\'', MYSQLI_USE_RESULT);
            //f1bcbc646b7caf73aa5b0b71be389f78:
            while ($row = mysqli_fetch_assoc($query)) {
                $E4416ae8f96620daee43ac43f9515200 = htmlspecialchars(base64_decode($row['title']), ENT_XML1 | ENT_QUOTES | ENT_DISALLOWED, 'UTF-8');
                $d4c3c80b508f5d00d05316e7aa0858de = htmlspecialchars(base64_decode($row['description']), ENT_XML1 | ENT_QUOTES | ENT_DISALLOWED, 'UTF-8');
                $e818ebc908da0ee69f4f99daba6a1a18 = htmlspecialchars($row['channel_id'], ENT_XML1 | ENT_QUOTES | ENT_DISALLOWED, 'UTF-8');
                $A73d5129dfb465fd94f3e09e9b179de0 = new DateTime($row['start']);
                $cdd6af41b10abec2ff03fe043f3df1cf = new DateTime($row['end']);
                $start = $A73d5129dfb465fd94f3e09e9b179de0->format('YmdHis O');
                $end = $cdd6af41b10abec2ff03fe043f3df1cf->format('YmdHis O');
                echo "<programme start=\"{$start}\" stop=\"{$end}\" channel=\"{$e818ebc908da0ee69f4f99daba6a1a18}\" >";
                echo '<title>' . $E4416ae8f96620daee43ac43f9515200 . '</title>';
                echo '<desc>' . $d4c3c80b508f5d00d05316e7aa0858de . '</desc>';
                echo '</programme>';
            }
            //cbb7c0585d6bcc07f8df162c7bd39253:
            echo '</tv>';
        }
    }
}
if ($f0ac6ad2b40669833242a10c23cad2e0) {
    http_response_code(401);
    CheckFlood();
}
?>
