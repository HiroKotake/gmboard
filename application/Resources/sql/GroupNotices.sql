/************************************************
 * グループ用告知管理作成プロシージャ登録SQL
 ************************************************/
DROP PROCEDURE IF EXISTS CreateGroupNotice;
DELIMITER //
CREATE PROCEDURE CreateGroupNotice(IN GameId INT(8), IN GroupId BIGINT(12))
BEGIN
    SET @GameIndex = LPAD(GameId, 8, '0');
    SET @BoardNumber = LPAD(GroupId, 12, '0');
    SET @query = CONCAT(
    'CREATE TABLE GNotice_', @GameIndex, '_', @BoardNumber,
    '(
        `NoticeId` INT(8) UNSIGNED AUTO_INCREMENT COMMENT \'管理ID\',
        `GamePlayerId` BIGINT(12) UNSIGNED NOT NULL COMMENT \'ユーザ管理ID\',
        `Priority` INT(4) UNSIGNED DEFAULT 100 COMMENT \'優先度\',
        `Message` TEXT COMMENT \'メッセージテキスト\',
        `ShowStartDateTime` DATETIME COMMENT \'表示開始日時\',
        `ShowEndDateTime` DATETIME COMMENT \'表示終了日時\',
        `Showable` TINYINT(1) UNSIGNED DEFAULT 1 COMMENT \'メッセージ表示フラグ(0:無効, 1:有効)\',
        `CreateDate` DATETIME COMMENT \'レコード登録日\',
        `UpdateDate` DATETIME COMMENT \'レコード更新日\',
        `DeleteDate` DATETIME COMMENT \'レコード無効日\',
        `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT \'レコード無効フラグ(0:有効, 1:無効)\',
        PRIMARY KEY (`NoticeId`)
    ) ENGINE=InnoDB COMMENT \'グループ告知\''
    );
    PREPARE stmt FROM @query;
    EXECUTE stmt;
END;
//
DELIMITER ;
