/*****************************************
 * グループ管理テーブル作成プロシージャ登録SQL
 *****************************************/
DROP PROCEDURE IF EXISTS CreateGroup;
DELIMITER //
CREATE PROCEDURE CreateGroup(IN GameId INT(8))
BEGIN
    SET @TableNumber = LPAD(GameId, 8, '0');
    SET @query = CONCAT(
    'CREATE TABLE Groups_', @TableNumber,
    '(
        `GroupId` INT(8) UNSIGNED AUTO_INCREMENT COMMENT \'グループ管理ID\',
        `AliasId` CHAR(16) NOT NULL COMMENT \'IDエリアス\',
        `GroupName` VARCHAR(60) COMMENT \'グループ名\',
        `Leader` BIGINT(12) UNSIGNED COMMENT \'リーダーのユーザID\',
        `Description` TEXT COMMENT \'説明\',
        `CreateDate` DATETIME COMMENT \'レコード登録日\',
        `UpdateDate` DATETIME COMMENT \'レコード更新日\',
        `DeleteDate` DATETIME COMMENT \'レコード無効日\',
        `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT \'レコード無効フラグ(0:有効, 1:無効)\',
        PRIMARY KEY (`GroupId`),
        INDEX `IdxAliasId` (`AliasId`)
    ) ENGINE=InnoDB COMMENT \'グループ情報\''
    );
    PREPARE stmt FROM @query;
    EXECUTE stmt;
END;
//
DELIMITER ;
