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
        `UBoardMsgId` INT(8) UNSIGNED AUTO_INCREMENT COMMENT \'メッセージ管理ID\',
        `AliasId` CHAR(16) NOT NULL COMMENT \'IDエリアス\',
        `FromUserId` BIGINT(12) NOT NULL COMMENT \'送信者ユーザID\',
        `FromUserName` VARCHAR(30) COMMENT \'送信者ニックネーム\',
        `FromGroupId` BIGINT(12) COMMENT \'送信者グループID\',
        `FromGroupName` VARCHAR(60) COMMENT \'送信者ニックネーム\',
        `Idiom` INT(6) UNSIGNED COMMENT \'慣用句コード\',
        `Message` TEXT COMMENT \'メッセージテキスト\',
        `Images` TEXT COMMENT \'イメージのファイル名のJSON配列\',
        `Data` MEDIUMBLOB NULL COMMENT \'各種データ保持用\',
        `AlreadyRead` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT \'メッセージ既読フラグ(0:無効, 1:有効)\',
        `NoReplay` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT \'返信不要フラグ(0:通常, 1:不要)\',
        `CreateDate` DATETIME COMMENT \'レコード登録日\',
        `UpdateDate` DATETIME COMMENT \'レコード更新日\',
        `DeleteDate` DATETIME COMMENT \'レコード無効日\',
        `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT \'レコード無効フラグ(0:有効, 1:無効)\',
        PRIMARY KEY (`UBoardMsgId`),
        INDEX `IdxAliasId` (`AliasId`)
    ) ENGINE=InnoDB COMMENT \'ユーザメッセージボード\''
    );
    PREPARE stmt FROM @query;
    EXECUTE stmt;
END;
//
DELIMITER ;
