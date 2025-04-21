-- RR sanctions have been updated and temp bans reduced drastically. Lift all currently active temp ban sanctions.
UPDATE `wD_Users` SET `tempBan` = NULL WHERE `tempBanReason` = 'System';

UPDATE `wD_vDipMisc` SET `value` = '69' WHERE `name` = 'Version';
