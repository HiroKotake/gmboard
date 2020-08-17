/************************************
 * ゲーム拡張機能管理テーブル
 ************************************/
DROP TABLE IF EXISTS `GameExtend`;
CREATE TABLE `GameExtend`
(
    `GameExtendId` INT(10) UNSIGNED AUTO_INCREMENT COMMENT 'ゲーム拡張機能管理ID',
    `AliasId` CHAR(16) NOT NULL COMMENT 'IDエリアス',
    `GameId` INT(8) UNSIGNED COMMENT 'ゲーム管理ID',
    `ExtendName` VARCHAR(128) COMMENT '機能名',
    `ClassName` VARCHAR(128) COMMENT 'クラスファイル',
    `Description` TEXT COMMENT '説明',
    `Priority` TINYINT(3) UNSIGNED COMMENT '表示順位',
    `CreateDate` DATETIME COMMENT 'レコード登録日',
    `UpdateDate` DATETIME COMMENT 'レコード更新日',
    `DeleteDate` DATETIME COMMENT 'レコード無効日',
    `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'レコード無効フラグ(0:有効, 1:無効)',
    PRIMARY KEY (`GameExtendId`),
    INDEX `IDX_AliasId` (`AliasId`)
) ENGINE=InnoDB COMMENT 'ゲーム拡張機能管理';
