/************************************************
 * グループ用チャットボード作成プロシージャ登録SQL
 ************************************************/
DROP PROCEDURE IF EXISTS CreateGroupBoard;
DELIMITER //
CREATE PROCEDURE CreateGroupBoard(IN GroupId BIGINT(12))
BEGIN
    SET @BoardNumber = LPAD(GroupId, 12, '0');
    SET @query = CONCAT(
    'CREATE TABLE GBoard_', @BoardNumber,
    '(
        `id` INT(8) UNSIGNED AUTO_INCREMENT COMMENT \'管理ID\',
        `UserId` BIGINT(12) UNSIGNED NOT NULL COMMENT \'ユーザ管理ID\',
        `message` TEXT COMMENT \'メッセージテキスト\',
        `Showable` TINYINT(1) UNSIGNED DEFAULT 1 COMMENT \'メッセージ表示フラグ(0:無効, 1:有効)\',
        `CerateDate` DATETIME COMMENT \'レコード登録日\',
        `UpdateDate` DATETIME COMMENT \'レコード更新日\',
        `DeleteDate` DATETIME COMMENT \'レコード無効日\',
        `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT \'レコード無効フラグ(0:有効, 1:無効)\',
        PRIMARY KEY (`Id`)
    ) ENGINE=InnoDB COMMENT \'グループメッセージボード\''
    );
    PREPARE stmt FROM @query;
    EXECUTE stmt;
END;
//
DELIMITER ;
