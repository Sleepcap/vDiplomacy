ALTER TABLE wD_Users ADD `buttonWidth` enum('auto','small','large') NOT NULL DEFAULT 'auto';

UPDATE `wD_vDipMisc` SET `value` = '61' WHERE `name` = 'Version';

