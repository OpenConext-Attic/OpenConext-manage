-- Add table to define a report.
-- Add table to store who gets which report mailed
CREATE TABLE IF NOT EXISTS `report` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `class` VARCHAR(255) NOT NULL,
  `method`  VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY `class_method` (`class`, `method`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `report_recipient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `report_id` INT(11) NOT NULL,
  `email`  VARCHAR(100) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY `report_recipient` (`report_id`, `email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

