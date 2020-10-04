/*****************************************
 * 登録予約管理テーブル作成プロシージャ登録SQL
 *****************************************/
DROP PROCEDURE IF EXISTS CreateRBooking;
DELIMITER //
CREATE PROCEDURE CreateRBooking(IN GameId INT(8))
BEGIN
    SET @TableNumber = LPAD(GameId, 8, '0');
    SET @query = CONCAT(
    'CREATE TABLE RegistBooking_', @TableNumber,
    '(
        `RegistBookingId` BIGINT(12) UNSIGNED AUTO_INCREMENT COMMENT \'管理ID\',
        `AliasId` CHAR(16) NOT NULL COMMENT \'IDエリアス\',
        `GroupId` BIGINT(12) UNSIGNED COMMENT \'グループ管理ID\',
        `PlayerId` VARCHAR(30) NOT NULL COMMENT \'ゲーム側ユーザID\',
        `AuthCode` VARCHAR(30) NOT NULL COMMENT \'認証確認用コード\',
        `GameNickname` VARCHAR(30) NOT NULL COMMENT \'ゲーム側ニックネーム\',
        `UserId` BIGINT(12) UNSIGNED COMMENT \'ユーザ管理ID\',
        `Type` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT \'レコードタイプ(0:加入申請, 1:招待)\',
        `Registed` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT \'登録済みフラグ(0:未登録, 1:登録済)\',
        `Approved` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT \'承認済みフラグ(0:未承認, 1:承認済)\',
        `CreateDate` DATETIME COMMENT \'レコード登録日\',
        `UpdateDate` DATETIME COMMENT \'レコード更新日\',
        `DeleteDate` DATETIME COMMENT \'レコード無効日\',
        `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT \'レコード無効フラグ(0:有効, 1:無効)\',
        PRIMARY KEY (`RegistBookingId`),
        INDEX `IdxPlayer` (`PlayerId`),
        INDEX `IdxGroupId` (`GroupId`),
        INDEX `IdxAliasId` (`AliasId`)
    ) ENGINE=InnoDB COMMENT \'登録予約情報\''
    );
    PREPARE stmt FROM @query;
    EXECUTE stmt;
END;
//
DELIMITER ;
