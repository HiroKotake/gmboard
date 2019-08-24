DROP TABLE `GameInfos`;
CREATE TABLE `GameInfos`
(
    `GameId` INT(8) UNSIGNED AUTO_INCREMENT COMMENT 'ゲーム管理ID',
    `Name` TEXT COMMENT 'ゲーム名',
    `Description` TEXT COMMENT '説明',
    `CerateDate` DATETIME COMMENT 'レコード登録日',
    `UpdateDate` DATETIME COMMENT 'レコード更新日',
    `DeleteDate` DATETIME COMMENT 'レコード無効日',
    `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'レコード無効フラグ(0:有効, 1:無効)',
    PRIMARY KEY (`GameId`)
) ENGINE=InnoDB COMMENT 'ゲーム情報';

INSERT INTO `GameInfos` (`Name`, `Description`) VALUE ("プリンセスコネクト Re:dive", "サイゲームが送るシネマティックRPG");
