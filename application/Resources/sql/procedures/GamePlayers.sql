/**********************************************
 * ゲームプレイヤー管理テーブル作成プロシージャ登録SQL
 **********************************************/
DROP PROCEDURE IF EXISTS CreateGamePlayers;
DELIMITER //
CREATE PROCEDURE CreateGamePlayers(IN GameId INT(8))
BEGIN
    SET @TableNumber = LPAD(GameId, 8, '0');
    SET @query = CONCAT(
    'CREATE TABLE GamePlayers_', @TableNumber,
    '(
        `GamePlayerId` BIGINT(12) UNSIGNED AUTO_INCREMENT COMMENT \'管理ID\',
        `AliasId` CHAR(16) NOT NULL COMMENT \'IDエリアス\',
        `UserId` BIGINT(12) UNSIGNED COMMENT \'ユーザ管理ID\',
        `PlayerId` VARCHAR(30) NOT NULL COMMENT \'ゲーム側ユーザID\',
        `GameNickname` VARCHAR(30) NOT NULL COMMENT \'ゲーム側ニックネーム\',
        `GroupId` BIGINT(12) UNSIGNED COMMENT \'グループ管理ID\',
        `Authority` INT(3) UNSIGNED DEFAULT 0 COMMENT \'グループ内権限\',
        `LastReadMsgId` INT(8) UNSIGNED DEFAULT 0 COMMENT \'最新起動ボードID\',
        `CreateDate` DATETIME COMMENT \'レコード登録日\',
        `UpdateDate` DATETIME COMMENT \'レコード更新日\',
        `DeleteDate` DATETIME COMMENT \'レコード無効日\',
        `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT \'レコード無効フラグ(0:有効, 1:無効)\',
        PRIMARY KEY (`GamePlayerId`),
        INDEX `IdxUserId` (`UserId`),
        INDEX `IdxGroupId` (`GroupId`),
        INDEX `IdxGroupIdUserId` (`GroupId`,`UserId`),
        INDEX `IdxAliasId` (`AliasId`)
    ) ENGINE=InnoDB COMMENT \'ゲームプレイヤー\';'
    );
    PREPARE stmt FROM @query;
    EXECUTE stmt;
END;
//
DELIMITER ;
