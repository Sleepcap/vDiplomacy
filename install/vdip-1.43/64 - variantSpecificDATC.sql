ALTER TABLE `wD_DATC` CHANGE `testID` `testID` INT NOT NULL;
ALTER TABLE `wD_DATCOrders` CHANGE `testID` `testID` INT NOT NULL;

DELETE FROM `wD_DATC` WHERE `testID` >= 1900 AND `testID` <= 1933;
DELETE FROM `wD_DATCOrders` WHERE `testID` >= 1900 AND `testID` <= 1933;

UPDATE `wD_vDipMisc` SET `value` = '64' WHERE `name` = 'Version';
