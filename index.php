<?php
require 'db.php';

// Create user
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action'] ?? '') === 'create_user') {
    $name_ru = trim($_POST['name_ru'] ?? '');
    $name_lat = trim($_POST['name_lat'] ?? '');
    $birth = $_POST['birth'] ?? '';
    $sex = $_POST['sex'] ?? '';
    if ($name_ru) {
        $stmt = $db->prepare("INSERT INTO users (name_ru, name_lat, birthdate, sex) VALUES (?,?,?,?)");
        $stmt->execute([$name_ru,$name_lat,$birth,$sex]);
    }
    header('Location: index.php');
    exit;
}

$users = $db->query("SELECT id, COALESCE(NULLIF(name_ru,''), name_lat) AS label, name_ru, name_lat, birthdate FROM users ORDER BY lower(label)")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="ru">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Пациенты</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <h1>Веб-приложение учёта прививок</h1>
  <table class="table">
    <tr><th>Имя</th><th>Дата рождения</th><th>Действия</th></tr>
    <?php foreach($users as $u): ?>
      <tr>
        <td><a href="patient.php?id=<?=$u['id']?>"><?=htmlspecialchars($u['label'])?></a></td>
        <td><?=htmlspecialchars($u['birthdate'])?></td>
        <td>
          <a class="small" href="patient.php?id=<?=$u['id']?>">Открыть карту</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>

  <div id="newUser" class="modal" style="display:none;">
    <form method="post" class="card">
      <h2>Новый пользователь</h2>
      <input name="name_ru" placeholder="Имя (кириллица)" required>
      <input name="name_lat" placeholder="Имя (латиница)">
      <input name="birth" type="date" placeholder="Дата рождения">
      <select name="sex"><option value="m">М</option><option value="f">Ж</option><option value="o">Другое</option></select>
      <input type="hidden" name="action" value="create_user">
      <div class="actions">
        <button class="btn" type="submit">Создать</button>
        <a class="btn ghost" href="#" onclick="document.getElementById('newUser').style.display='none';return false;">Отмена</a>
      </div>
    </form>
  </div>

  <hr>


  <a class="btn" href="#" onclick="document.getElementById('newUser').style.display='block';return false;">+ Новый пользователь</a>
  <!-- <p>Проект веб-приложения учёта прививок на <a href="https://github.com/Aligatorru/VaccinationTracker">GitHub</a> </p> -->
  <p>Веб-приложение доступно только в домашней локальной сети</p>

</div>
<script src="assets/js.js"></script>
</body>
</html>
