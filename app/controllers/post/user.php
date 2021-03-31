<?php

    $json = [
        'error' => [ "type" => "warning", "message" => "empty post" ]
    ];

    $ok = false;

    if (isset($_POST) && !empty($_POST)) {

        $app->query(
            "INSERT INTO users SET name = :name, email = :email, sex = :sex, created_at = NOW()",
            [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'sex' => $_POST['sex'],
            ]
        );

        $json = $app->fetch("SELECT * FROM users WHERE id = :id", ['id' => $app->lastId()]);
        $ok = true;
    }

    if (isset($ok) && $ok === true) {
        $app->http_status(422);
    } else {
        $app->http_status(200);
    }
    echo json_encode($json);