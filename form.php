<?php

    // В какой кодировке приходят данные.
    $incoming_encoding = 'Windows-1251';
    // Почта куда слать сообщения.
    $address = "regul-tech@mail.ru";
    //откуда слать
    $from = "regul-tech@mail.ru";


    // Получаем переменные
    $error = "";
    if (!isset($_GET['name'])) {$error .= "Не указано имя.";} else { $name = $_GET['name'];}
    if (!isset($_GET['mail'])) {$error .= "Не указан Email.";} else { $mail = $_GET['mail'];}
    if (!isset($_GET['phone'])) {$error .= "Не указан номер телефона.";} else { $tel = $_GET['phone'];}
    if (!isset($_GET['message'])) {$error .= "Не указан комментарий.";} else { $comment = $_GET['message'];}

    header("Content-Type: application/json; charset=utf-8");
    if (empty($error)) {
        // Проверяем переменные на вшивость.
        $name = htmlspecialchars($name, ENT_QUOTES);
        $mail = htmlspecialchars($mail, ENT_QUOTES);
        $tel = htmlspecialchars($tel, ENT_QUOTES);
        $comment = htmlspecialchars($comment, ENT_QUOTES);

        // Переводим в UTF-8
//         $name = iconv($incoming_encoding, 'UTF-8', $name);
//         $mail = iconv($incoming_encoding, 'UTF-8', $mail);
//         $tel = iconv($incoming_encoding, 'UTF-8', $tel);
//         $comment = iconv($incoming_encoding, 'UTF-8', $comment);

        // Узнаем айпи отправителя.
        $remote_ip = $_SERVER["REMOTE_ADDR"];

        // Тема письма. Для фильтров в гмайле.
        $sub = "[Почта с сайта «Регулятор \"Звезда\"»]";

        // Собственно, текст. Точнее, HTML.
        $mes = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>Почта с сайта «Регулятор "Звезда"»</title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
            </head>
            <body style="margin: 0; padding: 0;">
                <h2>Получено новое сообщение с сайта «Регулятор "Звезда"»</h2>
                <table rules="all" style="border-color: #666;" cellpadding="10" width="100%">
                <tr>
                    <td width="20%">Имя:</td>
                    <td>' . $name . '</td>
                </tr>
                    <tr>
                        <td>Телефон:</td>
                        <td>' . $tel . '</td>
                    </tr>
                    <tr>
                        <td>E-mail:</td>
                        <td>' . $mail . '</td>
                    </tr>
                    <tr>
                        <td>Сообщение:</td>
                        <td>' . $comment . '</td>
                    </tr>
                    <tr>
                        <td>IP:</td>
                        <td><a href="http://ip-whois.net/ip_geo.php?ip=' . $remote_ip . '">' . $remote_ip . '</a></td>
                    </tr>
                </table>
            </body>
        </html>
        ';

        $headers = "From: " . $from . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";


        // Шлем песьмо, чочо.
        $send = mail($address, $sub, $mes, $headers);
        if ($send) {
            echo json_encode([
                "message" => 'Ваша заявка отправлена!'
            ]);
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode([
                "message" => "Ошибка при отправке заявки!"
            ]);
        }
        die();
    }

    header('HTTP/1.1 400 Internal Server Error');
    echo json_encode([
        "error" => $error
    ]);
