<?php
require_once "vendor/autoload.php";
require_once "App/models/db.php";

$requestmethod = empty($_POST['_method']) ? strtoupper($_SERVER['REQUEST_METHOD']) : $_POST['_method'];

switch ($requestmethod) {
    case 'GET':
    default:
        responseGet();
        break;
    case 'POST':
        responsePost();
        break;
    case 'DELETE':
        responseDelete();
        break;
    case "PUT":
    case "PATCH":
        responseUpdate();
}

function responseUpdate()
{
    var_dump($_REQUEST);
    if (!empty($_REQUEST['id'])) {
        $order = Order::find($_REQUEST['id']);
        if (empty($order)) {
            http_response_code(404);
            echo "Failed to update: 404 not found";
        } else {
            if (!empty($_REQUEST['good'])) {
                $order->fill([
                    'user_id' => strip_tags($_REQUEST['user_id']),
                    'adress' => strip_tags($_REQUEST['adress']),
                    'phone' => strip_tags($_REQUEST['phone']),
                    'mail' => strip_tags($_REQUEST['mail']),
                    'good' => strip_tags($_REQUEST['good']),
                    'photo' => strip_tags($_REQUEST['photo']),
                    'desc' => strip_tags($_REQUEST['desc'])
                ]);
                $order->save();
                echo "user updated";
            } else {
                http_response_code(422);
                echo "Field  required";
            }
        }
    } else {
        http_response_code(422);
        echo "Field 'id' required";
    }
}

function responseDelete()
{
    if (!empty($_REQUEST['id'])) {
        $order = Order::find($_REQUEST['id']);
        if (empty($order)) {
            http_response_code(404);
            echo "Failed to delete: 404 not found";
        } else {
            $order->delete();
            echo "User deleted";
        }
    } else {
        http_response_code(422);
        echo "Field 'id' required";
    }
}

function responsePost()
{
    if (empty($_POST['good'])) {
        http_response_code(422);
        echo "Field 'name' required";
    } else {
        $order = Order::create([
            'user_id' => $_POST['user_id'],
            'adress' => $_POST['adress'],
            'phone' => $_POST['phone'],
            'mail' => $_POST['mail'],
            'good' => $_POST['good'],
            'photo' => $_POST['photo'],
            'desc' => $_POST['desc']
        ]);
    }
}

function responseGet()
{
    if (!empty($_GET['id'])) {
        $user = Order::find($_GET['id']);
        if (empty($user)) {
            http_response_code(404);
            echo "404 not found";
        } else {
            echo $user;
        }
    } else {
        echo Order::all();
    }
}