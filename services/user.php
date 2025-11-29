<?php
    date_default_timezone_set('Asia/Jakarta');
    require_once '../db/connection.php';
    require_once './apis.php';

    $table = 'jg_users';
    $mod = $_GET['mod'];

    if ($mod=='signin') {
        $hand = (int)preg_replace('/[^0-9]/', '', $_POST['user']);
        $mail = $_POST['user'];
        $pass = md5($_POST['pass']);

        $sql1 = mysqli_query($conn, "SELECT * FROM jg_users WHERE hand=$hand AND active>0 AND deleted_at IS NULL");
        $rows1 = [];
        while($row1 = mysqli_fetch_assoc($sql1)) {
            $rows1[] = $row1;
        }
        $sql2 = mysqli_query($conn, "SELECT * FROM jg_users WHERE mail='$mail' AND active>0 AND deleted_at IS NULL");
        $rows2 = [];
        while($row2 = mysqli_fetch_assoc($sql2)) {
            $rows2[] = $row2;
        }
                
        if (count($rows1)<1 && count($rows2)<1) {
            $result = [
                'status' => false,
                'value' => 0,
                'message' => 'akun belum terdaftar'
            ];
        }
        else {
            if (count($rows1)>0) {
                if ($rows1[0]['pass']!=$pass) {
                    $result = [
                        'status' => false,
                        'value' => 1,
                        'message' => 'kata sandi salah'
                    ];
                }
                else {
                    $result = [
                        'status' => true,
                        'value' => 'dashboard',
                        'message' => $rows1[0]['ttle'] . " " . $rows1[0]['name']
                    ];
                }
            }
            else if (count($rows2)>0) {
                if ($rows2[0]['pass']!=$pass) {
                    $result = [
                        'status' => false,
                        'value' => 1,
                        'message' => 'kata sandi salah'
                    ];
                }
                else {
                    $result = [
                        'status' => true,
                        'value' => 'dashboard',
                        'message' => $rows2[0]['ttle'] . " " . $rows2[0]['name']
                    ];
                }
            }
        }

        echo json_encode($result);
    }
    else if ($mod=='forgot') {
        $hand = (int)preg_replace('/[^0-9]/', '', $_POST['user']);
        $mail = $_POST['user'];
        
        $sql1 = mysqli_query($conn, "SELECT * FROM jg_users WHERE hand=$hand AND active>0 AND deleted_at IS NULL");
        $rows1 = [];
        while($row1 = mysqli_fetch_assoc($sql1)) {
            $rows1[] = $row1;
        }
        $sql2 = mysqli_query($conn, "SELECT * FROM jg_users WHERE mail='$mail' AND active>0 AND deleted_at IS NULL");
        $rows2 = [];
        while($row2 = mysqli_fetch_assoc($sql2)) {
            $rows2[] = $row2;
        }

        if (count($rows1)<1 && count($rows2)<1) {
            $result = [
                'status' => false,
                'value' => 0,
                'message' => 'akun belum terdaftar'
            ];
        }
        else {
            $tokened_cd = rand(100001, 999999);
            $tokened_ex = date('Y-m-d H:i:s', strtotime('+5 minute'));

            $message = "Berikut nomor token untuk melakukan sign in akses platform aplikasi JAYA GRAHA - Land Development di bawah ini :\n*$tokened_cd*\n\nTerima kasih,\nManagement";
            if (count($rows1)>0) {
                $hand = $rows1[0]['hand'];
                mysqli_query($conn, "UPDATE jg_users SET tokened_cd=$tokened_cd,tokened_ex='$tokened_ex',tokened_rd='password' WHERE id=".$rows1[0]['id']."");
            }
            else if (count($rows2)>0) {
                $hand = $rows2[0]['hand'];
                mysqli_query($conn, "UPDATE jg_users SET tokened_cd=$tokened_cd,tokened_ex='$tokened_ex',tokened_rd='password' WHERE id=".$rows2[0]['id']."");
            }
            textmebot("+62".$hand, urlencode($message));

            $result = [
                'status' => true,
                'value' => 'token',
                'message' => null
            ];
        }

        echo json_encode($result);
    }
    else if ($mod=='signup') {
        $hand = (int)preg_replace('/[^0-9]/', '', $_POST['hand']);
        $mail = $_POST['mail'];
        $pass = md5($_POST['pass2']);

        $sql1 = mysqli_query($conn, "SELECT * FROM jg_users WHERE hand=$hand AND active>0 AND deleted_at IS NULL");
        $rows1 = [];
        while($row1 = mysqli_fetch_assoc($sql1)) {
            $rows1[] = $row1;
        }
        $sql2 = mysqli_query($conn, "SELECT * FROM jg_users WHERE mail='$mail' AND active>0 AND deleted_at IS NULL");
        $rows2 = [];
        while($row2 = mysqli_fetch_assoc($sql2)) {
            $rows2[] = $row2;
        }

        if (count($rows1)>0 || count($rows2)>0) {
            if (count($rows1)>0) {
                $result = [
                    'status' => false,
                    'value' => 0,
                    'message' => 'nomor sudah terdaftar'
                ];
            }
            else if (count($rows2)>0) {
                $result = [
                    'status' => false,
                    'value' => 1,
                    'message' => 'akun sudah terdaftar'
                ];
            }
        }
        else {
            $tokened_cd = rand(100001, 999999);
            $tokened_ex = date('Y-m-d H:i:s', strtotime('+5 minute'));

            mysqli_query($conn, "DELETE FROM jg_users WHERE hand=$hand AND active<1");
            mysqli_query($conn, "DELETE FROM jg_users WHERE mail='$mail' AND active<1");
            mysqli_query($conn, "INSERT INTO jg_users(hand,mail,pass,tokened_cd,tokened_ex,tokened_rd) VALUES($hand,'$mail','$pass',$tokened_cd,'$tokened_ex','dashboard')");

            $message = "Berikut nomor token untuk melakukan registrasi akses platform aplikasi JAYA GRAHA - Land Development di bawah ini :\n*$tokened_cd*\n\nTerima kasih,\nManagement";
            textmebot("+62".$hand, urlencode($message));

            $result = [
                'status' => true,
                'value' => 'token',
                'message' => null
            ];
        }

        echo json_encode($result);
    }
    else if ($mod=='token') {
        $hand = (int)preg_replace('/[^0-9]/', '', $_POST['user']);
        $mail = $_POST['user'];
        $pass = md5($_POST['pass']);
        $token = $_POST['otp1'].$_POST['otp2'].$_POST['otp3'].$_POST['otp4'].$_POST['otp5'].$_POST['otp6'];
        $token = (int)$token;

        $sql1 = mysqli_query($conn, "SELECT * FROM jg_users WHERE hand=$hand AND deleted_at IS NULL");
        $rows1 = [];
        while($row1 = mysqli_fetch_assoc($sql1)) {
            $rows1[] = $row1;
        }
        $sql2 = mysqli_query($conn, "SELECT * FROM jg_users WHERE mail='$mail' AND deleted_at IS NULL");
        $rows2 = [];
        while($row2 = mysqli_fetch_assoc($sql2)) {
            $rows2[] = $row2;
        }

        if (count($rows1)>0) {
            if ($rows1[0]['tokened_cd']!=$token) {
                $result = [
                    'status' => false,
                    'value' => 5,
                    'message' => 'nomor token salah'
                ];
            }
            else if (date('Y-m-d H:i:s', strtotime($rows1[0]['tokened_ex'])) < date('Y-m-d H:i:s')) {
                $result = [
                    'status' => false,
                    'value' => 5,
                    'message' => 'nomor token expired'
                ];
            }
            else {
                if ($rows1[0]['tokened_rd']=='dashboard') {
                    mysqli_query($conn, "UPDATE jg_users SET active=1 WHERE id=".$rows1[0]['id']." AND active<1");
                }

                $result = [
                    'status' => true,
                    'value' => 'dashboard',
                    'message' => null
                ];
            }
        }
        else if (count($rows2)>0) {
            if ($rows2[0]['tokened_cd']!=$token) {
                $result = [
                    'status' => false,
                    'value' => 5,
                    'message' => 'nomor token salah'
                ];
            }
            else if (date('Y-m-d H:i:s', strtotime($rows2[0]['tokened_ex'])) < date('Y-m-d H:i:s')) {
                $result = [
                    'status' => false,
                    'value' => 5,
                    'message' => 'nomor token expired'
                ];
            }
            else {
                if ($rows2[0]['tokened_rd']=='dashboard') {
                    mysqli_query($conn, "UPDATE jg_users SET active=1 WHERE id=".$rows2[0]['id']." AND active<1");
                }

                $result = [
                    'status' => true,
                    'value' => 'dashboard',
                    'message' => null
                ];
            }
        }

        echo json_encode($result);
    }
    else if ($mod=='notification') {
        $result = [
            'status' => false,
            'value' => null,
            'message' => null
        ];

        $hand = (int)preg_replace('/[^0-9]/', '', $_GET['user']);
        $mail = $_GET['user'];
        
        $que1 = "SELECT * ";
        $que1 .= "FROM jg_notifications t1 ";
        $que1 .= "LEFT JOIN jg_users t2 ON t1.user=t2.id ";
        $que1 .= "WHERE t1.active>0 AND t2.hand=$hand AND t2.active>0 AND t2.deleted_at IS NULL";
        $sql1 = mysqli_query($conn, $que1);
        $rows1 = [];
        while($row1 = mysqli_fetch_assoc($sql1)) {
            $rows1[] = $row1;
        }
        $que2 = "SELECT * ";
        $que2 .= "FROM jg_notifications t1 ";
        $que2 .= "LEFT JOIN jg_users t2 ON t1.user=t2.id ";
        $que2 .= "WHERE t1.active>0 AND t2.mail='$mail' AND t2.active>0 AND t2.deleted_at IS NULL";
        $sql2 = mysqli_query($conn, $que2);
        $rows2 = [];
        while($row2 = mysqli_fetch_assoc($sql2)) {
            $rows2[] = $row2;
        }

        if (count($rows1)>0 || count($rows2)>0) {
            $result = [
                'status' => true,
                'value' => count($rows1)>0 ? count($rows1) : count($rows2),
                'message' => null
            ];
        }

        echo json_encode($result);
    }
    else if ($mod=='read') {
        $hand = (int)preg_replace('/[^0-9]/', '', $_GET['user']);
        $mail = $_GET['user'];
        
        $que1 = "SELECT id,IF(photo IS NULL, NULL, TO_BASE64(photo)) AS photo,ttle,name,IF(addr IS NULL, '', addr) AS addr,IF(hand > 0, CONCAT(0,hand), NULL) AS hand,mail ";
        $que1 .= "FROM jg_users t1 ";
        $que1 .= "WHERE t1.hand=$hand";
        $sql1 = mysqli_query($conn, $que1);
        $rows1 = [];
        while($row1 = mysqli_fetch_assoc($sql1)) {
            $rows1[] = $row1;
        }
        $que2 = "SELECT id,IF(photo IS NULL, NULL, TO_BASE64(photo)) AS photo,ttle,name,IF(addr IS NULL, '', addr) AS addr,IF(hand > 0, CONCAT(0,hand), NULL) AS hand,mail ";
        $que2 .= "FROM jg_users t1 ";
        $que2 .= "WHERE t1.mail='$mail'";
        $sql2 = mysqli_query($conn, $que2);
        $rows2 = [];
        while($row2 = mysqli_fetch_assoc($sql2)) {
            $rows2[] = $row2;
        }

        if (count($rows1)>0) {
            $result = [
                'status' => true,
                'value' => $rows1[0],
                'message' => null
            ];
        }
        if (count($rows2)>0) {
            $result = [
                'status' => true,
                'value' => $rows2[0],
                'message' => null
            ];
        }

        echo json_encode($result);
    }
    else if ($mod=='update') {
        $id = $_POST['id'];
        $ttle = $_POST['ttle'];
        $name = $_POST['name'];
        $addr = $_POST['addr'];

        mysqli_query($conn, "UPDATE ".$table." SET ttle='$ttle',name='$name',addr='$addr' WHERE id=".$id."");
        mysqli_query($conn, "INSERT INTO jg_notifications(user,message) VALUES($id,'Ubah akun')");

        $result = [
            'status' => true,
            'value' => null,
            'message' => null
        ];

        echo json_encode($result);
    }
