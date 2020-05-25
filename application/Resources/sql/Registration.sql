/************************************
 * ユーザ登録管理テーブル
 ************************************/
 DROP TABLE IF EXISTS `Registration`;
 CREATE TABLE `Registration`
 (
     `RegistrationId` BIGINT(12) UNSIGNED AUTO_INCREMENT COMMENT '管理ID',
     `UserId` BIGINT(12) UNSIGNED COMMENT 'ユーザ管理ID',
     `Rcode` VARCHAR(128) COMMENT '登録確認コード',
     `ExpireDate` DATETIME COMMENT '有効期限',
     `RegistDate` DATETIME COMMENT '登録日',
     `CreateDate` DATETIME COMMENT 'レコード登録日',
     `UpdateDate` DATETIME COMMENT 'レコード更新日',
     `DeleteDate` DATETIME COMMENT 'レコード無効日',
     `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'レコード無効フラグ(0:有効, 1:無効)',
     PRIMARY KEY (`RegistrationId`),
     INDEX `RcodeIDX`(`Rcode`)
 ) ENGINE=InnoDB COMMENT 'ユーザ登録管理テーブル';
