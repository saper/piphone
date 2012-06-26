
-- Adds metadata for meps (picture, birth, groups etc.

ALTER TABLE lists 
      ADD (`meta` TEXT NOT NULL DEFAULT '');

