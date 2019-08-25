/************************************
 * ゲームプレイヤー管理テーブル
 ************************************/
DROP TABLE IF EXISTS `GamePlayers`;
CREATE TABLE `GamePlayers`
(
    `GamePlayerId` BIGINT(12) UNSIGNED AUTO_INCREMENT COMMENT '管理ID',
    `UserId` BIGINT(12) UNSIGNED COMMENT 'ユーザ管理ID',
    `GameId` INT(8) UNSIGNED COMMENT 'ゲーム管理ID',
    `PlayerId` VARCHAR(30) NOT NULL COMMENT 'ゲーム側ユーザID',
    `GameNickname` VARCHAR(30) NOT NULL COMMENT 'ゲーム側ニックネーム',
    `GroupId` BIGINT(12) UNSIGNED COMMENT 'グループ管理ID',
    `Authority` INT(3) UNSIGNED DEFAULT 0 COMMENT 'グループ内権限',
    `CerateDate` DATETIME COMMENT 'レコード登録日',
    `UpdateDate` DATETIME COMMENT 'レコード更新日',
    `DeleteDate` DATETIME COMMENT 'レコード無効日',
    `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'レコード無効フラグ(0:有効, 1:無効)',
    PRIMARY KEY (`GamePlayerId`),
    INDEX `IdxUserId` (`UserId`),
    INDEX `IdxGroupId` (`GroupId`)
) ENGINE=InnoDB COMMENT 'ゲームプレイヤー';
