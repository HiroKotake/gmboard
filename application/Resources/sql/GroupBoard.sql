/************************************************
 * グループ用チャットボード作成プロシージャ登録SQL
 ************************************************/
DROP PROCEDURE IF EXISTS CreateGroupBoard;
DELIMITER //
CREATE PROCEDURE CreateGroupBoard(IN GameId INT(8), IN GroupId BIGINT(12))
BEGIN
    SET @GameIndex = LPAD(GameId, 8, '0');
    SET @BoardNumber = LPAD(GroupId, 12, '0');
    SET @query = CONCAT(
    'CREATE TABLE GBoard_', @GameIndex, '_', @BoardNumber,
    '(
        `MessageId` INT(8) UNSIGNED AUTO_INCREMENT COMMENT \'管理ID\',
        `GamePlayerId` BIGINT(12) UNSIGNED NOT NULL COMMENT \'ユーザ管理ID\',
        `Message` TEXT COMMENT \'メッセージテキスト\',
        `Showable` TINYINT(1) UNSIGNED DEFAULT 1 COMMENT \'メッセージ表示フラグ(0:無効, 1:有効)\',
        `CreateDate` DATETIME COMMENT \'レコード登録日\',
        `UpdateDate` DATETIME COMMENT \'レコード更新日\',
        `DeleteDate` DATETIME COMMENT \'レコード無効日\',
        `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT \'レコード無効フラグ(0:有効, 1:無効)\',
        PRIMARY KEY (`MessageId`)
    ) ENGINE=InnoDB COMMENT \'グループメッセージボード\''
    );
    PREPARE stmt FROM @query;
    EXECUTE stmt;
END;
//
DELIMITER ;
