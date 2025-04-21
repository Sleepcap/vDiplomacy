ALTER TABLE wD_Users ADD vpoints mediumint(8) unsigned NOT NULL DEFAULT '1000';

UPDATE wD_Users u 
	SET u.vpoints = (SELECT r.rating FROM wD_Ratings r
		LEFT JOIN wD_Games g ON (g.id = r.gameID)
			JOIN (SELECT MAX(g2.processTime) AS last, r2.userID AS uid FROM wD_Ratings r2
				LEFT JOIN wD_Games g2 ON (g2.id = r2.gameID ) GROUP BY r2.userID) AS tab2 ON 
				(uid = r.userID && last = g.processTime)			
	WHERE r.ratingType='vDip' AND r.userID = u.id);
		
UPDATE `wD_vDipMisc` SET `value` = '52' WHERE `name` = 'Version';

