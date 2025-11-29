<?php
    date_default_timezone_set('Asia/Jakarta');
    require_once '../db/connection.php';

    $act = $_GET['act'];
    $cup = array('pokal','coupe','copa','cup','troph','coppa','shield','piala','beker','trofeo','torneo','taça','tournament');
    $result = [ 'status' => true, 'value' => array(), 'message' => null ];

    if ($act=='country') {
        /*
        $options = array(
            CURLOPT_URL => "https://apiv2.allsportsapi.com/basketball/?met=Countries&APIkey=5f6bae0b6df3f9e943e9c5c4ac53b5942407e53c6b6ac94185b2ad70f01f3ac0",
            //CURLOPT_URL => "https://apiv2.allsportsapi.com/basketball/?met=Leagues&countryId=197&APIkey=5f6bae0b6df3f9e943e9c5c4ac53b5942407e53c6b6ac94185b2ad70f01f3ac0",
            //CURLOPT_URL => "https://apiv2.allsportsapi.com/basketball/?met=Standings&leagueId=766&APIkey=5f6bae0b6df3f9e943e9c5c4ac53b5942407e53c6b6ac94185b2ad70f01f3ac0",
            //CURLOPT_URL => "https://apiv2.allsportsapi.com/basketball/?met=Fixtures&leagueId=766&teamId=18&from=2025-10-27&to=2025-11-27&APIkey=5f6bae0b6df3f9e943e9c5c4ac53b5942407e53c6b6ac94185b2ad70f01f3ac0",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 5
        );
        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $raws = json_decode(curl_exec($curl));

        foreach ($raws->result as $row) {
            $insert = true;
            foreach ($nation as $exc) {
                if (str_contains(strtolower($row->country_name), $exc)) {
                    $insert = false;
                    break;
                }
            }
            if ($insert) {
                mysqli_query($conn, "INSERT INTO $table(country_key,country_name) VALUES($row->country_key,'$row->country_name')");
            }
        }
        */

        /*
        usort($rows->result, function($a, $b) {
            return $a->event_date <=> $b->event_date;
        });
        $rows = array_slice($rows->result,-5);
        */
        // event_key 217114
        // event_status Finished
        // home_team_key 18
        // away_team_key 25
        // scores []
        // scores->1stQuarter
        // scores->2ndQuarter
        // scores->3rdQuarter
        // scores->4thQuarter[0]->score_home

        /*
        $rows = array();        
        foreach ($raws as $row) {
            $name = $row['country_name'];
            if (strpos($name," ")) {
                $name = explode(" ",$name)[0];
            }

            $sql = mysqli_query($conn2, "SELECT * FROM sc_countries WHERE country_name LIKE '%$name%' LIMIT 0,1");
            if (mysqli_num_rows($sql) > 0) {
                $rowd = mysqli_fetch_assoc($sql);
                mysqli_query($conn, "UPDATE $table SET country_flag='".$rowd['country_logo']."' WHERE country_key=".$row['country_key']."");
                array_push($rows, $rowd['country_name']);
            }
        }
        */

        $table = 'as_countries';

        $sql = mysqli_query($conn, "SELECT * FROM $table ORDER BY country_name");
        $rows = [];
        while($row = mysqli_fetch_assoc($sql)) {
            $rows[] = $row;
            /*
            $options = array(
                CURLOPT_URL => "https://apiv2.allsportsapi.com/basketball/?met=Leagues&countryId=".$row['country_key']."&APIkey=5f6bae0b6df3f9e943e9c5c4ac53b5942407e53c6b6ac94185b2ad70f01f3ac0",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => false,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CONNECTTIMEOUT => 5
            );
            $curl = curl_init();
            curl_setopt_array($curl, $options);
            $raws = json_decode(curl_exec($curl));

            foreach ($raws->result as $rowd) {
                $insert = true;
                foreach ($cup as $exc) {
                    if (str_contains(strtolower($rowd->league_name), $exc)) {
                        $insert = false;
                        break;
                    }
                }
                if ($insert) {
                    mysqli_query($conn, "INSERT INTO as_leagues(league_key,league_name,country) VALUES($rowd->league_key,'$rowd->league_name',".$row['country_key'].")");
                }
            }
            */
        }

        $table = 'as_apis';

        $sql = mysqli_query($conn, "SELECT * FROM $table ORDER BY created_at DESC LIMIT 0,1");
        $apis = [];
        while($row = mysqli_fetch_assoc($sql)) {
            $apis[] = $row;
        }

        $result = [
            'status' => true,
            'value' => array($rows,$apis[0]),
            'message' => null
        ];

        echo json_encode($result);
    }
    else if ($act=='feature') {
        $from = date('Y-m-d', strtotime('2023-04-05'));
        $to = date('Y-m-d', strtotime('2023-04-05'));
        $table = 'sc_matchs';
        $league = 168;

        $options = array(
            CURLOPT_URL => "https://apiv3.apifootball.com/?action=get_events&from=$from&to=$to&league_id=$league&APIkey=$key",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 5
        );
        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $rows = json_decode(curl_exec($curl));

        $result = [
            'status' => true,
            'value' => $rows,
            'message' => null
        ];

        echo json_encode($result);
    }
    else if ($act=='league') {
        $country = $_GET['country'];
        $table = 'as_leagues';

        $sql = mysqli_query($conn, "SELECT * FROM $table WHERE country=$country ORDER BY league_key");
        $rows = [];
        while($row = mysqli_fetch_assoc($sql)) {
            $rows[] = $row;
        }

        $result = [
            'status' => true,
            'value' => array($rows,true),
            'message' => null
        ];

        echo json_encode($result);
    }
    else if ($act=='standing') {
        $key = $_GET['key'];
        $league = $_GET['league'];
        $table = 'as_standings';

        $sql = mysqli_query($conn, "SELECT * FROM as_leagues WHERE league_key=$league");
        $row = mysqli_fetch_assoc($sql);

        if ($row['updated_at'] == null) {
            $options = array(
                CURLOPT_URL => "https://apiv2.allsportsapi.com/basketball/?met=Standings&leagueId=$league&APIkey=$key",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => false,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CONNECTTIMEOUT => 5
            );
            $curl = curl_init();
            curl_setopt_array($curl, $options);
            $raws = json_decode(curl_exec($curl));

            foreach ($raws->result->total as $row) {
                $sql = mysqli_query($conn, "SELECT * FROM $table WHERE team_key=$row->team_key");
                if (mysqli_num_rows($sql) < 1) {
                    mysqli_query($conn, "INSERT INTO $table(standing_place,team_key,standing_team,standing_P,league) VALUES($row->standing_place,$row->team_key,'".str_replace("'","`",$row->standing_team)."',$row->standing_P,$league)");
                }
            }
            mysqli_query($conn, "UPDATE as_leagues SET season=1 WHERE league_key=$league");
        }
        else {
            $old = date('Y-m-d H:i:s', strtotime($row['updated_at']));
            $new = date('Y-m-d H:i:s', strtotime('-2 day'));
            $sql = mysqli_query($conn, "SELECT * FROM $table WHERE league=$league");

            if ($old < $new || mysqli_num_rows($sql) < 1) {
                mysqli_query($conn, "DELETE FROM $table WHERE league=$league");

                $options = array(
                    CURLOPT_URL => "https://apiv2.allsportsapi.com/basketball/?met=Standings&leagueId=$league&APIkey=$key",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HEADER => false,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CONNECTTIMEOUT => 5
                );
                $curl = curl_init();
                curl_setopt_array($curl, $options);
                $raws = json_decode(curl_exec($curl));

                foreach ($raws->result->total as $row) {
                    $sql = mysqli_query($conn, "SELECT * FROM $table WHERE team_key=$row->team_key");
                    if (mysqli_num_rows($sql) < 1) {
                        mysqli_query($conn, "INSERT INTO $table(standing_place,team_key,standing_team,standing_P,league) VALUES($row->standing_place,$row->team_key,'".str_replace("'","`",$row->standing_team)."',$row->standing_P,$league)");
                    }
                }
                mysqli_query($conn, "UPDATE as_leagues SET season=".((int)$row['season']+1)." WHERE league_key=$league");
            }
        }

        $sql = mysqli_query($conn, "SELECT * FROM $table WHERE league=$league ORDER BY standing_place");
        $rows = [];
        while($row = mysqli_fetch_assoc($sql)) {
            $rows[] = $row;
        }

        $result = [
            'status' => true,
            'value' => array($rows,""),
            'message' => null
        ];

        echo json_encode($result);
    }
    else if ($act=='match') {
        $key = $_GET['key'];
        $league = $_GET['league'];
        $team = array($_GET['home'],$_GET['away']);
        $from = date('Y-m-d', strtotime('-3 month'));
        $to = date('Y-m-d');
        $table = 'as_matchs';

        mysqli_query($conn, "TRUNCATE $table");

        $options = array(
            CURLOPT_URL => "https://apiv2.allsportsapi.com/basketball/?met=Fixtures&leagueId=$league&teamId=$team[0]&from=$from&to=$to&APIkey=$key",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 5
        );
        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $raws = json_decode(curl_exec($curl));

        if ($raws->result != null) {
            usort($raws->result, function($a, $b) {
                return $b->event_date <=> $a->event_date;
            });
            $p = 0;
            foreach ($raws->result as $row) {
                if (trim($row->event_status) == 'Finished' && $p < 5) {
                    foreach ($row->scores as $index => $rowg) {
                        mysqli_query($conn, "INSERT INTO $table(dates,home,away,quart,hscore,ascore) VALUES('$row->event_date',$row->home_team_key,$row->away_team_key,".($index+1).",".$rowg[0]->score_home.",".$rowg[0]->score_away.")");
                    }
                    $p++;
                }
            }
        }
        
        $options = array(
            CURLOPT_URL => "https://apiv2.allsportsapi.com/basketball/?met=Fixtures&leagueId=$league&teamId=$team[1]&from=$from&to=$to&APIkey=$key",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 5
        );
        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $raws = json_decode(curl_exec($curl));

        if ($raws->result != null) {
            usort($raws->result, function($a, $b) {
                return $b->event_date <=> $a->event_date;
            });
            $p = 0;
            foreach ($raws->result as $row) {
                if (trim($row->event_status) == 'Finished' && $p < 5) {
                    foreach ($row->scores as $index => $rowg) {
                        mysqli_query($conn, "INSERT INTO $table(dates,home,away,quart,hscore,ascore) VALUES('$row->event_date',$row->home_team_key,$row->away_team_key,".($index+1).",".$rowg[0]->score_home.",".$rowg[0]->score_away.")");
                    }
                    $p++;
                }
            }
        }
        
        $raws = [];
        $sql = mysqli_query($conn, "SELECT * FROM $table WHERE home=$team[0] OR away=$team[0] GROUP BY dates ORDER BY dates DESC LIMIT 0,5");
        while($row = mysqli_fetch_assoc($sql)) {
            $raws[] = $row;
        }

        $home = array();
        foreach ($raws as $index => $raw) {
            $rows = [];
            $sql = mysqli_query($conn, "SELECT * FROM $table WHERE home=$team[0] AND dates LIKE '".$raw['dates']."' OR away=$team[0] AND dates LIKE '".$raw['dates']."' ORDER BY quart");
            while($row = mysqli_fetch_assoc($sql)) {
                $rows[] = $row;
            }
            $scored_HT = 0; $conced_HT = 0;
            $scored_FT = 0; $conced_FT = 0;
            foreach ($rows as $row) {
                if ((int)$row['quart']<=3) {
                    if ((int)$row['home'] == $team[0]) {
                        $scored_HT = $scored_HT + (int)$row['hscore'];
                        $conced_HT = $conced_HT + (int)$row['ascore'];
                    }
                    else {
                        $scored_HT = $scored_HT + (int)$row['ascore'];
                        $conced_HT = $conced_HT + (int)$row['hscore'];
                    }
                }
                if ((int)$row['home'] == $team[0]) {
                    $scored_FT = $scored_FT + (int)$row['hscore'];
                    $conced_FT = $conced_FT + (int)$row['ascore'];
                }
                else {
                    $scored_FT = $scored_FT + (int)$row['ascore'];
                    $conced_FT = $conced_FT + (int)$row['hscore'];
                }
            }
            array_push($home,(object) [
                'scoredg_HT' => (double)($scored_HT / 10),
                'concedg_HT' => (double)($conced_HT / 10),
                'scoredg_FT' => (double)($scored_FT / 10),
                'concedg_FT' => (double)($conced_FT / 10),
                'scored_HT' => $scored_HT,
                'conced_HT' => $conced_HT,
                'scored_FT' => $scored_FT,
                'conced_FT' => $conced_FT,
                'id' => $index
            ]);
        }
        usort($home, function($a, $b) {
            return $b->id <=> $a->id;
        });

        $raws = [];
        $sql = mysqli_query($conn, "SELECT * FROM $table WHERE home=$team[1] OR away=$team[1] GROUP BY dates ORDER BY dates DESC LIMIT 0,5");
        while($row = mysqli_fetch_assoc($sql)) {
            $raws[] = $row;
        }

        $away = array();
        foreach ($raws as $index => $raw) {
            $rows = [];
            $sql = mysqli_query($conn, "SELECT * FROM $table WHERE home=$team[1] AND dates LIKE '".$raw['dates']."' OR away=$team[1] AND dates LIKE '".$raw['dates']."' ORDER BY quart");
            while($row = mysqli_fetch_assoc($sql)) {
                $rows[] = $row;
            }
            $scored_HT = 0; $conced_HT = 0;
            $scored_FT = 0; $conced_FT = 0;
            foreach ($rows as $row) {
                if ((int)$row['quart']<=3) {
                    if ((int)$row['away'] == $team[1]) {
                        $scored_HT = $scored_HT + (int)$row['ascore'];
                        $conced_HT = $conced_HT + (int)$row['hscore'];
                    }
                    else {
                        $scored_HT = $scored_HT + (int)$row['hscore'];
                        $conced_HT = $conced_HT + (int)$row['ascore'];
                    }
                }
                if ((int)$row['away'] == $team[1]) {
                    $scored_FT = $scored_FT + (int)$row['ascore'];
                    $conced_FT = $conced_FT + (int)$row['hscore'];
                }
                else {
                    $scored_FT = $scored_FT + (int)$row['hscore'];
                    $conced_FT = $conced_FT + (int)$row['ascore'];
                }
            }
            array_push($away,(object) [
                'scoredg_HT' => (double)($scored_HT / 10),
                'concedg_HT' => (double)($conced_HT / 10),
                'scoredg_FT' => (double)($scored_FT / 10),
                'concedg_FT' => (double)($conced_FT / 10),
                'scored_HT' => $scored_HT,
                'conced_HT' => $conced_HT,
                'scored_FT' => $scored_FT,
                'conced_FT' => $conced_FT,
                'id' => $index
            ]);
        }
        usort($away, function($a, $b) {
            return $b->id <=> $a->id;
        });

        $mafia = false;

        $result = [
            'status' => true,
            'value' => array($home,$away,'',$mafia),
            'message' => null
        ];

        echo json_encode($result);
    }
    else if ($act=='footy') {
        $league = $_POST['league'];
        $stat = $_POST['stat'];
        $table = 'sc_footys';

        $sql = mysqli_query($conn, "SELECT * FROM $table WHERE league_id=$league");
        $rows = [];
        while($row = mysqli_fetch_assoc($sql)) {
            $rows[] = $row;
        }

        if (count($rows)<1) {
            mysqli_query($conn, "INSERT INTO $table(league_id,stat) VALUES($league,'$stat')");
        }
        else {
            mysqli_query($conn, "UPDATE $table SET stat='$stat' WHERE league_id=$league");
        }
    }
