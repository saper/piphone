
-- Adds metadata for meps (picture, birth, groups etc.

ALTER TABLE lists 
      ADD (`scores` INT NOT NULL DEFAULT '0',
	`pond_scores` INT NOT NULL DEFAULT '0');

