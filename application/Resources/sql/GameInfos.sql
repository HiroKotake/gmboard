/************************************
 * ゲーム管理テーブル
 ************************************/
DROP TABLE IF EXISTS `GameInfos`;
CREATE TABLE `GameInfos`
(
    `GameId` INT(8) UNSIGNED AUTO_INCREMENT COMMENT 'ゲーム管理ID',
    `Genre` TINYINT(3) UNSIGNED COMMENT 'ジャンル',
    `Name` TEXT COMMENT 'ゲーム名',
    `Description` TEXT COMMENT '説明',
    `CreateDate` DATETIME COMMENT 'レコード登録日',
    `UpdateDate` DATETIME COMMENT 'レコード更新日',
    `DeleteDate` DATETIME COMMENT 'レコード無効日',
    `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'レコード無効フラグ(0:有効, 1:無効)',
    PRIMARY KEY (`GameId`)
) ENGINE=InnoDB COMMENT 'ゲーム情報';

INSERT INTO `GameInfos` (`Name`, `Genre`, `Description`) VALUE ("プリンセスコネクト Re:dive", 1, "サイゲームが送るシネマティックRPG");
