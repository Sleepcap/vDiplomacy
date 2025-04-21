ALTER TABLE wD_Users ADD `forceDesktop` enum('Yes','No') DEFAULT 'No';

UPDATE `wD_vDipMisc` SET `value` = '59' WHERE `name` = 'Version';

