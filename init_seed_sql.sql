-- init_seed_sql.sql - seed data executed only if vaccines table empty
INSERT INTO vaccines (infection_name_ru, infection_name_en, infection_name_es, infection_name_pt) VALUES
('Туберкулёз (БЦЖ)','Tuberculosis (BCG)','Tuberculosis (BCG)','Tuberculose (BCG)'),
('Коклюш – Дифтерия – Столбняк (АКДС/DTaP/dTpa)','Pertussis – Diphtheria – Tetanus (DTP/DTaP/dTpa)','Tos ferina – Difteria – Tétanos (DTP/DTaP/dTpa)','Coqueluche – Difteria – Tétano (DTP/DTaP/dTpa)'),
('Дифтерия – Столбняк (АДС/Td)','Diphtheria – Tetanus (Td)','Difteria – Tétanos (Td)','Difteria – Tétano (Td)'),
('Полиомиелит','Poliomyelitis (Polio)','Poliomielitis (Polio)','Poliomielite (Polio)'),

-- Корь / Краснуха / Паротит по отдельности
('Корь','Measles','Sarampión','Sarampo'),
('Паротит','Mumps','Paperas','Caxumba'),
('Краснуха','Rubella','Rubéola','Rubéola'),

-- Комбинированные вакцины
('Корь – Краснуха (MR)','Measles – Rubella (MR)','Sarampión – Rubéola (MR)','Sarampo – Rubéola (MR)'),
('Корь – Паротит – Краснуха (MMR, Тройная)','Measles – Mumps – Rubella (MMR)','Sarampión – Paperas – Rubéola (Triple vírica)','Sarampo – Caxumba – Rubéola (Tríplice viral)'),

-- Остальные
('Гепатит B','Hepatitis B','Hepatitis B','Hepatite B'),
('Гепатит A','Hepatitis A','Hepatitis A','Hepatite A'),
('Ветряная оспа (ветрянка)','Chickenpox (Varicella)','Varicela','Varicela'),
('Жёлтая лихорадка','Yellow fever','Fiebre amarilla','Febre amarela'),
('Ротавирус','Rotavirus','Rotavirus','Rotavírus'),
('Пневмококковая инфекция','Pneumococcal','Neumocócica','Pneumocócica'),
('Менингококковая инфекция','Meningococcal','Meningocócica','Meningocócica'),
('ВПЧ (папилломавирус)','HPV (Human Papillomavirus)','VPH (Virus del papiloma humano)','HPV (Papilomavírus humano)'),
('Грипп','Influenza','Gripe','Gripe'),
('Коронавирус (COVID-19)','Coronavirus (COVID-19)','Coronavirus (COVID-19)','Coronavírus (COVID-19)'),
('Реакция Манту (тест, не вакцина)','Mantoux reaction (test)','Reacción de Mantoux (prueba)','Reação de Mantoux (teste)');
