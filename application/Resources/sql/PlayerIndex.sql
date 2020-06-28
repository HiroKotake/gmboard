/************************************
 * プレイヤー管理テーブル
 * ： ユーザの登録しているゲームを管理する
 ************************************/
DROP TABLE IF EXISTS `PlayerIndex`;
CREATE TABLE `PlayerIndex`
(
    `PlayerIndexId` INT(8) UNSIGNED AUTO_INCREMENT COMMENT 'プレイヤー管理ID',
    `UserId` BIGINT(12) UNSIGNED COMMENT 'ユーザ管理ID',
    `GameId` INT(8) UNSIGNED COMMENT 'ゲーム管理ID',
    `CreateDate` DATETIME COMMENT 'レコード登録日',
    `UpdateDate` DATETIME COMMENT 'レコード更新日',
    `DeleteDate` DATETIME COMMENT 'レコード無効日',
    `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'レコード無効フラグ(0:有効, 1:無効)',
    PRIMARY KEY (`PlayerIndexId`)
) ENGINE=InnoDB COMMENT 'プレイヤー管理';
