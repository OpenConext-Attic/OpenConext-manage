-- Add table to store who gets which report mailed
CREATE TABLE IF NOT EXISTS `report_recipients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `report` VARCHAR(50) NOT NULL,
  `email`  VARCHAR(100) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY `report_recipient` (report, email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
