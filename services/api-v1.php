<?php
    date_default_timezone_set('Asia/Jakarta');
    require_once '../db/connection.php';

    $sport = 'basketball';
    $act = $_GET['act'];
    $cup = array('pokal','coupe','copa','cup','troph','coppa','shield','piala','beker','trofeo','torneo','taça','tournament','taca');
    $absError = 1;
    $nba = 0;
    $result = [ 'status' => true, 'value' => array(), 'message' => null ];

    if ($act=='country') {
        $table = 'as_countries';

        $sql = mysqli_query($conn, "SELECT * FROM $table WHERE sport='$sport' ORDER BY country_name");
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
        $sql = mysqli_query($conn, "SELECT * FROM $table WHERE sport='$sport' ORDER BY dates LIMIT 0,1");
        $row = mysqli_fetch_assoc($sql);
        $exist = date('Y-m-d', strtotime('+6 hour', strtotime($row['dates'])));
        $start = date('Y-m-d H:i:s', strtotime('+6 hour', strtotime($row['dates'])));
        $sql = mysqli_query($conn, "SELECT * FROM $table WHERE sport='$sport' ORDER BY dates DESC LIMIT 0,1");
        $row = mysqli_fetch_assoc($sql);
        $end = date('Y-m-d H:i:s', strtotime('+6 hour', strtotime($row['dates'])));
        if (
            ($from == $exist) ||
            ($start <= $now && $end >= $now)
        ) {
            $newfixtures = false;
        }

        $table = 'as_leagues';
        $sql = mysqli_query($conn, "SELECT * FROM $table WHERE sport='$sport' ORDER BY country,league_key");
        $rows = [];
        while($row = mysqli_fetch_assoc($sql)) {
            $rows[] = $row;
        }
        
        $table = 'as_fixtures';
        if ($newfixtures) {
            mysqli_query($conn, "DELETE FROM $table WHERE sport='$sport'");
            foreach ($rows as $row) {
                $league = $row['league_key'];

                $options = array(
                    CURLOPT_URL => "https://apiv2.allsportsapi.com/$sport/?met=Fixtures&leagueId=$league&from=$from&to=$to&APIkey=$key",
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
                        mysqli_query($conn, "INSERT INTO $table(dates,home,away,nhome,naway,league,country,sport) VALUES('".date('Y-m-d H:i:s', strtotime($rowf->event_date.' '.$rowf->event_time.':00'))."',$rowf->home_team_key,$rowf->away_team_key,'$rowf->event_home_team','$rowf->event_away_team',$league,".$row['country'].",'$sport')");
                    }
                }            
            }
        }
        
        $t1 = 'as_fixtures';
        $t2 = 'as_countries';
        $t3 = 'as_leagues';

        $sql = mysqli_query($conn, "SELECT t2.country_key,t2.country_name,t2.country_flag,t3.league_key,t3.league_name,t1.dates,t1.home,t1.away,t1.nhome,t1.naway FROM $t1 t1 LEFT JOIN $t2 t2 ON t2.country_key=t1.country LEFT JOIN $t3 t3 ON t3.league_key=t1.league WHERE t1.sport='$sport' ORDER BY t1.dates,t1.country,t1.league");
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

        $sql = mysqli_query($conn, "SELECT * FROM $table WHERE country=$country AND sport='$sport' ORDER BY league_key");
        $rows = [];
        while($row = mysqli_fetch_assoc($sql)) {
            $rows[] = $row;
        }

        $result = [
            'status' => true,
            'value' => $rows,
            'message' => null
        ];

        echo json_encode($result);
    }
    else if ($act=='standing') {
        $key = $_GET['key'];
        $league = $_GET['league'];
        $nba = (int)$_GET['nba'];
        $table = 'as_standings';

        $sql = mysqli_query($conn, "SELECT * FROM as_leagues WHERE league_key=$league AND sport='$sport'");
        $row = mysqli_fetch_assoc($sql);
        $session = (int)$row['session'] + 1;

        if ($row['updated_at'] == null) {
            $options = array(
                CURLOPT_URL => "https://apiv2.allsportsapi.com/$sport/?met=Standings&leagueId=$league&APIkey=$key",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => false,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CONNECTTIMEOUT => 5
            );
            $curl = curl_init();
            curl_setopt_array($curl, $options);
            $raws = json_decode(curl_exec($curl));

            foreach ($raws->result->total as $row) {
                $sql = mysqli_query($conn, "SELECT * FROM $table WHERE team_key=$row->team_key AND sport='$sport'");
                if (mysqli_num_rows($sql) < 1) {
                    mysqli_query($conn, "INSERT INTO $table(standing_place,team_key,standing_team,standing_P,league,sport) VALUES($row->standing_place,$row->team_key,'".str_replace("'","`",$row->standing_team)."',$row->standing_P,$league,'$sport')");
                }
            }
            mysqli_query($conn, "UPDATE as_leagues SET season=1 WHERE league_key=$league AND sport='$sport'");
        }
        else {
            $old = date('Y-m-d H:i:s', strtotime($row['updated_at']));
            $new = date('Y-m-d H:i:s', strtotime('-2 day'));
            $sql = mysqli_query($conn, "SELECT * FROM $table WHERE league=$league AND sport='$sport'");

            if ($old < $new || mysqli_num_rows($sql) < 1) {
                mysqli_query($conn, "DELETE FROM $table WHERE league=$league AND sport='$sport'");

                $options = array(
                    CURLOPT_URL => "https://apiv2.allsportsapi.com/$sport/?met=Standings&leagueId=$league&APIkey=$key",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HEADER => false,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CONNECTTIMEOUT => 5
                );
                $curl = curl_init();
                curl_setopt_array($curl, $options);
                $raws = json_decode(curl_exec($curl));

                foreach ($raws->result->total as $row) {
                    $sql = mysqli_query($conn, "SELECT * FROM $table WHERE team_key=$row->team_key AND sport='$sport'");
                    if (mysqli_num_rows($sql) < 1) {
                        mysqli_query($conn, "INSERT INTO $table(standing_place,team_key,standing_team,standing_P,league,sport) VALUES($row->standing_place,$row->team_key,'".str_replace("'","`",$row->standing_team)."',$row->standing_P,$league,'$sport')");
                    }
                }
                mysqli_query($conn, "UPDATE as_leagues SET season=$session WHERE league_key=$league AND sport='$sport'");
            }
        }

        $sql = mysqli_query($conn, "SELECT * FROM $table WHERE league=$league AND sport='$sport' ORDER BY standing_place");
        $rows = [];
        while($row = mysqli_fetch_assoc($sql)) {
            $rows[] = $row;
        }

        $result = [
            'status' => true,
            'value' => $rows,
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

        mysqli_query($conn, "DELETE FROM $table WHERE sport='$sport'");

        $options = array(
            CURLOPT_URL => "https://apiv2.allsportsapi.com/$sport/?met=Fixtures&leagueId=$league&teamId=$team[0]&from=$from&to=$to&APIkey=$key",
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
                if ((trim($row->event_status) == 'Finished' || trim($row->event_status) == 'After Over Time') && $p < 5) {
                    foreach ($row->scores as $index => $rowg) {
                        mysqli_query($conn, "INSERT INTO $table(dates,home,away,quart,hscore,ascore,sport) VALUES('$row->event_date',$row->home_team_key,$row->away_team_key,".($index+1).",".$rowg[0]->score_home.",".$rowg[0]->score_away.",'$sport')");
                    }
                    $p++;
                }
            }
        
            $options = array(
                CURLOPT_URL => "https://apiv2.allsportsapi.com/$sport/?met=Fixtures&leagueId=$league&teamId=$team[1]&from=$from&to=$to&APIkey=$key",
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
                $live = $raws->result[0];

                $p = 0;
                foreach ($raws->result as $row) {
                    if ((trim($row->event_status) == 'Finished' || trim($row->event_status) == 'After Over Time') && $p < 5) {
                        foreach ($row->scores as $index => $rowg) {
                            mysqli_query($conn, "INSERT INTO $table(dates,home,away,quart,hscore,ascore,sport) VALUES('$row->event_date',$row->home_team_key,$row->away_team_key,".($index+1).",".$rowg[0]->score_home.",".$rowg[0]->score_away.",'$sport')");
                        }
                        $p++;
                    }
                }

                $raws = [];
                $sql = mysqli_query($conn, "SELECT * FROM $table WHERE home=$team[0] AND sport='$sport' OR away=$team[0] AND sport='$sport' GROUP BY dates ORDER BY dates DESC LIMIT 0,5");
                while($row = mysqli_fetch_assoc($sql)) {
                    $raws[] = $row;
                }

                $home = array();
                foreach ($raws as $index => $raw) {
                    $rows = [];
                    $sql = mysqli_query($conn, "SELECT * FROM $table WHERE home=$team[0] AND dates LIKE '".$raw['dates']."' AND sport='$sport' OR away=$team[0] AND dates LIKE '".$raw['dates']."' AND sport='$sport' ORDER BY quart");
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
                    $sql = mysqli_query($conn, "SELECT * FROM $table WHERE home=$team[1] AND sport='$sport' OR away=$team[1] AND sport='$sport' GROUP BY dates ORDER BY dates DESC LIMIT 0,5");
                    while($row = mysqli_fetch_assoc($sql)) {
                        $raws[] = $row;
                    }

                    $away = array();
                    foreach ($raws as $index => $raw) {
                        $rows = [];
                        $sql = mysqli_query($conn, "SELECT * FROM $table WHERE home=$team[1] AND dates LIKE '".$raw['dates']."' AND sport='$sport' OR away=$team[1] AND dates LIKE '".$raw['dates']."' AND sport='$sport' ORDER BY quart");
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
                        $part_H = []; $part_A = []; $score_HT_Q = 0; $score_FT_Q = 0; $score_live = [0,0];
                        if ((int)$live->event_live > 0) {
                            // $start = new DateTime(date('Y-m-d H:i', strtotime('+6 hour',strtotime($live->event_date.' '.$live->event_time))));
                            // $finish = new DateTime(date('Y-m-d H:i'));
                            // $minute = ($start->diff($finish)->h*60)+$start->diff($finish)->i;
                            foreach ($live->scores as $index => $rowg) {
                                if ($rowg[0] != null) {
                                    if ($index < 2) {
                                        $score_HT_Q = (int)$rowg[0]->score_home+(int)$rowg[0]->score_away;
                                    }
                                    else if ($index < 3) {
                                        $score_FT_Q = $score_HT_Q+(int)$rowg[0]->score_home+(int)$rowg[0]->score_away;
                                    }
                                    array_push($part_H,(int)$rowg[0]->score_home);
                                    array_push($part_A,(int)$rowg[0]->score_away);
                                    $score_live[0] = $score_live[0]+(int)$rowg[0]->score_home;
                                    $score_live[1] = $score_live[1]+(int)$rowg[0]->score_away;
                                }
                            }
                        }

                        $getMinMax = getMinMax($home,$away,$score_HT_Q,$score_FT_Q,$score_live,$part_H,$part_A,$nba);
                        $getCriteria1 = count($getMinMax) > 0 ? getCriteria1($home,$away,$getMinMax,$absError) : [];
                        $getCriteria2 = count($getCriteria1) > 0 ? getCriteria2($home,$away,$getMinMax[0][3]+$absError,$getMinMax[1][3]+$absError) : [];
                        $getCriteria3 = count($getCriteria2) > 0 ? getCriteria3($home,$away,$getMinMax[0][3]+$absError,$getMinMax[1][3]+$absError,$getCriteria2,$absError,$getMinMax) : [];

                        $method2 = array(
                            $getCriteria1,
                            $getMinMax,
                            $getCriteria2,
                            $getCriteria3
                        );

                        $result = [
                            'status' => true,
                            'value' => array(
                                $home,$away,$method2
                            ),
                            'message' => null
                        ];
                    }
                }
            }
        }

        echo json_encode($result);
    }

    function getMinMax($home,$away,$score_HT,$score_FT,$score_live,$part_H,$part_A,$nba) {
        $arrH = $home;
        $minscoredH_HT_1 = 0;
        $minconcedH_HT_1 = 0;
        $minscoredH_FT_1 = 0;
        $minconcedH_FT_1 = 0;
        usort($arrH, function($a, $b) {
            return $a->scored_HT <=> $b->scored_HT;
        });
        foreach ($arrH as $row) {
            if ($minscoredH_HT_1 == 0) {
                $minscoredH_HT_1 = $row->scored_HT;
                break;
            }
        }
        usort($arrH, function($a, $b) {
            return $a->conced_HT <=> $b->conced_HT;
        });
        foreach ($arrH as $row) {
            if ($minconcedH_HT_1 == 0) {
                $minconcedH_HT_1 = $row->conced_HT;
                break;
            }
        }
        usort($arrH, function($a, $b) {
            return $a->scored_FT <=> $b->scored_FT;
        });
        foreach ($arrH as $row) {
            if ($minscoredH_FT_1 == 0) {
                $minscoredH_FT_1 = $row->scored_FT;
                break;
            }
        }
        usort($arrH, function($a, $b) {
            return $a->conced_FT <=> $b->conced_FT;
        });
        foreach ($arrH as $row) {
            if ($minconcedH_FT_1 == 0) {
                $minconcedH_FT_1 = $row->conced_FT;
                break;
            }
        }

        $arrA = $away;
        $minscoredA_HT_1 = 0;
        $minconcedA_HT_1 = 0;
        $minscoredA_FT_1 = 0;
        $minconcedA_FT_1 = 0;
        usort($arrA, function($a, $b) {
            return $a->scored_HT <=> $b->scored_HT;
        });
        foreach ($arrA as $row) {
            if ($minscoredA_HT_1 == 0) {
                $minscoredA_HT_1 = $row->scored_HT;
                break;
            }
        }
        usort($arrA, function($a, $b) {
            return $a->conced_HT <=> $b->conced_HT;
        });
        foreach ($arrA as $row) {
            if ($minconcedA_HT_1 == 0) {
                $minconcedA_HT_1 = $row->conced_HT;
                break;
            }
        }
        usort($arrA, function($a, $b) {
            return $a->scored_FT <=> $b->scored_FT;
        });
        foreach ($arrA as $row) {
            if ($minscoredA_FT_1 == 0) {
                $minscoredA_FT_1 = $row->scored_FT;
                break;
            }
        }
        usort($arrA, function($a, $b) {
            return $a->conced_FT <=> $b->conced_FT;
        });
        foreach ($arrA as $row) {
            if ($minconcedA_FT_1 == 0) {
                $minconcedA_FT_1 = $row->conced_FT;
                break;
            }
        }

        $totH_HT = 0; $totA_HT = 0;
        if ($home[4]->scored_HT >= $home[3]->scored_HT && $away[4]->conced_HT > $away[3]->conced_HT) {
            $totH_HT = $minscoredH_HT_1 + $minconcedA_HT_1;
        }
        if ($away[4]->scored_HT >= $away[3]->scored_HT && $home[4]->conced_HT > $home[3]->conced_HT) {
            $totA_HT = $minscoredA_HT_1 + $minconcedH_HT_1;
        }

        $totH_FT = 0; $totA_FT = 0;
        if ($home[4]->scored_FT >= $home[3]->scored_FT && $away[4]->conced_FT > $away[3]->conced_FT) {
            $totH_FT = $minscoredH_FT_1 + $minconcedA_FT_1;
        }
        if ($away[4]->scored_FT >= $away[3]->scored_FT && $home[4]->conced_FT > $home[3]->conced_FT) {
            $totA_FT = $minscoredA_FT_1 + $minconcedH_FT_1;
        }

        $min_tot_HT = $totH_HT >= $totA_HT ? $totH_HT : $totA_HT;
        $min_tot_FT = $totH_FT >= $totA_FT ? $totH_FT : $totA_FT;

        $per_min_tot_HT = $min_tot_HT / 100;
        $per_min_tot_FT = $min_tot_FT / 100;

        $tot_HT = []; $tot_FT = [];
        $u_tot_HT = []; $u_tot_FT = [];

        for ($x=3;$x>=0;$x--) {
            array_push($tot_HT,(int)number_format($min_tot_HT + ((20-(5 * $x)) * $per_min_tot_HT)));
            array_push($tot_FT,(int)number_format($min_tot_FT + ((20-(5 * $x)) * $per_min_tot_FT)));
        }
        
        // this NBA
        if ($tot_FT[3] > 200 & $nba < 1) {
            $tot_HT = [0,0,0,0]; $tot_FT = [0,0,0,0];
        }

        for ($x=0;$x<=3;$x++) {
            array_push($u_tot_HT,(int)($tot_HT[$x] / 2));
            array_push($u_tot_FT,(int)($tot_FT[$x] / 4));
        }

        $more_HT = 0; $more_FT = 0;
        /*
        if (($tot_HT[5] / 2) < $score_HT) {
            $more_HT = (int)number_format($score_HT - ($tot_HT[5] / 2),0);
            $min_tot_HT = $min_tot_HT + ($score_HT - ($tot_HT[5] / 2));
            $per_min_tot_HT = $min_tot_HT / 100;

            $p_tot_HT = (int)number_format($min_tot_HT + (20 * $per_min_tot_HT));

            $tot_HT = [];
            for ($x=5;$x>=0;$x--) {
                array_push($tot_HT,($p_tot_HT-$x)-$absError);
            }
        }

        if (($tot_FT[5] / 2) < $score_FT) {
            $more_FT = (int)number_format($score_FT - ($tot_FT[5] / 2),0);
            $min_tot_FT = $min_tot_FT + ($score_FT - ($tot_FT[5] / 2));
            $per_min_tot_FT = $min_tot_FT / 100;

            $p_tot_FT = (int)number_format($min_tot_FT + (20 * $per_min_tot_FT));

            $tot_FT = [];
            for ($x=5;$x>=0;$x--) {
                array_push($tot_FT,($p_tot_FT-$x)-$absError);
            }
        }
        */

        $x_H_HT = []; $x_A_HT = []; $x_H_FT = []; $x_A_FT = [];
        
        $part_tot_HT = []; $part_tot_FT = [];
        for ($x=0;$x<=3;$x++) {
            array_push($x_H_HT,0); array_push($x_A_HT,0);
            array_push($x_H_FT,0); array_push($x_A_FT,0);

            $p_t_HT = fmod(($tot_HT[$x]-$score_HT)/2, 1) > 0 ? ((($tot_HT[$x]-$score_HT)/2)-fmod(($tot_HT[$x]-$score_HT)/2, 1))+1 : ($tot_HT[$x]-$score_HT)/2;
            $p_t_FT = fmod(($tot_FT[$x]-$score_FT)/4, 1) > 0 ? ((($tot_FT[$x]-$score_FT)/4)-fmod(($tot_FT[$x]-$score_FT)/4, 1))+1 : ($tot_FT[$x]-$score_FT)/4;

            array_push($part_tot_HT,$p_t_HT);
            array_push($part_tot_FT,$p_t_FT);
        }

        $part_Q = ['?','?','?','?'];

        foreach ($part_H as $index => $row) {
            for ($x=0;$x<=3;$x++) {
                if ($row >= $part_tot_HT[$x]) {
                    $x_H_HT[$x] = $x_H_HT[$x]+1;
                }
                if ($row >= $part_tot_FT[$x]) {
                    $x_H_FT[$x] = $x_H_FT[$x]+1;
                }
            }

            $part_Q[$index] = $row+$part_A[$index];
        }

        foreach ($part_A as $row) {
            for ($x=0;$x<=3;$x++) {
                if ($row >= $part_tot_HT[$x]) {
                    $x_A_HT[$x] = $x_A_HT[$x]+1;
                }
                if ($row >= $part_tot_FT[$x]) {
                    $x_A_FT[$x] = $x_A_FT[$x]+1;
                }
            }
        }

        return array(
            $tot_HT,$tot_FT,
            array($more_HT,$more_FT,$score_live),
            $part_tot_HT,$part_tot_FT,
            array($x_H_HT,$x_A_HT),
            array($x_H_FT,$x_A_FT),
            array($u_tot_HT,$u_tot_FT),
            $part_Q
        );
    }

    function getCriteria1($home,$away,$minmax,$absError) {
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
            if ($last2_H[0]->conced_HT == $last1_H[0]->conced_HT && $last2_H[0]->conced_HT > ($minmax[1][0]+$absError) && $last1_H[0]->conced_HT > ($minmax[1][0]+$absError)) {
                $concedH_HT = true;
            }
        }
        if ($last2_H[0]->conced_FT <= $last1_H[0]->conced_FT) {
            $concedH_FT = false;
            if ($last2_H[0]->conced_FT == $last1_H[0]->conced_FT && $last2_H[0]->conced_FT > ($minmax[5][0]+$absError) && $last1_H[0]->conced_FT > ($minmax[5][0]+$absError)) {
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
            if ($last2_A[0]->conced_HT == $last1_A[0]->conced_HT && $last2_A[0]->conced_HT > ($minmax[3][0]+$absError) && $last1_A[0]->conced_HT > ($minmax[3][0]+$absError)) {
                $concedA_HT = true;
            }
        }
        if ($last2_A[0]->conced_FT <= $last1_A[0]->conced_FT) {
            $concedA_FT = false;
            if ($last2_A[0]->conced_FT == $last1_A[0]->conced_FT && $last2_A[0]->conced_FT > ($minmax[7][0]+$absError) && $last1_A[0]->conced_FT > ($minmax[7][0]+$absError)) {
                $concedA_FT = true;
            }
        }

        return array(
            array($scoredH_HT,$concedH_HT,$scoredA_HT,$concedA_HT),
            array($scoredH_FT,$concedH_FT,$scoredA_FT,$concedA_FT)
        );
    }

    function getCriteria2($home,$away,$min_score_HT,$min_score_FT) {
        $arr_P_H_HT = []; $arr_P_A_HT = [];
        $arr_P_H_FT = []; $arr_P_A_FT = [];

        foreach ($home as $index => $row) {
            array_push($arr_P_H_HT, $row->scored_HT+$away[$index]->conced_HT);
            array_push($arr_P_H_FT, $row->scored_FT+$away[$index]->conced_FT);
        }

        foreach ($away as $index => $row) {
            array_push($arr_P_A_HT, $row->scored_HT+$home[$index]->conced_HT);
            array_push($arr_P_A_FT, $row->scored_FT+$home[$index]->conced_FT);            
        }

        $err_H_HT = 0; $err_H_FT = 0;
        foreach ($arr_P_H_HT as $index => $row) {
            if ($index >= 2) {
                $tot = (int)number_format(($arr_P_H_HT[$index-2]+$arr_P_H_HT[$index-1]) / 2,0);
                $err_H_HT = $err_H_HT + (abs($arr_P_H_HT[$index] - $tot) / $arr_P_H_HT[$index]);
                $tot = (int)number_format(($arr_P_H_FT[$index-2]+$arr_P_H_FT[$index-1]) / 2,0);
                $err_H_FT = $err_H_FT + (abs($arr_P_H_FT[$index] - $tot) / $arr_P_H_FT[$index]);
            }
        }
        $sma_H_HT = (int)number_format(($arr_P_H_HT[3]+$arr_P_H_HT[4]) / 2,0);
        $err_H_HT = number_format($err_H_HT / 3,2);
        $sma_H_FT = (int)number_format(($arr_P_H_FT[3]+$arr_P_H_FT[4]) / 2,0);
        $err_H_FT = number_format($err_H_FT / 3,2);

        $err_A_HT = 0; $err_A_FT = 0;
        foreach ($arr_P_A_HT as $index => $row) {
            if ($index >= 2) {
                $tot = (int)number_format(($arr_P_A_HT[$index-2]+$arr_P_A_HT[$index-1]) / 2,0);
                $err_A_HT = $err_A_HT + (abs($arr_P_A_HT[$index] - $tot) / $arr_P_A_HT[$index]);
                $tot = (int)number_format(($arr_P_A_FT[$index-2]+$arr_P_A_FT[$index-1]) / 2,0);
                $err_A_FT = $err_A_FT + (abs($arr_P_A_FT[$index] - $tot) / $arr_P_A_FT[$index]);
            }
        }
        $sma_A_HT = (int)number_format(($arr_P_A_HT[3]+$arr_P_A_HT[4]) / 2,0);
        $err_A_HT = number_format($err_A_HT / 3,2);
        $sma_A_FT = (int)number_format(($arr_P_A_FT[3]+$arr_P_A_FT[4]) / 2,0);
        $err_A_FT = number_format($err_A_FT / 3,2);

        $per_P_H_HT = 0; $per_M_H_HT = 0;
        $per_P_H_FT = 0; $per_M_H_FT = 0;
        foreach ($arr_P_H_HT as $index => $row) {
            if ($arr_P_H_HT[$index] > $sma_H_HT) {
                $per_P_H_HT++;
            }
            if ($arr_P_H_HT[$index] > $min_score_HT) {
                $per_M_H_HT++;
            }
            if ($arr_P_H_FT[$index] > $sma_H_FT) {
                $per_P_H_FT++;
            }
            if ($arr_P_H_FT[$index] > $min_score_FT) {
                $per_M_H_FT++;
            }
        }

        $per_P_A_HT = 0; $per_M_A_HT = 0;
        $per_P_A_FT = 0; $per_M_A_FT = 0;
        foreach ($arr_P_A_HT as $index => $row) {
            if ($arr_P_A_HT[$index] > $sma_A_HT) {
                $per_P_A_HT++;
            }
            if ($arr_P_A_HT[$index] > $min_score_HT) {
                $per_M_A_HT++;
            }
            if ($arr_P_A_FT[$index] > $sma_A_FT) {
                $per_P_A_FT++;
            }
            if ($arr_P_A_FT[$index] > $min_score_FT) {
                $per_M_A_FT++;
            }
        }

        return array(
            array($sma_H_HT,$err_H_HT,$per_P_H_HT*20,$per_M_H_HT*20),
            array($sma_A_HT,$err_A_HT,$per_P_A_HT*20,$per_M_A_HT*20),
            array($sma_H_FT,$err_H_FT,$per_P_H_FT*20,$per_M_H_FT*20),
            array($sma_A_FT,$err_A_FT,$per_P_A_FT*20,$per_M_A_FT*20)
        );
    }

    function getCriteria3($home,$away,$min_score_HT,$min_score_FT,$criteria2,$absError,$minmax) {
        $arr_P_H_HT = []; $arr_P_A_HT = [];
        $arr_P_H_FT = []; $arr_P_A_FT = [];

        foreach ($home as $index => $row) {
            array_push($arr_P_H_HT, $row->scored_HT+$row->conced_HT);
            array_push($arr_P_H_FT, $row->scored_FT+$row->conced_FT);
        }

        foreach ($away as $index => $row) {
            array_push($arr_P_A_HT, $row->scored_HT+$row->conced_HT);
            array_push($arr_P_A_FT, $row->scored_FT+$row->conced_FT);            
        }

        $err_H_HT = 0; $err_H_FT = 0;
        foreach ($arr_P_H_HT as $index => $row) {
            if ($index >= 2) {
                $tot = (int)number_format(($arr_P_H_HT[$index-2]+$arr_P_H_HT[$index-1]) / 2,0);
                $err_H_HT = $err_H_HT + (abs($arr_P_H_HT[$index] - $tot) / $arr_P_H_HT[$index]);
                $tot = (int)number_format(($arr_P_H_FT[$index-2]+$arr_P_H_FT[$index-1]) / 2,0);
                $err_H_FT = $err_H_FT + (abs($arr_P_H_FT[$index] - $tot) / $arr_P_H_FT[$index]);
            }
        }
        $sma_H_HT = (int)number_format(($arr_P_H_HT[3]+$arr_P_H_HT[4]) / 2,0);
        $err_H_HT = number_format($err_H_HT / 3,2);
        $sma_H_FT = (int)number_format(($arr_P_H_FT[3]+$arr_P_H_FT[4]) / 2,0);
        $err_H_FT = number_format($err_H_FT / 3,2);

        $err_A_HT = 0; $err_A_FT = 0;
        foreach ($arr_P_A_HT as $index => $row) {
            if ($index >= 2) {
                $tot = (int)number_format(($arr_P_A_HT[$index-2]+$arr_P_A_HT[$index-1]) / 2,0);
                $err_A_HT = $err_A_HT + (abs($arr_P_A_HT[$index] - $tot) / $arr_P_A_HT[$index]);
                $tot = (int)number_format(($arr_P_A_FT[$index-2]+$arr_P_A_FT[$index-1]) / 2,0);
                $err_A_FT = $err_A_FT + (abs($arr_P_A_FT[$index] - $tot) / $arr_P_A_FT[$index]);
            }
        }
        $sma_A_HT = (int)number_format(($arr_P_A_HT[3]+$arr_P_A_HT[4]) / 2,0);
        $err_A_HT = number_format($err_A_HT / 3,2);
        $sma_A_FT = (int)number_format(($arr_P_A_FT[3]+$arr_P_A_FT[4]) / 2,0);
        $err_A_FT = number_format($err_A_FT / 3,2);

        $sma_H_HT = (float)$criteria2[0][1] <= $err_H_HT ? $criteria2[0][0] : $sma_H_HT;
        $sma_A_HT = (float)$criteria2[1][1] <= $err_A_HT ? $criteria2[1][0] : $sma_A_HT;        
        $sma_H_FT = (float)$criteria2[2][1] <= $err_H_FT ? $criteria2[2][0] : $sma_H_FT;
        $sma_A_FT = (float)$criteria2[3][1] <= $err_A_FT ? $criteria2[3][0] : $sma_A_FT;

        $per_H_P_HT_min = 0; $per_H_H_HT_min = 0;
        $per_A_P_HT_min = 0; $per_A_H_HT_min = 0;
        $per_H_P_FT_min = 0; $per_H_H_FT_min = 0;
        $per_A_P_FT_min = 0; $per_A_H_FT_min = 0;

        $pow_H5_HT = 0; $his_H5_HT = 0;
        $pow_H10_HT = 0; $his_H10_HT = 0;
        $pow_H5_FT = 0; $his_H5_FT = 0;
        $pow_H10_FT = 0; $his_H10_FT = 0;
        foreach ($home as $index => $row) {
            if (($row->scored_HT+$away[$index]->conced_HT) >= $min_score_HT) {
                $per_H_P_HT_min++;
            }
            if (($row->scored_HT+$row->conced_HT) >= $min_score_HT) {
                $per_H_H_HT_min++;
            }
            if (($row->scored_FT+$away[$index]->conced_FT) >= $min_score_FT) {
                $per_H_P_FT_min++;
            }
            if (($row->scored_FT+$row->conced_FT) >= $min_score_FT) {
                $per_H_H_FT_min++;
            }

            if (($row->scored_HT+$away[$index]->conced_HT) >= ($min_score_HT+5)) {
                $pow_H5_HT++;
            }
            if (($row->scored_HT+$row->conced_HT) >= ($min_score_HT+5)) {
                $his_H5_HT++;
            }
            if (($row->scored_HT+$away[$index]->conced_HT) >= ($min_score_HT+10)) {
                $pow_H10_HT++;
            }
            if (($row->scored_HT+$row->conced_HT) >= ($min_score_HT+10)) {
                $his_H10_HT++;
            }

            if (($row->scored_FT+$away[$index]->conced_FT) >= ($min_score_FT+5)) {
                $pow_H5_FT++;
            }
            if (($row->scored_FT+$row->conced_FT) >= ($min_score_FT+5)) {
                $his_H5_FT++;
            }
            if (($row->scored_FT+$away[$index]->conced_FT) >= ($min_score_FT+10)) {
                $pow_H10_FT++;
            }
            if (($row->scored_FT+$row->conced_FT) >= ($min_score_FT+10)) {
                $his_H10_FT++;
            }
        }

        $pow_A5_HT = 0; $his_A5_HT = 0;
        $pow_A10_HT = 0; $his_A10_HT = 0;
        $pow_A5_FT = 0; $his_A5_FT = 0;
        $pow_A10_FT = 0; $his_A10_FT = 0;
        foreach ($away as $index => $row) {
            if (($row->scored_HT+$home[$index]->conced_HT) >= $min_score_HT) {
                $per_A_P_HT_min++;
            }
            if (($row->scored_HT+$row->conced_HT) >= $min_score_HT) {
                $per_A_H_HT_min++;
            }
            if (($row->scored_FT+$home[$index]->conced_FT) >= $min_score_FT) {
                $per_A_P_FT_min++;
            }
            if (($row->scored_FT+$row->conced_FT) >= $min_score_FT) {
                $per_A_H_FT_min++;
            }

            if (($row->scored_HT+$home[$index]->conced_HT) >= ($min_score_HT+5)) {
                $pow_A5_HT++;
            }
            if (($row->scored_HT+$row->conced_HT) >= ($min_score_HT+5)) {
                $his_A5_HT++;
            }
            if (($row->scored_HT+$home[$index]->conced_HT) >= ($min_score_HT+10)) {
                $pow_A10_HT++;
            }
            if (($row->scored_HT+$row->conced_HT) >= ($min_score_HT+10)) {
                $his_A10_HT++;
            }

            if (($row->scored_FT+$home[$index]->conced_FT) >= ($min_score_FT+5)) {
                $pow_A5_FT++;
            }
            if (($row->scored_FT+$row->conced_FT) >= ($min_score_FT+5)) {
                $his_A5_FT++;
            }
            if (($row->scored_FT+$home[$index]->conced_FT) >= ($min_score_FT+10)) {
                $pow_A10_FT++;
            }
            if (($row->scored_FT+$row->conced_FT) >= ($min_score_FT+10)) {
                $his_A10_FT++;
            }
        }

        $H_HT95 = 95; $H_HT90 = 90; $H_HT85 = 85; $H_HT80 = 80;
        $A_HT95 = 95; $A_HT90 = 90; $A_HT85 = 85; $A_HT80 = 80;

            $H_P_HT95 = 0; $H_H_HT95 = 0;
            $H_P_HT90 = 0; $H_H_HT90 = 0;
            $H_P_HT85 = 0; $H_H_HT85 = 0;
            $H_P_HT80 = 0; $H_H_HT80 = 0;
            foreach ($home as $index => $row) {
                if (($row->scored_HT+$away[$index]->conced_HT) >= $minmax[0][0]) {
                    $H_P_HT95++;
                }
                if (($row->scored_HT+$row->conced_HT) >= $minmax[0][0]) {
                    $H_H_HT95++;
                }

                if (($row->scored_HT+$away[$index]->conced_HT) >= $minmax[0][1]) {
                    $H_P_HT90++;
                }
                if (($row->scored_HT+$row->conced_HT) >= $minmax[0][1]) {
                    $H_H_HT90++;
                }

                if (($row->scored_HT+$away[$index]->conced_HT) >= $minmax[0][2]) {
                    $H_P_HT85++;
                }
                if (($row->scored_HT+$row->conced_HT) >= $minmax[0][2]) {
                    $H_H_HT85++;
                }

                if (($row->scored_HT+$away[$index]->conced_HT) >= $minmax[0][3]) {
                    $H_P_HT80++;
                }
                if (($row->scored_HT+$row->conced_HT) >= $minmax[0][3]) {
                    $H_H_HT80++;
                }
            }
            $H_HT95 = ($H_P_HT95*20).'/'.($H_H_HT95*20);
            $H_HT90 = ($H_P_HT90*20).'/'.($H_H_HT90*20);
            $H_HT85 = ($H_P_HT85*20).'/'.($H_H_HT85*20);
            $H_HT80 = ($H_P_HT80*20).'/'.($H_H_HT80*20);

            $A_P_HT95 = 0; $A_H_HT95 = 0;
            $A_P_HT90 = 0; $A_H_HT90 = 0;
            $A_P_HT85 = 0; $A_H_HT85 = 0;
            $A_P_HT80 = 0; $A_H_HT80 = 0;
            foreach ($away as $index => $row) {
                if (($row->scored_HT+$home[$index]->conced_HT) >= $minmax[0][0]) {
                    $A_P_HT95++;
                }
                if (($row->scored_HT+$row->conced_HT) >= $minmax[0][0]) {
                    $A_H_HT95++;
                }

                if (($row->scored_HT+$home[$index]->conced_HT) >= $minmax[0][1]) {
                    $A_P_HT90++;
                }
                if (($row->scored_HT+$row->conced_HT) >= $minmax[0][1]) {
                    $A_H_HT90++;
                }

                if (($row->scored_HT+$home[$index]->conced_HT) >= $minmax[0][2]) {
                    $A_P_HT85++;
                }
                if (($row->scored_HT+$row->conced_HT) >= $minmax[0][2]) {
                    $A_H_HT85++;
                }

                if (($row->scored_HT+$home[$index]->conced_HT) >= $minmax[0][3]) {
                    $A_P_HT80++;
                }
                if (($row->scored_HT+$row->conced_HT) >= $minmax[0][3]) {
                    $A_H_HT80++;
                }
            }
            $A_HT95 = ($A_P_HT95*20).'/'.($A_H_HT95*20);
            $A_HT90 = ($A_P_HT90*20).'/'.($A_H_HT90*20);
            $A_HT85 = ($A_P_HT85*20).'/'.($A_H_HT85*20);
            $A_HT80 = ($A_P_HT80*20).'/'.($A_H_HT80*20);

        $H_FT95 = 95; $H_FT90 = 90; $H_FT85 = 85; $H_FT80 = 80;
        $A_FT95 = 95; $A_FT90 = 90; $A_FT85 = 85; $A_FT80 = 80;

            $H_P_FT95 = 0; $H_H_FT95 = 0;
            $H_P_FT90 = 0; $H_H_FT90 = 0;
            $H_P_FT85 = 0; $H_H_FT85 = 0;
            $H_P_FT80 = 0; $H_H_FT80 = 0;
            foreach ($home as $index => $row) {
                if (($row->scored_FT+$away[$index]->conced_FT) >= $minmax[1][0]) {
                    $H_P_FT95++;
                }
                if (($row->scored_FT+$row->conced_FT) >= $minmax[1][0]) {
                    $H_H_FT95++;
                }

                if (($row->scored_FT+$away[$index]->conced_FT) >= $minmax[1][1]) {
                    $H_P_FT90++;
                }
                if (($row->scored_FT+$row->conced_FT) >= $minmax[1][1]) {
                    $H_H_FT90++;
                }

                if (($row->scored_FT+$away[$index]->conced_FT) >= $minmax[1][2]) {
                    $H_P_FT85++;
                }
                if (($row->scored_FT+$row->conced_FT) >= $minmax[1][2]) {
                    $H_H_FT85++;
                }

                if (($row->scored_FT+$away[$index]->conced_FT) >= $minmax[1][3]) {
                    $H_P_FT80++;
                }
                if (($row->scored_FT+$row->conced_FT) >= $minmax[1][3]) {
                    $H_H_FT80++;
                }
            }
            $H_FT95 = ($H_P_FT95*20).'/'.($H_H_FT95*20);
            $H_FT90 = ($H_P_FT90*20).'/'.($H_H_FT90*20);
            $H_FT85 = ($H_P_FT85*20).'/'.($H_H_FT85*20);
            $H_FT80 = ($H_P_FT80*20).'/'.($H_H_FT80*20);

            $A_P_FT95 = 0; $A_H_FT95 = 0;
            $A_P_FT90 = 0; $A_H_FT90 = 0;
            $A_P_FT85 = 0; $A_H_FT85 = 0;
            $A_P_FT80 = 0; $A_H_FT80 = 0;
            foreach ($away as $index => $row) {
                if (($row->scored_FT+$home[$index]->conced_FT) >= $minmax[1][0]) {
                    $A_P_FT95++;
                }
                if (($row->scored_FT+$row->conced_FT) >= $minmax[1][0]) {
                    $A_H_FT95++;
                }

                if (($row->scored_FT+$home[$index]->conced_FT) >= $minmax[1][1]) {
                    $A_P_FT90++;
                }
                if (($row->scored_FT+$row->conced_FT) >= $minmax[1][1]) {
                    $A_H_FT90++;
                }
                
                if (($row->scored_FT+$home[$index]->conced_FT) >= $minmax[1][2]) {
                    $A_P_FT85++;
                }
                if (($row->scored_FT+$row->conced_FT) >= $minmax[1][2]) {
                    $A_H_FT85++;
                }

                if (($row->scored_FT+$home[$index]->conced_FT) >= $minmax[1][3]) {
                    $A_P_FT80++;
                }
                if (($row->scored_FT+$row->conced_FT) >= $minmax[1][3]) {
                    $A_H_FT80++;
                }
            }
            $A_FT95 = ($A_P_FT95*20).'/'.($A_H_FT95*20);
            $A_FT90 = ($A_P_FT90*20).'/'.($A_H_FT90*20);
            $A_FT85 = ($A_P_FT85*20).'/'.($A_H_FT85*20);
            $A_FT80 = ($A_P_FT80*20).'/'.($A_H_FT80*20);

        return array(
            array($min_score_HT-$absError,$min_score_FT-$absError,($min_score_HT+5)-$absError,($min_score_FT+5)-$absError,($min_score_HT+10)-$absError,($min_score_FT+10)-$absError),
            array($per_H_P_HT_min*20,$per_A_P_HT_min*20,$per_H_P_FT_min*20,$per_A_P_FT_min*20),
            array($per_H_H_HT_min*20,$per_A_H_HT_min*20,$per_H_H_FT_min*20,$per_A_H_FT_min*20),
            array($pow_H5_HT*20,$pow_A5_HT*20,$pow_H5_FT*20,$pow_A5_FT*20),
            array($his_H5_HT*20,$his_A5_HT*20,$his_H5_FT*20,$his_A5_FT*20),
            array($pow_H10_HT*20,$pow_A10_HT*20,$pow_H10_FT*20,$pow_A10_FT*20),
            array($his_H10_HT*20,$his_A10_HT*20,$his_H10_FT*20,$his_A10_FT*20),
            array(
                array(
                    array($H_HT95,$H_HT90,$H_HT85,$H_HT80),
                    array($A_HT95,$A_HT90,$A_HT85,$A_HT80)
                ),
                array(
                    array($H_FT95,$H_FT90,$H_FT85,$H_FT80),
                    array($A_FT95,$A_FT90,$A_FT85,$A_FT80)
                )
            )
        );
    }
