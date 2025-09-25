# VaccinationTracker
Простой веб-приложение на PHP для ведения учёта прививок пользователей с поддержкой фильтров, сортировки и нескольких языков.


Веб-приложение на PHP для ведения учёта прививок пользователей. Позволяет:

- Сохранять информацию о пользователях и их вакцинациях
- Фильтровать по инфекциям
- Сортировать записи по дате и алфавиту
- Выбирать, какие столбцы отображать
- Поддержка нескольких языков: русский, английский, испанский, португальский

## Пример
  https://aligatorru.github.io/VaccinationTracker/exemple/pacients.html

## Установка

1. Клонируйте репозиторий на сервер:
   ```bash
   git clone https://github.com/Aligatorru/VaccinationTracker.git
   ```

2. Настройте базу данных MySQL и импортируйте схему (если есть `db.sql`):

   ```sql
   CREATE DATABASE vaccinations;
   ```
3. Настройте подключение в `db.php`:

   ```php
   <?php
   $db = new PDO('mysql:host=localhost;dbname=vaccinations;charset=utf8', 'username', 'password');
   ```
4. Загрузите файлы на сервер (например, в `public_html`).
5. Откройте в браузере `http://your-server/patient.php?id=1`.

## Лицензия

MIT License

## Теги

PHP, MySQL, vaccination, medical, multilingual
