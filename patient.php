<?php
require 'db.php';
$id = intval($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }

$user = $db->prepare("SELECT * FROM users WHERE id=?");
$user->execute([$id]);
$user = $user->fetch(PDO::FETCH_ASSOC);
if (!$user) { echo "User not found"; exit; }

$lang = in_array($_GET['lang'] ?? 'ru', ['ru','en','es','pt']) ? $_GET['lang'] : 'ru';


// ---------------------
// СОРТИРОВКА
// ---------------------
$sort = $_GET['sort'] ?? 'date_desc';
$orderSql = "ORDER BY uv.date DESC, uv.id DESC";
switch ($sort) {
    case 'date_asc':
        $orderSql = "ORDER BY uv.date ASC, uv.id ASC";
        break;
    case 'alpha_asc':
        $orderSql = "ORDER BY COALESCE(v.infection_name_$lang, '') COLLATE NOCASE ASC, uv.date DESC";
        break;
    case 'alpha_desc':
        $orderSql = "ORDER BY COALESCE(v.infection_name_$lang, '') COLLATE NOCASE DESC, uv.date DESC";
        break;
    default:
        $orderSql = "ORDER BY uv.date DESC, uv.id DESC";
}
// ---------------------
// ВСЕ ИНФЕКЦИИ
// ---------------------
$all_v = $db->query("SELECT * FROM vaccines ORDER BY lower(infection_name_ru)")->fetchAll(PDO::FETCH_ASSOC);

// ---------------------
// ФИЛЬТР ПО ИНФЕКЦИЯМ
// ---------------------
$filter = $_GET['filter'] ?? [];
if (empty($filter)) {
    $filter = array_column($all_v, 'id'); // теперь все id есть
}

$whereFilter = "";
$params = [$id];
if (!empty($filter)) {
    $placeholders = implode(',', array_fill(0, count($filter), '?'));
    $whereFilter = " AND uv.vaccine_id IN ($placeholders)";
    $params = array_merge($params, $filter);
}


// ---------------------
// СТОЛБЦЫ
// ---------------------
$all_columns = [
    'infection' => ['ru'=>'Инфекция','en'=>'Infection','es'=>'Infección','pt'=>'Infecção'],
    'dose'      => ['ru'=>'Прививка','en'=>'Dose','es'=>'Dosis','pt'=>'Dose'],
    'date'      => ['ru'=>'Дата','en'=>'Date','es'=>'Fecha','pt'=>'Data'],
    'comment'   => ['ru'=>'Комментарий','en'=>'Comment','es'=>'Comentario','pt'=>'Comentário'],
    'result'    => ['ru'=>'Результат','en'=>'Result','es'=>'Resultado','pt'=>'Resultado'],
    'series'    => ['ru'=>'Серия','en'=>'Series','es'=>'Serie','pt'=>'Série'],
];
$selected_columns = $_GET['columns'] ?? array_keys($all_columns);

// ---------------------
// ВАКЦИНАЦИИ
// ---------------------
$sql = "SELECT uv.*, v.infection_name_ru, v.infection_name_en, v.infection_name_es, v.infection_name_pt
        FROM user_vaccinations uv
        LEFT JOIN vaccines v ON uv.vaccine_id=v.id
        WHERE uv.user_id=? $whereFilter
        $orderSql";
$vacs = $db->prepare($sql);
$vacs->execute($params);
$vacs = $vacs->fetchAll(PDO::FETCH_ASSOC);

$all_v = $db->query("SELECT * FROM vaccines ORDER BY lower(infection_name_ru)")->fetchAll(PDO::FETCH_ASSOC);

function getName($row,$lang){
    $col = "infection_name_".$lang;
    return $row[$col] ?? $row['infection_name_ru'] ?? '—';
}

// словарь переводов (как у тебя было)
$translations = [
    'ru' => [
        'back' => '← Назад',
        'patient' => 'Пациент',
        'birthdate' => 'Дата рождения',
        'sex' => 'Пол',
        'male' => 'Мужской',
        'female' => 'Женский',
        'sort' => 'Сортировка',
        'by_date_desc' => 'по дате (новые → старые)',
        'by_date_asc' => 'по дате (старые → новые)',
        'by_alpha_asc' => 'по алфавиту (А-Я)',
        'by_alpha_desc' => 'по алфавиту (Я-А)',
        'infection' => 'Инфекция',
        'dose' => 'Прививка',
        'date' => 'Дата',
        'comment' => 'Комментарий',
        'result' => 'Результат',
        'series' => 'Серия',
        'actions' => 'Действия',
        // 'edit' => 'Редактировать',
        // 'delete' => 'Удалить',
        'edit' => '✏️',
        'delete' => '❌',
        'confirm_delete' => 'Удалить прививку?',
        'add_vac' => 'Добавить прививку',
        'edit_vac' => 'Редактировать прививку',
        'save' => 'Сохранить',
        'add' => 'Добавить',
        'cancel' => 'Отмена',
        'choose' => '-- выбрать --',
        'new' => 'Добавить новую...',
        'new_infection' => 'Если вводите новую инфекцию — заполните переводы:',
        'dose_placeholder' => 'V, V1, V2, RV1...',
        'series_vaccine' => 'Серия вакцины',
        'dict_edit' => 'Редактировать/Добавить инфекцию (справочник)',
        'filter' => 'Фильтр по инфекциям',
        'columns' => 'Отображаемые столбцы',
    ],
    'en' => [
        'back' => '← Back',
        'patient' => 'Patient',
        'birthdate' => 'Birthdate',
        'sex' => 'Sex',
        'male' => 'Male',
        'female' => 'Female',
        'sort' => 'Sort',
        'by_date_desc' => 'by date (new → old)',
        'by_date_asc' => 'by date (old → new)',
        'by_alpha_asc' => 'alphabetically (A → Z)',
        'by_alpha_desc' => 'alphabetically (Z → A)',
        'by_date' => 'by date',
        'by_alpha' => 'alphabetically',
        'infection' => 'Infection',
        'dose' => 'Dose',
        'date' => 'Date',
        'comment' => 'Comment',
        'result' => 'Result',
        'series' => 'Series',
        'actions' => 'Actions',
        // 'edit' => 'Edit',
        // 'delete' => 'Delete',
        'edit' => '✏️',
        'delete' => '❌',
        'confirm_delete' => 'Delete vaccination?',
        'add_vac' => 'Add vaccination',
        'edit_vac' => 'Edit vaccination',
        'save' => 'Save',
        'add' => 'Add',
        'cancel' => 'Cancel',
        'choose' => '-- select --',
        'new' => 'Add new...',
        'new_infection' => 'If you add a new infection — fill in translations:',
        'dose_placeholder' => 'V, V1, V2, RV1...',
        'series_vaccine' => 'Vaccine series',
        'dict_edit' => 'Edit/Add infection (dictionary)',
        'filter' => 'Filter by infections',
        'columns' => 'Displayed columns',
    ],
    'es' => [
        'back' => '← Atrás',
        'patient' => 'Paciente',
        'birthdate' => 'Fecha de nacimiento',
        'sex' => 'Sexo',
        'male' => 'Masculino',
        'female' => 'Feminino',
        'sort' => 'Ordenar',
        'by_date_desc' => 'por fecha (nuevas → antiguas)',
        'by_date_asc' => 'por fecha (antiguas → nuevas)',
        'by_alpha_asc' => 'alfabéticamente (A → Z)',
        'by_alpha_desc' => 'alfabéticamente (Z → A)',
        'by_date' => 'por fecha',
        'by_alpha' => 'alfabéticamente',
        'infection' => 'Infección',
        'dose' => 'Dosis',
        'date' => 'Fecha',
        'comment' => 'Comentario',
        'result' => 'Resultado',
        'series' => 'Serie',
        'actions' => 'Acciones',
        // 'edit' => 'Editar',
        // 'delete' => 'Eliminar',
        'edit' => '✏️',
        'delete' => '❌',
        'confirm_delete' => '¿Eliminar vacuna?',
        'add_vac' => 'Agregar vacuna',
        'edit_vac' => 'Editar vacuna',
        'save' => 'Guardar',
        'add' => 'Agregar',
        'cancel' => 'Cancelar',
        'choose' => '-- elegir --',
        'new' => 'Agregar nueva...',
        'new_infection' => 'Si agrega una nueva infección — complete las traducciones:',
        'dose_placeholder' => 'V, V1, V2, RV1...',
        'series_vaccine' => 'Serie de vacuna',
        'dict_edit' => 'Editar/Agregar infección (diccionario)',
        'filter' => 'Filtro por infecciones',
        'columns' => 'Columnas visibles',
    ],
    'pt' => [
        'back' => '← Voltar',
        'patient' => 'Paciente',
        'birthdate' => 'Data de nascimento',
        'sex' => 'Sexo',
        'male' => 'Masculino',
        'female' => 'Feminino',
        'sort' => 'Ordenar',
        'by_date_desc' => 'por data (novas → antigas)',
        'by_date_asc' => 'por data (antigas → novas)',
        'by_alpha_asc' => 'alfabeticamente (A → Z)',
        'by_alpha_desc' => 'alfabeticamente (Z → A)',
        'by_date' => 'por data',
        'by_alpha' => 'alfabeticamente',
        'infection' => 'Infecção',
        'dose' => 'Dose',
        'date' => 'Data',
        'comment' => 'Comentário',
        'result' => 'Resultado',
        'series' => 'Série',
        'actions' => 'Ações',
        // 'edit' => 'Editar',
        // 'delete' => 'Excluir',
        'edit' => '✏️',
        'delete' => '❌',
        'confirm_delete' => 'Excluir vacina?',
        'add_vac' => 'Adicionar vacina',
        'edit_vac' => 'Editar vacina',
        'save' => 'Salvar',
        'add' => 'Adicionar',
        'cancel' => 'Cancelar',
        'choose' => '-- selecionar --',
        'new' => 'Adicionar nova...',
        'new_infection' => 'Se adicionar nova infecção — preencha as traduções:',
        'dose_placeholder' => 'V, V1, V2, RV1...',
        'series_vaccine' => 'Série da vacina',
        'dict_edit' => 'Editar/Adicionar infecção (dicionário)',
        'filter' => 'Filtro de infecções',
        'columns' => 'Colunas a mostrar',
    ],
];
$t = $translations[$lang] ?? $translations['ru'];

// имя пациента
$patientName = ($lang === 'ru') 
    ? ($user['name_ru'] ?: $user['name_lat']) 
    : ($user['name_lat'] ?: $user['name_ru']);

// пол пациента
$sexVal = strtolower(trim($user['sex']));
if (in_array($sexVal, ['m','male','муж','мужской'])) {
    $sexText = $t['male'];
} elseif (in_array($sexVal, ['f','female','жен','женский'])) {
    $sexText = $t['female'];
} else {
    $sexText = htmlspecialchars($user['sex']);
}
?>
<!doctype html>
<html lang="<?=$lang?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?=$t['patient']?></title>

  <style>
    .controls { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:10px; }
    .dropdown { position:relative; }
    .dropdown-btn {
      padding:5px 10px;
      border:1px solid #ccc;
      background:#f9f9f9;
      cursor:pointer;
    }
    .dropdown-content {
      display:none;
      position:absolute;
      background:#fff;
      border:1px solid #ccc;
      min-width:200px;
      max-height:250px;
      overflow:auto;
      z-index:100;
      padding:5px;
    }
    .dropdown.open .dropdown-content { display:block; }
    .dropdown-content label { display:block; padding:2px 5px; cursor:pointer; }
    .dropdown-content label:hover { background:#eee; }
    .dropdown-content label {
      display: flex;
      align-items: center;    /* выравнивание по вертикали */
      gap: 6px;               /* расстояние между чекбоксом и текстом */
      padding: 4px 6px;
      cursor: pointer;
      font-size: 14px;
      line-height: 1.4;
      user-select: none;
    }

    .dropdown-content input[type="checkbox"],
    .dropdown-content input[type="radio"] {
      width: auto !important;
      margin: 0;              /* убираем стандартные отступы браузера */
      flex-shrink: 0;
    }
  </style>

  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
  <a class="btn" href="index.php"><?=$t['back']?></a>
  <h1><?=$t['patient']?>: <?=htmlspecialchars($patientName)?></h1>
  <?php
  $birthdateFormatted = '—';
  if (!empty($user['birthdate'])) {
      $bd = DateTime::createFromFormat('Y-m-d', $user['birthdate']);
      if ($bd) {
          $birthdateFormatted = $bd->format('d.m.Y');
      }
  }
  ?>
  <p><?=$t['birthdate']?>: <?=htmlspecialchars($birthdateFormatted)?> — <?=$t['sex']?>: <?=htmlspecialchars($sexText)?></p>


  <div class="lang-flags">
    <a class="flag" href="?id=<?=$id?>&lang=ru">🇷🇺</a>
    <a class="flag" href="?id=<?=$id?>&lang=en">🇬🇧</a>
    <a class="flag" href="?id=<?=$id?>&lang=es">🇪🇸</a>
    <a class="flag" href="?id=<?=$id?>&lang=pt">🇵🇹</a>
  </div>

  <!-- Панель управления -->
  <form method="get">
  <input type="hidden" name="id" value="<?=$id?>">
  <input type="hidden" name="lang" value="<?=$lang?>">

  <div class="controls">
    <!-- сортировка -->
    <div class="dropdown">
      <div class="dropdown-btn"><?=$t['sort']?></div>
      <div class="dropdown-content">
        <label><input type="radio" name="sort" value="date_desc" <?= $sort==='date_desc'?'checked':'' ?>> <?=$t['by_date_desc']?></label>
        <label><input type="radio" name="sort" value="date_asc" <?= $sort==='date_asc'?'checked':'' ?>> <?=$t['by_date_asc']?></label>
        <label><input type="radio" name="sort" value="alpha_asc" <?= $sort==='alpha_asc'?'checked':'' ?>> <?=$t['by_alpha_asc']?></label>
        <label><input type="radio" name="sort" value="alpha_desc" <?= $sort==='alpha_desc'?'checked':'' ?>> <?=$t['by_alpha_desc']?></label>
      </div>
    </div>

    <!-- фильтр по инфекциям -->
    <div class="dropdown">
      <div class="dropdown-btn"><?=$t['filter']?></div>
      <div class="dropdown-content">
        <?php foreach($all_v as $av): ?>
          <label>
            <input type="checkbox" name="filter[]" value="<?=$av['id']?>" <?=in_array($av['id'],$filter)?'checked':''?>>
            <?=htmlspecialchars(getName($av,$lang))?>
          </label>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- выбор столбцов -->
    <div class="dropdown">
      <div class="dropdown-btn"><?=$t['columns']?></div>
      <div class="dropdown-content">
        <?php foreach($all_columns as $key=>$lbls): ?>
          <label>
            <input type="checkbox" name="columns[]" value="<?=$key?>" <?=in_array($key,$selected_columns)?'checked':''?>>
            <?=htmlspecialchars($lbls[$lang] ?? $lbls['ru'])?>
          </label>
        <?php endforeach; ?>
      </div>
    </div>

    <button class="btn" type="submit"><?=$t['save']?></button>
  </div>
</form>

<script>
document.querySelectorAll('.dropdown-btn').forEach(btn=>{
  btn.addEventListener('click',()=>{
    const dd = btn.parentElement;
    dd.classList.toggle('open');
  });
});

// закрытие при клике вне
document.addEventListener('click',e=>{
  document.querySelectorAll('.dropdown').forEach(dd=>{
    if(!dd.contains(e.target)) dd.classList.remove('open');
  });
});
</script>

  <table class="table">
    <tr>
      <?php foreach($selected_columns as $col): ?>
        <th><?=htmlspecialchars($all_columns[$col][$lang] ?? $all_columns[$col]['ru'])?></th>
      <?php endforeach; ?>
      <th><?=$t['actions']?></th>
    </tr>
    <?php foreach($vacs as $vv): ?>
      <tr>
        <?php if(in_array('infection',$selected_columns)): ?><td><?=htmlspecialchars(getName($vv,$lang))?></td><?php endif; ?>
        <?php if(in_array('dose',$selected_columns)): ?><td><?=htmlspecialchars($vv['dose'])?></td><?php endif; ?>

        <?php if(in_array('date',$selected_columns)): ?>
            <td>
                <?php 
                if (!empty($vv['date'])) {
                    $d = DateTime::createFromFormat('Y-m-d', $vv['date']);
                    echo $d ? $d->format('d.m.Y') : htmlspecialchars($vv['date']);
                } else {
                    echo '—';
                }
                ?>
            </td>
        <?php endif; ?>
        <?php if(in_array('comment',$selected_columns)): ?><td><?=htmlspecialchars($vv['comment'])?></td><?php endif; ?>
        <?php if(in_array('result',$selected_columns)): ?><td><?=htmlspecialchars($vv['result'])?></td><?php endif; ?>
        <?php if(in_array('series',$selected_columns)): ?><td><?=htmlspecialchars($vv['series'])?></td><?php endif; ?>
        <td>
          <a class="small" href="patient.php?id=<?=$id?>&lang=<?=$lang?>&edit_vac=<?=$vv['id']?>"><?=$t['edit']?></a>
          <a class="small danger" href="api.php?action=delete_vac&id=<?=$vv['id']?>&user_id=<?=$id?>" onclick="return confirm('<?=$t['confirm_delete']?>')"><?=$t['delete']?></a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>

  <hr>
  <h2 id="formTitle"><?=$t['add_vac']?></h2>

  <?php
  $edit_vac = null;
  if (!empty($_GET['edit_vac'])) {
      $eid = intval($_GET['edit_vac']);
      $stmt = $db->prepare("SELECT * FROM user_vaccinations WHERE id=? AND user_id=?");
      $stmt->execute([$eid,$id]);
      $edit_vac = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($edit_vac) {
          echo "<script>document.addEventListener('DOMContentLoaded',()=>{document.getElementById('formTitle').innerText='".addslashes($t['edit_vac'])."';});</script>";
      }
  }
  ?>

  <!-- форма добавления/редактирования вакцинации -->
  <form id="addVac" method="post" action="api.php">
    <input type="hidden" name="action" value="<?= $edit_vac ? 'edit_vac' : 'add_vac' ?>">
    <?php if ($edit_vac): ?><input type="hidden" name="vac_id" value="<?=intval($edit_vac['id'])?>"><?php endif; ?>
    <input type="hidden" name="user_id" value="<?=$id?>">
    <label><?=$t['infection']?>:
      <select name="vaccine_id" id="vaccineSelect">
        <option value=""><?=$t['choose']?></option>
        <?php foreach($all_v as $av): ?>
          <option value="<?=$av['id']?>" <?= $edit_vac && $av['id']==$edit_vac['vaccine_id'] ? 'selected':'' ?>><?=htmlspecialchars(getName($av,$lang))?></option>
        <?php endforeach; ?>
        <option value="__new" <?= $edit_vac && $edit_vac['vaccine_id']==0 ? 'selected':'' ?>><?=$t['new']?></option>
      </select>
    </label>

    <div id="newVaccineForm" style="display:none;">
      <p><?=$t['new_infection']?></p>
      <input name="inf_ru" placeholder="Название (RU)">
      <input name="inf_en" placeholder="Название (EN)">
      <input name="inf_es" placeholder="Nombre (ES)">
      <input name="inf_pt" placeholder="Nome (PT)">
    </div>

    <label><?=$t['dose']?>: <input name="dose" value="<?=htmlspecialchars($edit_vac['dose'] ?? '')?>" placeholder="<?=$t['dose_placeholder']?>"></label>
    <label><?=$t['date']?>: <input name="date" type="date" value="<?=htmlspecialchars($edit_vac['date'] ?? '')?>"></label>
    <label><?=$t['comment']?>: <input name="comment" value="<?=htmlspecialchars($edit_vac['comment'] ?? '')?>"></label>
    <label><?=$t['result']?>: <input name="result" value="<?=htmlspecialchars($edit_vac['result'] ?? '')?>"></label>
    <label><?=$t['series_vaccine']?>: <input name="series" value="<?=htmlspecialchars($edit_vac['series'] ?? '')?>"></label>
    <div class="actions">
      <button class="btn" type="submit"><?= $edit_vac ? $t['save'] : $t['add'] ?></button>
      <?php if ($edit_vac): ?><a class="btn ghost" href="patient.php?id=<?=$id?>&lang=<?=$lang?>"><?=$t['cancel']?></a><?php endif; ?>
    </div>
  </form>

  <hr>
  <h3><?=$t['dict_edit']?></h3>
  <form method="post" action="api.php">
    <input type="hidden" name="action" value="edit_vaccine">
    <label><?=$t['infection']?>:
      <select name="vaccine_id" id="vaccineEditSelect">
        <option value=""><?=$t['choose']?></option>
        <?php foreach($all_v as $av): ?>
          <option value="<?=$av['id']?>"><?=htmlspecialchars(getName($av,$lang))?></option>
        <?php endforeach; ?>
        <option value="__new"><?=$t['new']?></option>
      </select>
    </label>
    <div id="vaccineEditForm" style="display:none;">
      <input name="edit_inf_id" type="hidden">
      <input name="inf_ru" placeholder="Название (RU)">
      <input name="inf_en" placeholder="Название (EN)">
      <input name="inf_es" placeholder="Nombre (ES)">
      <input name="inf_pt" placeholder="Nome (PT)">
      <div class="actions"><button class="btn" type="submit"><?=$t['save']?></button></div>
    </div>
  </form>

</div>

<script>
const vsel = document.getElementById('vaccineSelect');
const newV = document.getElementById('newVaccineForm');
if(vsel){ vsel.addEventListener('change', ()=>{ newV.style.display = vsel.value === '__new' ? 'block' : 'none'; }); }

const editSel = document.getElementById('vaccineEditSelect');
const editForm = document.getElementById('vaccineEditForm');
if(editSel){ editSel.addEventListener('change', ()=> {
    const val = editSel.value;
    if (!val) { editForm.style.display='none'; return; }
    if (val === '__new') {
        editForm.style.display='block';
        document.querySelector('input[name="edit_inf_id"]').value = '';
        ['inf_ru','inf_en','inf_es','inf_pt'].forEach(n => document.querySelector('input[name="'+n+'"]').value='');
        return;
    }
    fetch('api.php?action=get_vaccine&id='+val).then(r=>r.json()).then(data=>{
        editForm.style.display='block';
        document.querySelector('input[name="edit_inf_id"]').value = data.id || '';
        document.querySelector('input[name="inf_ru"]').value = data.infection_name_ru || '';
        document.querySelector('input[name="inf_en"]').value = data.infection_name_en || '';
        document.querySelector('input[name="inf_es"]').value = data.infection_name_es || '';
        document.querySelector('input[name="inf_pt"]').value = data.infection_name_pt || '';
    });
});}
</script>

</body>
</html>
