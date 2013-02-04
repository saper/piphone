
-- Adds token into the user table

ALTER TABLE user
      ADD (`token` var_char(16));
