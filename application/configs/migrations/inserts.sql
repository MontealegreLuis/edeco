INSERT INTO `resource` (`name`) VALUES
('admin_error'),
('admin_city'),
('admin_google-map'),
('admin_excel'),
('admin_help'),
('admin_image'),
('default_property');

UPDATE `permission` SET `name` = 'default_property' WHERE `name` = 'default_javascript';
UPDATE `permission` SET `name` = 'list' WHERE `name` = 'index' AND `roleName` = 'client' AND `resourceName` = 'admin_project';

INSERT INTO `permission` (roleName, resourceName, `name`) VALUES
('guest', 'admin_index', 'authenticate'),
('guest', 'admin_index', 'login'),
('guest', 'default_property', '*'),
('guest', 'admin_error', '*'),
('client', 'admin_project', 'show');