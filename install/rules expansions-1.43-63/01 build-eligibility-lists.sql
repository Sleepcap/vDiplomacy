/**
 * Author:  Alex Ronke
 * Created: May 27, 2017
 */

ALTER TABLE `wD_Territories` ADD COLUMN `buildEligibilityFlags` INT(7) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 AFTER `coastParentID`;
