-- init_seed_sql.sql - seed data executed only if vaccines table empty
INSERT INTO vaccines (infection_name_ru, infection_name_en, infection_name_es, infection_name_pt) VALUES
-- Туберкулёз
('БЦЖ (Туберкулёз)','BCG (Tuberculosis)','BCG (Tuberculosis)','BCG (Tuberculose)'),

-- Гепатиты
('Гепатит B (HepB)','HepB (Hepatitis B)','HepB (Hepatitis B)','HepB (Hepatite B)'),
('Гепатит A (HepA)','HepA (Hepatitis A)','HepA (Hepatitis A)','HepA (Hepatite A)'),

-- Коклюш – Дифтерия – Столбняк
('АКДС-а (Ацеллюлярная КДС: Коклюш – Дифтерия – Столбняк)','Tdap (Acellular Pertussis – Diphtheria – Tetanus)','dTpa Triple Bacteriana Acelular (Tos ferina acelular – Difteria – Tétanos)','dTpa Tríplice bacteriana acelular (Coqueluche acelular – Difteria – Tétano)'),
('АКДС (Коклюш – Дифтерия – Столбняк, цельноклеточная)','DTwP / DTP (Whole-cell Pertussis – Diphtheria – Tetanus)','DTP / DTwP Triple Bacteriana Celular (Tos ferina – Difteria – Tétanos, celular)','DTP / DTwP Tríplice bacteriana celular (Coqueluche – Difteria – Tétano, celular)'),
('АДС (Дифтерия – Столбняк)','Td (Diphtheria – Tetanus)','dT (Difteria – Tétanos)','dT (Difteria – Tétano)'),
('АДС-М (Дифтерия – Столбняк)','Td (Diphtheria – Tetanus, adult booster, reduced dose)','dT Doble Adultos (Difteria – Tétanos)','dT (Difteria – Tétano, dose para adultos, dose reduzida)'),
('Пятивалентная (Pentavalent: DTP + Hib + HepB)','Pentavalent (DTP + Hib + HepB)','Pentavalente (DTP + Hib + HepB)','Pentavalente (DTP + Hib + HepB)'),

-- Полиомиелит
('Полиомиелит (ИПВ – инактивированная)','IPV (Inactivated Poliovirus Vaccine)','IPV (Vacuna antipoliomielítica inactivada)','IPV (Vacina inativada contra poliomielite)'),
('Полиомиелит (ОПВ – оральная)','OPV (Oral Poliovirus Vaccine)','OPV (Vacuna antipoliomielítica oral)','OPV (Vacina oral contra poliomielite)'),

-- Корь / Краснуха / Паротит
('Корь – Краснуха','MR (Measles – Rubella)','Doble viral SR (Sarampión – Rubéola)','Dupla viral SR (Sarampo – Rubéola)'),
('Корь – Паротит – Краснуха','MMR (Measles – Mumps – Rubella)','Triple viral SRP (Sarampión – Paperas – Rubéola)','Tríplice viral SRP (Sarampo – Caxumba – Rubéola)'),
('Корь','Measles','Sarampión','Sarampo'),
('Паротит','Mumps','Paperas','Caxumba'),
('Краснуха','Rubella','Rubéola','Rubéola'),

-- Другие вирусные
('Ветряная оспа (Ветрянка)','Varicella (Chickenpox)','Varicela','Varicela'),
('Ротавирус','Rotavirus','Rotavirus','Rotavírus'),
('Грипп','Influenza','Gripe','Gripe'),
('Коронавирус (COVID-19)','Coronavirus (COVID-19)','Coronavirus (COVID-19)','Coronavírus (COVID-19)'),
('ВПЧ (Папилломавирус)','HPV (Human Papillomavirus)','VPH (Virus del papiloma humano)','HPV (Papilomavírus humano)'),

-- Бактериальные
('Пневмококк (Пневмококковая инфекция, конъюгированная 13-валентная)','PCV13 (Pneumococcal conjugate, 13-valent)','PCV13 (Neumocócica conjugada, 13-valente)','PCV13 (Pneumocócica conjugada, 13-valente)'),
('Менингококк (Менингококковая инфекция, серогруппы A, C, W, Y)','MenACWY (Meningococcal, serogroups A, C, W, Y)','MenACWY (Meningocócica, serogrupos A, C, W, Y)','MenACWY (Meningocócica, sorogrupos A, C, W, Y)'),
('Менингококк B','MenB (Meningococcal B)','MenB (Meningocócica B)','MenB (Meningocócica B)'),
('Гемофильная инфекция Hib','Hib (Haemophilus influenzae type b)','Hib (Haemophilus influenzae tipo b)','Hib (Haemophilus influenzae tipo b)'),

-- Тропические и региональные
('Жёлтая лихорадка','Yellow fever','Fiebre amarilla','Febre amarela'),
('Аргентинская геморрагическая лихорадка (Junín)','Argentine hemorrhagic fever (Junín)','Fiebre hemorrágica argentina (Junín)','Febre hemorrágica argentina (Junín)')

-- Тест, не вакцина
('Реакция Манту (туберкулиновая проба)','Mantoux reaction (tuberculin test)','Reacción de Mantoux (prueba)','Reação de Mantoux (teste)'),
