/************************************
 * ユーザ情報管理テーブル
 ************************************/
DROP TABLE IF EXISTS `UserInfos`;
CREATE TABLE `UserInfos`
(
    `UserInfoId` BIGINT(12) UNSIGNED AUTO_INCREMENT COMMENT '管理ID(Users.UserIdを入れる)',
    `Country` TINYINT(3) UNSIGNED COMMENT '国番号',
    `State` TINYINT(3) UNSIGNED COMMENT '都道府県',
    `Gender` TINYINT(1) UNSIGNED COMMENT '性別',
    `BirthYear` TINYINT(4) UNSIGNED COMMENT '生年(予備)',
    `BirthMonth` TINYINT(2) UNSIGNED COMMENT '生月(予備)',
    `BirthDay` TINYINT(2) UNSIGNED COMMENT '生日(予備)',
    `CreateDate` DATETIME COMMENT 'レコード登録日',
    `UpdateDate` DATETIME COMMENT 'レコード更新日',
    `DeleteDate` DATETIME COMMENT 'レコード無効日',
    `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'レコード無効フラグ(0:有効, 1:無効)',
    PRIMARY KEY (`UserInfoId`)
) ENGINE=InnoDB COMMENT 'ユーザ情報';
