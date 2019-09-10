/************************************
 * 登録予約管理テーブル
 ************************************/
DROP TABLE IF EXISTS `RegistBooking`;
CREATE TABLE `RegistBooking`
(
    `RegistBookingId` BIGINT(12) UNSIGNED AUTO_INCREMENT COMMENT '管理ID',
    `GroupId` BIGINT(12) UNSIGNED COMMENT 'グループ管理ID',
    `PlayerId` VARCHAR(30) NOT NULL COMMENT 'ゲーム側ユーザID',
    `AuthCode` VARCHAR(30) NOT NULL COMMENT '認証確認用コード',
    `GameNickname` VARCHAR(30) NOT NULL COMMENT 'ゲーム側ニックネーム',
    `UserId` BIGINT(12) UNSIGNED COMMENT 'ユーザ管理ID',
    `Registed` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT '登録済みフラグ(0:未登録, 1:登録済)',
    `CreateDate` DATETIME COMMENT 'レコード登録日',
    `UpdateDate` DATETIME COMMENT 'レコード更新日',
    `DeleteDate` DATETIME COMMENT 'レコード無効日',
    `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'レコード無効フラグ(0:有効, 1:無効)',
    PRIMARY KEY (`RegistBookingId`)
) ENGINE=InnoDB COMMENT '登録予約情報';
