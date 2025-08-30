-- init_test.sql

DROP DATABASE IF EXISTS atecnadvisor_test;
CREATE DATABASE atecnadvisor_test;
GRANT ALL PRIVILEGES ON atecnadvisor_test.* TO 'chris'@'%';
FLUSH PRIVILEGES;