-- init_db_sql.sql - schema only, safe to run multiple times
PRAGMA foreign_keys = ON;
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name_ru TEXT,
    name_lat TEXT,
    birthdate TEXT,
    sex TEXT
);

CREATE TABLE IF NOT EXISTS vaccines (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    infection_name_ru TEXT,
    infection_name_en TEXT,
    infection_name_es TEXT,
    infection_name_pt TEXT
);

CREATE TABLE IF NOT EXISTS user_vaccinations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    vaccine_id INTEGER,
    dose TEXT,
    date TEXT,
    comment TEXT,
    result TEXT,
    series TEXT,
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY(vaccine_id) REFERENCES vaccines(id) ON DELETE SET NULL
);
