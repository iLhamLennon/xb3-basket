<?php
    date_default_timezone_set('Asia/Jakarta');
    require_once '../db/connection.php';

    $act = $_GET['act'];
    $cup = array('pokal','coupe','copa','cup','troph','coppa','shield','piala','beker','trofeo','torneo','taça','tournament');
    $result = [ 'status' => true, 'value' => array(), 'message' => null ];

    if ($act=='country') {
        $table = 'as_countries';

        $sql = mysqli_query($conn, "SELECT * FROM $table ORDER BY country_name");
        $rows = [];
        while($row = mysqli_fetch_assoc($sql)) {
            $rows[] = $row;
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
    else if ($act=='fixture') {
        $key = $_GET['key'];
        $from = date('Y-m-d');
        $to = date('Y-m-d');
        $now = date('Y-m-d H:i:s');

        $newfixtures = true;
        $table = 'as_fixtures';
        $sql = mysqli_query($conn, "SELECT * FROM $table ORDER BY dates LIMIT 0,1");
        $row = mysqli_fetch_assoc($sql);
        $exist = date('Y-m-d', strtotime('+6 hour', strtotime($row['dates'])));
        $start = date('Y-m-d H:i:s', strtotime('+6 hour', strtotime($row['dates'])));
        $sql = mysqli_query($conn, "SELECT * FROM $table ORDER BY dates DESC LIMIT 0,1");
        $row = mysqli_fetch_assoc($sql);
        $end = date('Y-m-d H:i:s', strtotime('+6 hour', strtotime($row['dates'])));
        if (
            ($from == $exist) ||
            ($start <= $now && $end >= $now)
        ) {
            $newfixtures = false;
        }

        $table = 'as_leagues';
        $sql = mysqli_query($conn, "SELECT * FROM $table ORDER BY country,league_key");
        $rows = [];
        while($row = mysqli_fetch_assoc($sql)) {
            $rows[] = $row;
        }
        
        $table = 'as_fixtures';
        if ($newfixtures) {
            mysqli_query($conn, "TRUNCATE $table");
            foreach ($rows as $row) {
                $league = $row['league_key'];

                $options = array(
                    CURLOPT_URL => "https://apiv2.allsportsapi.com/basketball/?met=Fixtures&leagueId=$league&from=$from&to=$to&APIkey=$key",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HEADER => false,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CONNECTTIMEOUT => 5
                );
                $curl = curl_init();
                curl_setopt_array($curl, $options);
                $raws = json_decode(curl_exec($curl));

                foreach ($raws->result as $rowf) {
                    if (strlen($rowf->event_status) < 1 && (int)$rowf->league_round >= 5) {
                        mysqli_query($conn, "INSERT INTO $table(dates,home,away,nhome,naway,league,country) VALUES('".date('Y-m-d H:i:s', strtotime($rowf->event_date.' '.$rowf->event_time.':00'))."',$rowf->home_team_key,$rowf->away_team_key,'$rowf->event_home_team','$rowf->event_away_team',$league,".$row['country'].")");
                    }
                }            
            }
        }
        
        $t1 = 'as_fixtures';
        $t2 = 'as_countries';
        $t3 = 'as_leagues';

        $sql = mysqli_query($conn, "SELECT t2.country_key,t2.country_name,t2.country_flag,t3.league_key,t3.league_name,t1.dates,t1.home,t1.away,t1.nhome,t1.naway FROM $t1 t1 LEFT JOIN $t2 t2 ON t2.country_key=t1.country LEFT JOIN $t3 t3 ON t3.league_key=t1.league ORDER BY t1.dates,t1.country,t1.league");
        $rows = [];
        while($row = mysqli_fetch_assoc($sql)) {
            $rows[] = $row;
        }
        $list = array();
        foreach ($rows as $row) {
            if (date('Y-m-d H:i:s', strtotime("+6 hour", strtotime($row['dates']))) >= $now) {
                $h = (int)date('H', strtotime("+6 hour", strtotime($row['dates'])));
                if ($h >= 12 && $h <= 23) {
                    $h = $h - 12;
                }
                $m = date('i A', strtotime("+6 hour", strtotime($row['dates'])));
                array_push($list, (object) [
                    'country_key' => $row['country_key'],
                    'country_name' => $row['country_name'],
                    'country_flag' => $row['country_flag'],
                    'league_key' => $row['league_key'],
                    'league_name' => $row['league_name'],
                    'dates' => $h.':'.$m,
                    'home' => $row['home'],
                    'away' => $row['away'],
                    'nhome' => $row['nhome'],
                    'naway' => $row['naway']
                ]);
            }
        }

        $result = [
            'status' => true,
            'value' => $list,
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
        $session = (int)$row['session'] + 1;

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
                mysqli_query($conn, "UPDATE as_leagues SET season=$session WHERE league_key=$league");
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

                if (count($home) > 4) {
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

                    if (count($away) > 4) {
                        $options = array(
                            CURLOPT_URL => "https://apiv2.allsportsapi.com/basketball/?met=Livescore&leagueId=$league&APIkey=$key",
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_HEADER => false,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_CONNECTTIMEOUT => 5
                        );
                        $curl = curl_init();
                        curl_setopt_array($curl, $options);
                        $raws = json_decode(curl_exec($curl));

                        if ($raws->success > 0) {
                            $minute = 0;
                            $score_HT_Q = 0; $score_FT_Q = 0; $score_3_Q = 0; $score_4_Q = 0;
                            foreach ($raws->result as $row) {
                                if ($row->home_team_key == $team[0] || $row->away_team_key == $team[0]) {
                                    $start = new DateTime(date('Y-m-d H:i', strtotime('+6 hour',strtotime($row->event_date.' '.$row->event_time))));
                                    $finish = new DateTime(date('Y-m-d H:i'));
                                    $minute = ($start->diff($finish)->h*60)+$start->diff($finish)->i;
                                    foreach ($row->scores as $index => $rowg) {
                                        if ($rowg[0] != null) {
                                            if ($index < 2) {
                                                $score_HT_Q = (int)$rowg[0]->score_home+(int)$rowg[0]->score_away;
                                            }
                                            else if ($index < 3) {
                                                $score_FT_Q = $score_HT_Q+(int)$rowg[0]->score_home+(int)$rowg[0]->score_away;
                                            }
                                            else if ($index < 4) {
                                                $score_3_Q = (int)$rowg[0]->score_home+(int)$rowg[0]->score_away;
                                            }
                                            else if ($index < 5) {
                                                $score_4_Q = (int)$rowg[0]->score_home+(int)$rowg[0]->score_away;
                                            }
                                        }
                                    }
                                    break;
                                }
                            }

                            $mafia = false;

                            $getMinMax = getMinMax($home,$away);
                            $getCriteria1 = count($getMinMax) > 0 ? getCriteria1($home,$away,$getMinMax) : [];
                            $getCriteria2 = count($getCriteria1) > 0 ? getCriteria2($home,$away,$getMinMax,$score_HT_Q,$score_FT_Q,$score_3_Q,$score_4_Q) : [];
                            $getRange = count($getCriteria2) > 0 ? getRange($getCriteria2) : [];

                            $result = [
                                'status' => true,
                                'value' => array(
                                    $home,$away,'',$mafia,
                                    array(
                                        $getCriteria1,
                                        $getCriteria2,
                                        $getMinMax
                                    ),
                                    $minute,
                                    $getRange
                                ),
                                'message' => null
                            ];
                        }
                    }
                }
            }
        }

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

    function getRange($getCriteria2) {
        $minPercent_HT = $getCriteria2[0][0] >= $getCriteria2[1][0] ? $getCriteria2[0][0] : $getCriteria2[1][0];
        $minPercent_FT = $getCriteria2[2][0] >= $getCriteria2[3][0] ? $getCriteria2[2][0] : $getCriteria2[3][0];
        $minTotal_HT = (int)$getCriteria2[5][0][0];
        $minTotal_FT = (int)$getCriteria2[5][1][0];
        $perfect_HT = ($minTotal_HT - ((int)(($minTotal_HT / $minPercent_HT) * 100) - $minTotal_HT)) / 100;
        $perfect_FT = ($minTotal_FT - ((int)(($minTotal_FT / $minPercent_FT) * 100) - $minTotal_FT)) / 100;

        return array(
            array(number_format($perfect_HT*100,0),number_format(($perfect_HT*100)+($perfect_HT*15),0)),
            array(number_format($perfect_FT*100,0),number_format(($perfect_FT*100)+($perfect_FT*15),0))
        );
    }

    function getMinMax($home,$away) {
        $arrH = $home;
        $minscoredH_HT_1 = 0; $minscoredH_HT_2 = 0; $minscoredH_HT_3 = 0;
        $minconcedH_HT_1 = 0; $minconcedH_HT_2 = 0; $minconcedH_HT_3 = 0;
        $minscoredH_FT_1 = 0; $minscoredH_FT_2 = 0; $minscoredH_FT_3 = 0;
        $minconcedH_FT_1 = 0; $minconcedH_FT_2 = 0; $minconcedH_FT_3 = 0;
        usort($arrH, function($a, $b) {
            return $a->scored_HT <=> $b->scored_HT;
        });
        foreach ($arrH as $row) {
            if ($minscoredH_HT_1 == 0) {
                $minscoredH_HT_1 = $row->scored_HT;
            }
            else if ($minscoredH_HT_2 == 0 && $row->scored_HT > $minscoredH_HT_1) {
                $minscoredH_HT_2 = $row->scored_HT;
            }
            else if ($minscoredH_HT_3 == 0 && $row->scored_HT > $minscoredH_HT_2) {
                $minscoredH_HT_3 = $row->scored_HT;
            }
        }
        usort($arrH, function($a, $b) {
            return $a->conced_HT <=> $b->conced_HT;
        });
        foreach ($arrH as $row) {
            if ($minconcedH_HT_1 == 0) {
                $minconcedH_HT_1 = $row->conced_HT;
            }
            else if ($minconcedH_HT_2 == 0 && $row->conced_HT > $minconcedH_HT_1) {
                $minconcedH_HT_2 = $row->conced_HT;
            }
            else if ($minconcedH_HT_3 == 0 && $row->conced_HT > $minconcedH_HT_2) {
                $minconcedH_HT_3 = $row->conced_HT;
            }
        }
        usort($arrH, function($a, $b) {
            return $a->scored_FT <=> $b->scored_FT;
        });
        foreach ($arrH as $row) {
            if ($minscoredH_FT_1 == 0) {
                $minscoredH_FT_1 = $row->scored_FT;
            }
            else if ($minscoredH_FT_2 == 0 && $row->scored_FT > $minscoredH_FT_1) {
                $minscoredH_FT_2 = $row->scored_FT;
            }
            else if ($minscoredH_FT_3 == 0 && $row->scored_FT > $minscoredH_FT_2) {
                $minscoredH_FT_3 = $row->scored_FT;
            }
        }
        usort($arrH, function($a, $b) {
            return $a->conced_FT <=> $b->conced_FT;
        });
        foreach ($arrH as $row) {
            if ($minconcedH_FT_1 == 0) {
                $minconcedH_FT_1 = $row->conced_FT;
            }
            else if ($minconcedH_FT_2 == 0 && $row->conced_FT > $minconcedH_FT_1) {
                $minconcedH_FT_2 = $row->conced_FT;
            }
            else if ($minconcedH_FT_3 == 0 && $row->conced_FT > $minconcedH_FT_2) {
                $minconcedH_FT_3 = $row->conced_FT;
            }
        }

        $arrA = $away;
        $minscoredA_HT_1 = 0; $minscoredA_HT_2 = 0; $minscoredA_HT_3 = 0;
        $minconcedA_HT_1 = 0; $minconcedA_HT_2 = 0; $minconcedA_HT_3 = 0;
        $minscoredA_FT_1 = 0; $minscoredA_FT_2 = 0; $minscoredA_FT_3 = 0;
        $minconcedA_FT_1 = 0; $minconcedA_FT_2 = 0; $minconcedA_FT_3 = 0;
        usort($arrA, function($a, $b) {
            return $a->scored_HT <=> $b->scored_HT;
        });
        foreach ($arrA as $row) {
            if ($minscoredA_HT_1 == 0) {
                $minscoredA_HT_1 = $row->scored_HT;
            }
            else if ($minscoredA_HT_2 == 0 && $row->scored_HT > $minscoredA_HT_1) {
                $minscoredA_HT_2 = $row->scored_HT;
            }
            else if ($minscoredA_HT_3 == 0 && $row->scored_HT > $minscoredA_HT_2) {
                $minscoredA_HT_3 = $row->scored_HT;
            }
        }
        usort($arrA, function($a, $b) {
            return $a->conced_HT <=> $b->conced_HT;
        });
        foreach ($arrA as $row) {
            if ($minconcedA_HT_1 == 0) {
                $minconcedA_HT_1 = $row->conced_HT;
            }
            else if ($minconcedA_HT_2 == 0 && $row->conced_HT > $minconcedA_HT_1) {
                $minconcedA_HT_2 = $row->conced_HT;
            }
            else if ($minconcedA_HT_3 == 0 && $row->conced_HT > $minconcedA_HT_2) {
                $minconcedA_HT_3 = $row->conced_HT;
            }
        }
        usort($arrA, function($a, $b) {
            return $a->scored_FT <=> $b->scored_FT;
        });
        foreach ($arrA as $row) {
            if ($minscoredA_FT_1 == 0) {
                $minscoredA_FT_1 = $row->scored_FT;
            }
            else if ($minscoredA_FT_2 == 0 && $row->scored_FT > $minscoredA_FT_1) {
                $minscoredA_FT_2 = $row->scored_FT;
            }
            else if ($minscoredA_FT_3 == 0 && $row->scored_FT > $minscoredA_FT_2) {
                $minscoredA_FT_3 = $row->scored_FT;
            }
        }
        usort($arrA, function($a, $b) {
            return $a->conced_FT <=> $b->conced_FT;
        });
        foreach ($arrA as $row) {
            if ($minconcedA_FT_1 == 0) {
                $minconcedA_FT_1 = $row->conced_FT;
            }
            else if ($minconcedA_FT_2 == 0 && $row->conced_FT > $minconcedA_FT_1) {
                $minconcedA_FT_2 = $row->conced_FT;
            }
            else if ($minconcedA_FT_3 == 0 && $row->conced_FT > $minconcedA_FT_2) {
                $minconcedA_FT_3 = $row->conced_FT;
            }
        }

        return array(
            array($minscoredH_HT_1,$minscoredH_HT_2,$minscoredH_HT_3),array($minconcedH_HT_1,$minconcedH_HT_2,$minconcedH_HT_3),
            array($minscoredA_HT_1,$minscoredA_HT_2,$minscoredA_HT_3),array($minconcedA_HT_1,$minconcedA_HT_2,$minconcedA_HT_3),
            array($minscoredH_FT_1,$minscoredH_FT_2,$minscoredH_FT_3),array($minconcedH_FT_1,$minconcedH_FT_2,$minconcedH_FT_3),
            array($minscoredA_FT_1,$minscoredA_FT_2,$minscoredA_FT_3),array($minconcedA_FT_1,$minconcedA_FT_2,$minconcedA_FT_3)
        );
    }

    function getCriteria1($home,$away,$minmax) {
        $scoredH_HT = true; $scoredH_FT = true;
        $concedH_HT = true; $concedH_FT = true;
        $scoredA_HT = true; $scoredA_FT = true;
        $concedA_HT = true; $concedA_FT = true;

        $last1_H = array_slice($home,3,1);
        $last2_H = array_slice($home,4,1);
        $last1_A = array_slice($away,3,1);
        $last2_A = array_slice($away,4,1);

        if ($last2_H[0]->scored_HT < $last1_H[0]->scored_HT) {
            $scoredH_HT = false;
        }
        if ($last2_H[0]->scored_FT < $last1_H[0]->scored_FT) {
            $scoredH_FT = false;
        }
        if ($last2_H[0]->conced_HT <= $last1_H[0]->conced_HT) {
            $concedH_HT = false;
            if ($last2_H[0]->conced_HT == $last1_H[0]->conced_HT && $last2_H[0]->conced_HT > $minmax[1][0] && $last1_H[0]->conced_HT > $minmax[1][0]) {
                $concedH_HT = true;
            }
        }
        if ($last2_H[0]->conced_FT <= $last1_H[0]->conced_FT) {
            $concedH_FT = false;
            if ($last2_H[0]->conced_FT == $last1_H[0]->conced_FT && $last2_H[0]->conced_FT > $minmax[5][0] && $last1_H[0]->conced_FT > $minmax[5][0]) {
                $concedH_FT = true;
            }
        }
        
        if ($last2_A[0]->scored_HT < $last1_A[0]->scored_HT) {
            $scoredA_HT = false;
        }
        if ($last2_A[0]->scored_FT < $last1_A[0]->scored_FT) {
            $scoredA_FT = false;
        }
        if ($last2_A[0]->conced_HT <= $last1_A[0]->conced_HT) {
            $concedA_HT = false;
            if ($last2_A[0]->conced_HT == $last1_A[0]->conced_HT && $last2_A[0]->conced_HT > $minmax[3][0] && $last1_A[0]->conced_HT > $minmax[3][0]) {
                $concedA_HT = true;
            }
        }
        if ($last2_A[0]->conced_FT <= $last1_A[0]->conced_FT) {
            $concedA_FT = false;
            if ($last2_A[0]->conced_FT == $last1_A[0]->conced_FT && $last2_A[0]->conced_FT > $minmax[7][0] && $last1_A[0]->conced_FT > $minmax[7][0]) {
                $concedA_FT = true;
            }
        }

        return array(
            array($scoredH_HT,$concedH_HT,$scoredA_HT,$concedA_HT),
            array($scoredH_FT,$concedH_FT,$scoredA_FT,$concedA_FT)
        );
    }

    function getCriteria2($home,$away,$minmax,$score_HT,$score_FT,$score_3Q,$score_4Q) {
        $prob1 = getProcentage1($home,$away,$minmax,0,0,0);
        $prob2 = getProcentage2($home,$away,$minmax,0,0,0);

        $xG_H_HT1 = ((($prob1[0][0][0]+$prob1[0][3][0])/2)+((($prob1[1][0][0]*20)+($prob1[1][1][0]*20))/2))/2;
        $xG_H_HT2 = ((($prob1[0][0][1]+$prob1[0][3][1])/2)+((($prob1[1][0][1]*20)+($prob1[1][1][1]*20))/2))/2;
        $xG_H_HT3 = ((($prob1[0][0][2]+$prob1[0][3][2])/2)+((($prob1[1][0][2]*20)+($prob1[1][1][2]*20))/2))/2;
        $xG_A_HT1 = ((($prob1[0][2][0]+$prob1[0][1][0])/2)+((($prob1[1][1][0]*20)+($prob1[1][0][0]*20))/2))/2;
        $xG_A_HT2 = ((($prob1[0][2][1]+$prob1[0][1][1])/2)+((($prob1[1][1][1]*20)+($prob1[1][0][1]*20))/2))/2;
        $xG_A_HT3 = ((($prob1[0][2][2]+$prob1[0][1][2])/2)+((($prob1[1][1][2]*20)+($prob1[1][0][2]*20))/2))/2;

        $xG_H_FT1 = ((($prob2[0][0][0]+$prob2[0][3][0])/2)+((($prob2[1][0][0]*20)+($prob2[1][1][0]*20))/2))/2;
        $xG_H_FT2 = ((($prob2[0][0][1]+$prob2[0][3][1])/2)+((($prob2[1][0][1]*20)+($prob2[1][1][1]*20))/2))/2;
        $xG_H_FT3 = ((($prob2[0][0][2]+$prob2[0][3][2])/2)+((($prob2[1][0][2]*20)+($prob2[1][1][2]*20))/2))/2;
        $xG_A_FT1 = ((($prob2[0][2][0]+$prob2[0][1][0])/2)+((($prob2[1][1][0]*20)+($prob2[1][0][0]*20))/2))/2;
        $xG_A_FT2 = ((($prob2[0][2][1]+$prob2[0][1][1])/2)+((($prob2[1][1][1]*20)+($prob2[1][0][1]*20))/2))/2;
        $xG_A_FT3 = ((($prob2[0][2][2]+$prob2[0][1][2])/2)+((($prob2[1][1][2]*20)+($prob2[1][0][2]*20))/2))/2;

        $xB_HT = $xG_H_HT1 >= 100 || $xG_A_HT1 >= 100 ? 2 : 1;
        $xB_FT = $xG_H_FT1 >= 200 || $xG_A_FT1 >= 200 ? 2 : 1;

        $per_H_HT = $xG_H_HT1 / 100;
        $per_A_HT = $xG_A_HT1 / 100;
        $per_H_FT = $xG_H_FT1 / 100;
        $per_A_FT = $xG_A_FT1 / 100;

        $max_H_HT = (int)(($minmax[0][0]+$minmax[3][0]) / $per_H_HT);
        $max_A_HT = (int)(($minmax[2][0]+$minmax[1][0]) / $per_A_HT);
        $max_H_FT = (int)(($minmax[4][0]+$minmax[7][0]) / $per_H_FT);
        $max_A_FT = (int)(($minmax[6][0]+$minmax[5][0]) / $per_A_FT);
        $max_HT = $max_H_HT < $max_A_HT ? $max_H_HT : $max_A_HT;
        $max_FT = $max_H_FT < $max_A_FT ? $max_H_FT : $max_A_FT;

        $plus_fix_HT = $score_HT - (int)($max_HT / 2);
        $plus_fix_FT = $score_FT - (int)($max_FT / 2);
        $fix_HT1 = ($score_HT + 1) >= ($max_HT / 2) ? number_format($plus_fix_HT+$max_HT, 0) : number_format($max_HT, 0);
        $fix_HT2 = ($score_HT + 1) >= ($max_HT / 2) ? number_format((($plus_fix_HT+$max_HT) + ((($plus_fix_HT+$max_HT) / 100) * 7)), 0) : number_format($max_HT + (($max_HT / 100) * 7), 0); // 93%
        $fix_HT3 = ($score_HT + 1) >= ($max_HT / 2) ? number_format((($plus_fix_HT+$max_HT) + ((($plus_fix_HT+$max_HT) / 100) * 15)), 0) : number_format($max_HT + (($max_HT / 100) * 15), 0); // 85%
        $fix_FT1 = ($score_FT + 1) >= ($max_FT / 2) ? number_format($plus_fix_FT+$max_FT, 0) : number_format($max_FT, 0);
        $fix_FT2 = ($score_FT + 1) >= ($max_FT / 2) ? number_format((($plus_fix_FT+$max_FT) + ((($plus_fix_FT+$max_FT) / 100) * 7)), 0) : number_format($max_FT + (($max_FT / 100) * 7), 0); // 93%
        $fix_FT3 = ($score_FT + 1) >= ($max_FT / 2) ? number_format((($plus_fix_FT+$max_FT) + ((($plus_fix_FT+$max_FT) / 100) * 15)), 0) : number_format($max_FT + (($max_FT / 100) * 15), 0); // 85%

        if (($score_HT + 1) >= ($max_HT / 2)) {
            $prob1 = getProcentage1($home,$away,$minmax,
                $plus_fix_HT+$max_HT,
                (($plus_fix_HT+$max_HT) + ((($plus_fix_HT+$max_HT) / 100) * 7)),
                (($plus_fix_HT+$max_HT) + ((($plus_fix_HT+$max_HT) / 100) * 15))
            );

            $xG_H_HT1 = ((($prob1[0][0][0]+$prob1[0][3][0])/2)+((($prob1[1][0][0]*20)+($prob1[1][1][0]*20))/2))/2;
            $xG_H_HT2 = ((($prob1[0][0][1]+$prob1[0][3][1])/2)+((($prob1[1][0][1]*20)+($prob1[1][1][1]*20))/2))/2;
            $xG_H_HT3 = ((($prob1[0][0][2]+$prob1[0][3][2])/2)+((($prob1[1][0][2]*20)+($prob1[1][1][2]*20))/2))/2;
            $xG_A_HT1 = ((($prob1[0][2][0]+$prob1[0][1][0])/2)+((($prob1[1][1][0]*20)+($prob1[1][0][0]*20))/2))/2;
            $xG_A_HT2 = ((($prob1[0][2][1]+$prob1[0][1][1])/2)+((($prob1[1][1][1]*20)+($prob1[1][0][1]*20))/2))/2;
            $xG_A_HT3 = ((($prob1[0][2][2]+$prob1[0][1][2])/2)+((($prob1[1][1][2]*20)+($prob1[1][0][2]*20))/2))/2;
        }
        if (($score_FT + 1) >= ($max_FT / 2)) {
            $prob2 = getProcentage2($home,$away,$minmax,
                $plus_fix_FT+$max_FT,
                (($plus_fix_FT+$max_FT) + ((($plus_fix_FT+$max_FT) / 100) * 7)),
                (($plus_fix_FT+$max_FT) + ((($plus_fix_FT+$max_FT) / 100) * 15))
            );

            $xG_H_FT1 = ((($prob2[0][0][0]+$prob2[0][3][0])/2)+((($prob2[1][0][0]*20)+($prob2[1][1][0]*20))/2))/2;
            $xG_H_FT2 = ((($prob2[0][0][1]+$prob2[0][3][1])/2)+((($prob2[1][0][1]*20)+($prob2[1][1][1]*20))/2))/2;
            $xG_H_FT3 = ((($prob2[0][0][2]+$prob2[0][3][2])/2)+((($prob2[1][0][2]*20)+($prob2[1][1][2]*20))/2))/2;
            $xG_A_FT1 = ((($prob2[0][2][0]+$prob2[0][1][0])/2)+((($prob2[1][1][0]*20)+($prob2[1][0][0]*20))/2))/2;
            $xG_A_FT2 = ((($prob2[0][2][1]+$prob2[0][1][1])/2)+((($prob2[1][1][1]*20)+($prob2[1][0][1]*20))/2))/2;
            $xG_A_FT3 = ((($prob2[0][2][2]+$prob2[0][1][2])/2)+((($prob2[1][1][2]*20)+($prob2[1][0][2]*20))/2))/2;
        }

        return array(
            array(
                $prob1[1][0][0]>3 && $prob1[1][1][0]>3?(($score_HT + 1) >= ($max_HT / 2) || ($score_HT < 1 && $score_FT < 1) || ($score_HT > 0 && $score_FT < 1)?$xG_H_HT1:70):70,
                $prob1[1][0][1]>3 && $prob1[1][1][1]>3?(($score_HT + 1) >= ($max_HT / 2) || ($score_HT < 1 && $score_FT < 1) || ($score_HT > 0 && $score_FT < 1)?$xG_H_HT2:70):70,
                $prob1[1][0][2]>3 && $prob1[1][1][2]>3?(($score_HT + 1) >= ($max_HT / 2) || ($score_HT < 1 && $score_FT < 1) || ($score_HT > 0 && $score_FT < 1)?$xG_H_HT3:70):70
            ),
            array(
                $prob1[1][1][0]>3 && $prob1[1][0][0]>3?(($score_HT + 1) >= ($max_HT / 2) || ($score_HT < 1 && $score_FT < 1) || ($score_HT > 0 && $score_FT < 1)?$xG_A_HT1:70):70,
                $prob1[1][1][1]>3 && $prob1[1][0][1]>3?(($score_HT + 1) >= ($max_HT / 2) || ($score_HT < 1 && $score_FT < 1) || ($score_HT > 0 && $score_FT < 1)?$xG_A_HT2:70):70,
                $prob1[1][1][2]>3 && $prob1[1][0][2]>3?(($score_HT + 1) >= ($max_HT / 2) || ($score_HT < 1 && $score_FT < 1) || ($score_HT > 0 && $score_FT < 1)?$xG_A_HT3:70):70
            ),
            array(
                $prob2[1][0][0]>3 && $prob2[1][1][0]>3?((($score_FT + 1) < ($max_FT / 2) && $xG_H_HT1 == 100 && $score_FT >= (int)$fix_HT1) || ($score_FT + 1) >= ($max_FT / 2) || ($score_FT < 1 && $score_3Q < 1) || ($score_FT > 0 && $score_3Q < 1)?$xG_H_FT1:70):70,
                $prob2[1][0][1]>3 && $prob2[1][1][1]>3?((($score_FT + 1) < ($max_FT / 2) && $xG_H_HT2 >= 93 && $score_FT >= (int)$fix_HT2) || ($score_FT + 1) >= ($max_FT / 2) || ($score_FT < 1 && $score_3Q < 1) || ($score_FT > 0 && $score_3Q < 1)?$xG_H_FT2:70):70,
                $prob2[1][0][2]>3 && $prob2[1][1][2]>3?((($score_FT + 1) < ($max_FT / 2) && $xG_H_HT3 >= 85 && $score_FT >= (int)$fix_HT3) || ($score_FT + 1) >= ($max_FT / 2) || ($score_FT < 1 && $score_3Q < 1) || ($score_FT > 0 && $score_3Q < 1)?$xG_H_FT3:70):70
            ),
            array(
                $prob2[1][1][0]>3 && $prob2[1][0][0]>3?((($score_FT + 1) < ($max_FT / 2) && $xG_A_HT1 == 100 && $score_FT > (int)$fix_HT1) || ($score_FT + 1) >= ($max_FT / 2) || ($score_FT < 1 && $score_3Q < 1) || ($score_FT > 0 && $score_3Q < 1)?$xG_A_FT1:70):70,
                $prob2[1][1][1]>3 && $prob2[1][0][1]>3?((($score_FT + 1) < ($max_FT / 2) && $xG_A_HT2 >= 93 && $score_FT > (int)$fix_HT2) || ($score_FT + 1) >= ($max_FT / 2) || ($score_FT < 1 && $score_3Q < 1) || ($score_FT > 0 && $score_3Q < 1)?$xG_A_FT2:70):70,
                $prob2[1][1][2]>3 && $prob2[1][0][2]>3?((($score_FT + 1) < ($max_FT / 2) && $xG_A_HT3 >= 85 && $score_FT > (int)$fix_HT3) || ($score_FT + 1) >= ($max_FT / 2) || ($score_FT < 1 && $score_3Q < 1) || ($score_FT > 0 && $score_3Q < 1)?$xG_A_FT3:70):70
            ),
            array($xB_HT,$xB_FT),
            array(
                array($fix_HT1,$fix_HT2,$fix_HT3),
                array($fix_FT1,$fix_FT2,$fix_FT3)
            ),
            array($score_HT,$score_FT+$score_3Q+$score_4Q)
        );
    }

    function getProcentage1($home,$away,$minmax,$xp_HT_1,$xp_HT_2,$xp_HT_3) {
        $xGscored_H1_HT = 0; $xGscored_H2_HT = 0; $xGscored_H3_HT = 0;
        $xGconced_H1_HT = 0; $xGconced_H2_HT = 0; $xGconced_H3_HT = 0;
        $xGscored_A1_HT = 0; $xGscored_A2_HT = 0; $xGscored_A3_HT = 0;
        $xGconced_A1_HT = 0; $xGconced_A2_HT = 0; $xGconced_A3_HT = 0;
        $totH_1_HT = 0; $totH_2_HT = 0; $totH_3_HT = 0;
        $totA_1_HT = 0; $totA_2_HT = 0; $totA_3_HT = 0;
        
        $xG_plusHT_1 = $xp_HT_1 < 1 ? $minmax[0][0]+$minmax[3][0] : $xp_HT_1;
        $xG_plusHT_2 = $xp_HT_2 < 1 ? $minmax[0][1]+$minmax[3][1] : $xp_HT_2;
        $xG_plusHT_3 = $xp_HT_3 < 1 ? $minmax[0][2]+$minmax[3][2] : $xp_HT_3;

        foreach ($home as $row) {
            if ($row->scored_HT >= $minmax[0][2]) {
                $xGscored_H1_HT++;
                $xGscored_H2_HT++;
                $xGscored_H3_HT++;
            }
            else if ($row->scored_HT >= $minmax[0][1]) {
                $xGscored_H1_HT++;
                $xGscored_H2_HT++;
            }
            else {
                $xGscored_H1_HT++;
            }
            if ($row->conced_HT >= $minmax[1][2]) {
                $xGconced_H1_HT++;
                $xGconced_H2_HT++;
                $xGconced_H3_HT++;
            }
            else if ($row->conced_HT >= $minmax[1][1]) {
                $xGconced_H1_HT++;
                $xGconced_H2_HT++;
            }
            else {
                $xGconced_H1_HT++;
            }

            if (($row->scored_HT+$row->conced_HT) >= $xG_plusHT_1) {
                $totH_1_HT++;
            }
            if (($row->scored_HT+$row->conced_HT) >= $xG_plusHT_2) {
                $totH_2_HT++;
            }
            if (($row->scored_HT+$row->conced_HT) >= $xG_plusHT_3) {
                $totH_3_HT++;
            }
        }

        $xG_plusHT_1 = $xp_HT_1 < 1 ? $minmax[2][0]+$minmax[1][0] : $xp_HT_1;
        $xG_plusHT_2 = $xp_HT_2 < 1 ? $minmax[2][1]+$minmax[1][1] : $xp_HT_2;
        $xG_plusHT_3 = $xp_HT_3 < 1 ? $minmax[2][2]+$minmax[1][2] : $xp_HT_3;

        foreach ($away as $row) {
            if ($row->scored_HT >= $minmax[2][2]) {
                $xGscored_A1_HT++;
                $xGscored_A2_HT++;
                $xGscored_A3_HT++;
            }
            else if ($row->scored_HT >= $minmax[2][1]) {
                $xGscored_A1_HT++;
                $xGscored_A2_HT++;
            }
            else {
                $xGscored_A1_HT++;
            }
            if ($row->conced_HT >= $minmax[3][2]) {
                $xGconced_A1_HT++;
                $xGconced_A2_HT++;
                $xGconced_A3_HT++;
            }
            else if ($row->conced_HT >= $minmax[3][1]) {
                $xGconced_A1_HT++;
                $xGconced_A2_HT++;
            }
            else {
                $xGconced_A1_HT++;
            }

            if (($row->scored_HT+$row->conced_HT) >= $xG_plusHT_1) {
                $totA_1_HT++;
            }
            if (($row->scored_HT+$row->conced_HT) >= $xG_plusHT_2) {
                $totA_2_HT++;
            }
            if (($row->scored_HT+$row->conced_HT) >= $xG_plusHT_3) {
                $totA_3_HT++;
            }
        }

        return array(
            array(
                array($xGscored_H1_HT*20,$xGscored_H2_HT*20,$xGscored_H3_HT*20),
                array($xGconced_H1_HT*20,$xGconced_H2_HT*20,$xGconced_H3_HT*20),
                array($xGscored_A1_HT*20,$xGscored_A2_HT*20,$xGscored_A3_HT*20),
                array($xGconced_A1_HT*20,$xGconced_A2_HT*20,$xGconced_A3_HT*20)
            ),
            array(
                array($totH_1_HT,$totH_2_HT,$totH_3_HT),
                array($totA_1_HT,$totA_2_HT,$totA_3_HT)
            )
        );
    }

    function getProcentage2($home,$away,$minmax,$xp_FT_1,$xp_FT_2,$xp_FT_3) {
        $xGscored_H1_FT = 0; $xGscored_H2_FT = 0; $xGscored_H3_FT = 0;
        $xGconced_H1_FT = 0; $xGconced_H2_FT = 0; $xGconced_H3_FT = 0;
        $xGscored_A1_FT = 0; $xGscored_A2_FT = 0; $xGscored_A3_FT = 0;
        $xGconced_A1_FT = 0; $xGconced_A2_FT = 0; $xGconced_A3_FT = 0;
        $totH_1_FT = 0; $totH_2_FT = 0; $totH_3_FT = 0;
        $totA_1_FT = 0; $totA_2_FT = 0; $totA_3_FT = 0;
        
        $xG_plusFT_1 = $xp_FT_1 < 1 ? $minmax[4][0]+$minmax[7][0] : $xp_FT_1;
        $xG_plusFT_2 = $xp_FT_2 < 1 ? $minmax[4][1]+$minmax[7][1] : $xp_FT_2;
        $xG_plusFT_3 = $xp_FT_3 < 1 ? $minmax[4][2]+$minmax[7][2] : $xp_FT_3;

        foreach ($home as $row) {
            if ($row->scored_FT >= $minmax[4][2]) {
                $xGscored_H1_FT++;
                $xGscored_H2_FT++;
                $xGscored_H3_FT++;
            }
            else if ($row->scored_FT >= $minmax[4][1]) {
                $xGscored_H1_FT++;
                $xGscored_H2_FT++;
            }
            else {
                $xGscored_H1_FT++;
            }
            if ($row->conced_FT >= $minmax[5][2]) {
                $xGconced_H1_FT++;
                $xGconced_H2_FT++;
                $xGconced_H3_FT++;
            }
            else if ($row->conced_FT >= $minmax[5][1]) {
                $xGconced_H1_FT++;
                $xGconced_H2_FT++;
            }
            else {
                $xGconced_H1_FT++;
            }

            if (($row->scored_FT+$row->conced_FT) >= $xG_plusFT_1) {
                $totH_1_FT++;
            }
            if (($row->scored_FT+$row->conced_FT) >= $xG_plusFT_2) {
                $totH_2_FT++;
            }
            if (($row->scored_FT+$row->conced_FT) >= $xG_plusFT_3) {
                $totH_3_FT++;
            }
        }

        $xG_plusFT_1 = $xp_FT_1 < 1 ? $minmax[6][0]+$minmax[5][0] : $xp_FT_1;
        $xG_plusFT_2 = $xp_FT_2 < 1 ? $minmax[6][1]+$minmax[5][1] : $xp_FT_2;
        $xG_plusFT_3 = $xp_FT_3 < 1 ? $minmax[6][2]+$minmax[5][2] : $xp_FT_3;

        foreach ($away as $row) {
            if ($row->scored_FT >= $minmax[6][2]) {
                $xGscored_A1_FT++;
                $xGscored_A2_FT++;
                $xGscored_A3_FT++;
            }
            else if ($row->scored_FT >= $minmax[6][1]) {
                $xGscored_A1_FT++;
                $xGscored_A2_FT++;
            }
            else {
                $xGscored_A1_FT++;
            }
            if ($row->conced_FT >= $minmax[7][2]) {
                $xGconced_A1_FT++;
                $xGconced_A2_FT++;
                $xGconced_A3_FT++;
            }
            else if ($row->conced_FT >= $minmax[7][1]) {
                $xGconced_A1_FT++;
                $xGconced_A2_FT++;
            }
            else {
                $xGconced_A1_FT++;
            }

            if (($row->scored_FT+$row->conced_FT) >= $xG_plusFT_1) {
                $totA_1_FT++;
            }
            if (($row->scored_FT+$row->conced_FT) >= $xG_plusFT_2) {
                $totA_2_FT++;
            }
            if (($row->scored_FT+$row->conced_FT) >= $xG_plusFT_3) {
                $totA_3_FT++;
            }
        }

        return array(
            array(
                array($xGscored_H1_FT*20,$xGscored_H2_FT*20,$xGscored_H3_FT*20),
                array($xGconced_H1_FT*20,$xGconced_H2_FT*20,$xGconced_H3_FT*20),
                array($xGscored_A1_FT*20,$xGscored_A2_FT*20,$xGscored_A3_FT*20),
                array($xGconced_A1_FT*20,$xGconced_A2_FT*20,$xGconced_A3_FT*20)
            ),
            array(
                array($totH_1_FT,$totH_2_FT,$totH_3_FT),
                array($totA_1_FT,$totA_2_FT,$totA_3_FT)
            )
        );
    }
