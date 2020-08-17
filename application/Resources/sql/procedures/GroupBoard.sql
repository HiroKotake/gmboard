/************************************************
 * グループ用チャットボード作成プロシージャ登録SQL
 ************************************************/
DROP PROCEDURE IF EXISTS CreateGroupBoard;
DELIMITER //
CREATE PROCEDURE CreateGroupBoard(IN GameId INT(8), IN GroupId INT(8))
BEGIN
    SET @GameIndex = LPAD(GameId, 8, '0');
    SET @BoardNumber = LPAD(GroupId, 8, '0');
    SET @query = CONCAT(
    'CREATE TABLE GBoard_', @GameIndex, '_', @BoardNumber,
    '(
        `GBoardMsgId` INT(8) UNSIGNED AUTO_INCREMENT COMMENT \'管理ID\',
        `AliasId` CHAR(16) NOT NULL COMMENT \'IDエリアス\',
        `UserId` BIGINT(12) NOT NULL COMMENT \'ユーザID\',
        `GamePlayerId` BIGINT(12) UNSIGNED NOT NULL COMMENT \'ゲームユーザ管理ID\',
        `GameNickname` VARCHAR(30) COMMENT \'送信者ニックネーム\',
        `ParentMsgId` INT(8) UNSIGNED DEFAULT 0 COMMENT \'基底ID\',
        `Idiom` INT(6) UNSIGNED COMMENT \'慣用句コード\',
        `Message` TEXT COMMENT \'メッセージテキスト\',
        `Images` TEXT COMMENT \'イメージのファイル名のJSON配列\',
        `Data` MEDIUMBLOB NULL COMMENT \'各種データ保持用\',
        `Showable` TINYINT(1) UNSIGNED DEFAULT 1 COMMENT \'メッセージ表示フラグ(0:無効, 1:有効)\',
        `CreateDate` DATETIME COMMENT \'レコード登録日\',
        `UpdateDate` DATETIME COMMENT \'レコード更新日\',
        `DeleteDate` DATETIME COMMENT \'レコード無効日\',
        `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT \'レコード無効フラグ(0:有効, 1:無効)\',
        PRIMARY KEY (`GBoardMsgId`),
        INDEX `IdxAliasId` (`AliasId`),
        INDEX `IdxParentMsgId` (`ParentMsgId`)
    ) ENGINE=InnoDB COMMENT \'グループメッセージボード\''
    );
    PREPARE stmt FROM @query;
    EXECUTE stmt;
END;
//
DELIMITER ;
