/************************************************
 * ユーザ用メッセージボード作成プロシージャ登録SQL
 ************************************************/
DROP PROCEDURE IF EXISTS CreateUserBoard;
DELIMITER //
CREATE PROCEDURE CreateUserBoard(IN UserId BIGINT(12))
BEGIN
    SET @BoardNumber = LPAD(UserId, 12, '0');
    SET @query = CONCAT(
    'CREATE TABLE UBoard_', @BoardNumber,
    '(
        `MessageId` INT(8) UNSIGNED AUTO_INCREMENT COMMENT \'メッセージ管理ID\',
        `FromUserId` BIGINT(12) NOT NULL COMMENT \'送信者ユーザID\',
        `FromGroupId` BIGINT(12) COMMENT \'送信者グループID\',
        `message` TEXT COMMENT \'メッセージテキスト\',
        `AlreadyRead` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT \'メッセージ既読フラグ(0:無効, 1:有効)\',
        `CreateDate` DATETIME COMMENT \'レコード登録日\',
        `UpdateDate` DATETIME COMMENT \'レコード更新日\',
        `DeleteDate` DATETIME COMMENT \'レコード無効日\',
        `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT \'レコード無効フラグ(0:有効, 1:無効)\',
        PRIMARY KEY (`MessageId`)
    ) ENGINE=InnoDB COMMENT \'ユーザメッセージボード\''
    );
    PREPARE stmt FROM @query;
    EXECUTE stmt;
END;
//
DELIMITER ;
