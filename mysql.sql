ALTER TABLE `mts_company` ADD `carwash` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'мойка' AFTER `is_infected` ;
ALTER TABLE `mts_company` ADD `remont` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Ремонт' AFTER `carwash` ;
ALTER TABLE `mts_company` ADD `tires` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Шиномонтаж' AFTER `remont` ;
ALTER TABLE `mts_company` ADD `disinfection` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'дезинфекция' AFTER `tires` ;