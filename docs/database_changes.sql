ALTER TABLE `a_log_food` ADD `total_carbs` DOUBLE NULL DEFAULT NULL;
ALTER TABLE `a_template` ADD `carbs_per_100` DOUBLE NULL DEFAULT NULL;

DROP VIEW IF EXISTS `v_template`;
CREATE VIEW `v_template` AS

    SELECT

        tem.a_template_id,
        tem.account_id,
        tem.title,
        tem.calories_per_100,
        tem.fat_per_100,
        tem.protein_per_100,
        tem.carbs_per_100,
        tem.portion_size,
        tem.image AS 'image',
        img.folder AS 'image_folder',
        img.name AS 'image_name',
        img.mime AS 'image_mime',
        img.account_id AS 'image_account_id'


    FROM a_template AS tem
    LEFT JOIN acc_image AS img ON tem.image = img.acc_image_id;