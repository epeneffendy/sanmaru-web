CREATE DATABASE IF NOT EXISTS `scl_sanmarudb_production`;
CREATE DATABASE IF NOT EXISTS `scl_sanmaruerpdb_production`;
GRANT ALL PRIVILEGES ON `scl_sanmarudb_production`.* TO 'root'@'%';
GRANT ALL PRIVILEGES ON `scl_sanmaruerpdb_production`.* TO 'root'@'%';
FLUSH PRIVILEGES;
