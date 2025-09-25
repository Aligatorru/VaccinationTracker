<?php
require 'db.php';
$action = $_REQUEST['action'] ?? '';

if ($action === 'add_vac') {
    $user_id = intval($_POST['user_id'] ?? 0);
    $vaccine_id = $_POST['vaccine_id'] ?? '';
    if ($vaccine_id === '__new') {
        $stmt = $db->prepare("INSERT INTO vaccines (infection_name_ru, infection_name_en, infection_name_es, infection_name_pt) VALUES (?,?,?,?)");
        $stmt->execute([$_POST['inf_ru'] ?? '', $_POST['inf_en'] ?? '', $_POST['inf_es'] ?? '', $_POST['inf_pt'] ?? '']);
        $vaccine_id = $db->lastInsertId();
    } else {
        $vaccine_id = intval($vaccine_id);
    }
    $stmt = $db->prepare("INSERT INTO user_vaccinations (user_id, vaccine_id, dose, date, comment, result, series) VALUES (?,?,?,?,?,?,?)");
    $stmt->execute([$user_id, $vaccine_id, $_POST['dose'] ?? '', $_POST['date'] ?? '', $_POST['comment'] ?? '', $_POST['result'] ?? '', $_POST['series'] ?? '']);
    header('Location: patient.php?id='.$user_id);
    exit;
}

if ($action === 'edit_vac') {
    $vac_id = intval($_POST['vac_id'] ?? 0);
    $user_id = intval($_POST['user_id'] ?? 0);
    $vaccine_id = $_POST['vaccine_id'] ?? '';
    if ($vaccine_id === '__new') {
        $stmt = $db->prepare("INSERT INTO vaccines (infection_name_ru, infection_name_en, infection_name_es, infection_name_pt) VALUES (?,?,?,?)");
        $stmt->execute([$_POST['inf_ru'] ?? '', $_POST['inf_en'] ?? '', $_POST['inf_es'] ?? '', $_POST['inf_pt'] ?? '']);
        $vaccine_id = $db->lastInsertId();
    } else {
        $vaccine_id = intval($vaccine_id);
    }
    $stmt = $db->prepare("UPDATE user_vaccinations SET vaccine_id=?, dose=?, date=?, comment=?, result=?, series=? WHERE id=? AND user_id=?");
    $stmt->execute([$vaccine_id, $_POST['dose'] ?? '', $_POST['date'] ?? '', $_POST['comment'] ?? '', $_POST['result'] ?? '', $_POST['series'] ?? '', $vac_id, $user_id]);
    header('Location: patient.php?id='.$user_id);
    exit;
}

if ($action === 'delete_vac') {
    $id = intval($_GET['id'] ?? 0);
    $user_id = intval($_GET['user_id'] ?? 0);
    if ($id) {
        $stmt = $db->prepare("DELETE FROM user_vaccinations WHERE id=? AND user_id=?");
        $stmt->execute([$id,$user_id]);
    }
    header('Location: patient.php?id='.$user_id);
    exit;
}

if ($action === 'edit_vaccine') {
    $vid = intval($_POST['edit_inf_id'] ?? 0);
    $ru = $_POST['inf_ru'] ?? '';
    $en = $_POST['inf_en'] ?? '';
    $es = $_POST['inf_es'] ?? '';
    $pt = $_POST['inf_pt'] ?? '';
    if ($vid) {
        $stmt = $db->prepare("UPDATE vaccines SET infection_name_ru=?, infection_name_en=?, infection_name_es=?, infection_name_pt=? WHERE id=?");
        $stmt->execute([$ru,$en,$es,$pt,$vid]);
    } else {
        $stmt = $db->prepare("INSERT INTO vaccines (infection_name_ru, infection_name_en, infection_name_es, infection_name_pt) VALUES (?,?,?,?)");
        $stmt->execute([$ru,$en,$es,$pt]);
    }
    // redirect back to current patient page if provided
    $ref = $_SERVER['HTTP_REFERER'] ?? 'index.php';
    header('Location: '.$ref);
    exit;
}

if ($action === 'get_vaccine') {
    $id = intval($_GET['id'] ?? 0);
    $stmt = $db->prepare("SELECT * FROM vaccines WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($row);
    exit;
}

header('Content-Type: application/json');
echo json_encode(['ok'=>false,'error'=>'unknown action']);
