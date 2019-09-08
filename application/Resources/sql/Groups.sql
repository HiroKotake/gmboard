/************************************
 * グループ管理テーブル
 ************************************/
DROP TABLE IF EXISTS `Groups`;
CREATE TABLE `Groups`
(
    `GroupId` INT(8) UNSIGNED AUTO_INCREMENT COMMENT 'グループ管理ID',
    `GameId` INT(8) UNSIGNED COMMENT 'ゲーム管理ID',
    `GroupName` VARCHAR(60) COMMENT 'グループ名',
    `Leader` BIGINT(12) UNSIGNED COMMENT 'リーダーのユーザID',
    `Description` TEXT COMMENT '説明',
    `CerateDate` DATETIME COMMENT 'レコード登録日',
    `UpdateDate` DATETIME COMMENT 'レコード更新日',
    `DeleteDate` DATETIME COMMENT 'レコード無効日',
    `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'レコード無効フラグ(0:有効, 1:無効)',
    PRIMARY KEY (`GroupId`)
) ENGINE=InnoDB COMMENT 'グループ情報';
