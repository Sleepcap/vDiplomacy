ALTER TABLE wD_NMRs ADD COLUMN ignoreNMR BOOLEAN DEFAULT 0;

UPDATE `wD_vDipMisc` SET `value` = '56' WHERE `name` = 'Version'
