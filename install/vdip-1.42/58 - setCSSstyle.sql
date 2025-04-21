ALTER TABLE wD_Users ADD `cssStyle` enum('vDip','webDip') DEFAULT 'vDip';

UPDATE `wD_vDipMisc` SET `value` = '58' WHERE `name` = 'Version';

