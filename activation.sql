CREATE TABLE IF NOT EXISTS `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mac_address` varchar(17) NOT NULL,
  `activation_code` varchar(32) NOT NULL,
  `is_activated` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `activated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mac_address` (`mac_address`),
  UNIQUE KEY `activation_code` (`activation_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; 