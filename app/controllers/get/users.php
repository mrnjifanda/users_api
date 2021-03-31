<?php

    $json = [
        'error' => [ "type" => "warning", "message" => "empty user" ]
    ];

    $ok = false;

    $users = $app->fetchAll("SELECT * FROM users");

    if ($users) {
        $json = $users;
        $ok = true;
    }

    if ($ok === false) {
        $app->http_status(422);
    } else {
        $app->http_status(200);
    }
    echo json_encode($json);