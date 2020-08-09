DROP TABLE `SystemCommon`;
CREATE TABLE `SystemCommon`
(
    `SystemCommonId` INT(8) UNSIGNED AUTO_INCREMENT COMMENT '管理ID',
    `Name` VARCHAR(60) COMMENT 'キー名',
    `Type` VARCHAR(10) COMMENT 'int:数値, array:配列, text:文字列',
    `Value` TEXT COMMENT '値(文字列化して登録)',
    `CreateDate` DATETIME COMMENT 'レコード登録日',
    `UpdateDate` DATETIME COMMENT 'レコード更新日',
    `DeleteDate` DATETIME COMMENT 'レコード無効日',
    `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'レコード無効フラグ(0:有効, 1:無効)',
    PRIMARY KEY (`SystemCommonId`)
) ENGINE=InnoDB COMMENT 'システム共通';
