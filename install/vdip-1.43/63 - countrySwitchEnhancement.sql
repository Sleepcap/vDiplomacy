ALTER TABLE wD_CountrySwitch ADD `hasWatched` enum('Yes','No') NOT NULL DEFAULT 'No';

UPDATE `wD_vDipMisc` SET `value` = '63' WHERE `name` = 'Version';

