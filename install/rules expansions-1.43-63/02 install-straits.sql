/**
 * Author:  Alex Ronke
 * Created: May 27, 2017
 */
ALTER TABLE `wD_Territories` CHANGE COLUMN `type` `type` ENUM('Sea', 'Land', 'Coast', 'Strait') NOT NULL ;

