DROP TRIGGER IF EXISTS `password_tmp_BFRINSERT`;
DROP TRIGGER IF EXISTS `password_tmp_BFRUPDATE`;

CREATE TRIGGER `password_tmp_BFRINSERT` BEFORE INSERT ON `user` FOR EACH ROW
    BEGIN
        SET NEW.password_hash = MD5(NEW.password_tmp);
        SET NEW.password_tmp = '';
    END;

CREATE TRIGGER `password_tmp_BFRUPDATE` BEFORE UPDATE ON `user` FOR EACH ROW
BEGIN
    IF NEW.password_tmp != '' THEN
        SET NEW.password_hash = MD5(NEW.password_tmp);
        SET NEW.password_tmp = '';
    END IF;
END;