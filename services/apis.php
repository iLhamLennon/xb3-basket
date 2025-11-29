<?php

    function textmebot(string $handphone, string $message)
    {
        $apikey = "zGRQ9YrQGMeg";

        $curl = curl_init("http://api.textmebot.com/send.php?recipient=$handphone&apikey=$apikey&text=$message");
        curl_exec($curl);
        curl_close($curl);
    }
