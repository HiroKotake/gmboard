/************************************
 * ゲーム管理テーブル
 ************************************/
DROP TABLE IF EXISTS `GameInfos`;
CREATE TABLE `GameInfos`
(
    `GameId` INT(8) UNSIGNED AUTO_INCREMENT COMMENT 'ゲーム管理ID',
    `AliasId` CHAR(16) NOT NULL COMMENT 'IDエリアス',
    `Genre` TINYINT(3) UNSIGNED COMMENT 'ジャンル',
    `Name` TEXT COMMENT 'ゲーム名',
    `Description` TEXT COMMENT '説明',
    `GroupTitle` VARCHAR(16) COMMENT 'グループ名',
    `CreateDate` DATETIME COMMENT 'レコード登録日',
    `UpdateDate` DATETIME COMMENT 'レコード更新日',
    `DeleteDate` DATETIME COMMENT 'レコード無効日',
    `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'レコード無効フラグ(0:有効, 1:無効)',
    PRIMARY KEY (`GameId`),
    INDEX `IdxAliasId` (`AliasId`)
) ENGINE=InnoDB COMMENT 'ゲーム情報';
