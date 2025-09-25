<?php
// db.php - central DB connection and initialization
$dbfile = __DIR__ . '/data.sqlite';
$init_sql = __DIR__ . '/init_db_sql.sql';
$db = new PDO('sqlite:'.$dbfile);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create schema if not exists
$sql = file_get_contents($init_sql);
$db->exec($sql);

// Seed default vaccines only if table empty
$cnt = $db->query("SELECT COUNT(*) FROM vaccines")->fetchColumn();
if (!$cnt) {
    $seed_sql = file_get_contents(__DIR__ . '/init_seed_sql.sql');
    $db->exec($seed_sql);
}
