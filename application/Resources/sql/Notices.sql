/************************************
 * 全体告知管理テーブル
 ************************************/
DROP TABLE IF EXISTS Notices;
CREATE TABLE Notices
(
    `NoticeId` INT(8) UNSIGNED AUTO_INCREMENT COMMENT '管理ID',
    `Target` TINYINT(2) UNSIGNED DEFAULT 0 COMMENT '表示タイプ(0:全体告知, 1:メンバー告知, 10:グループ管理者向け告知)',
    `Priority` INT(4) UNSIGNED DEFAULT 100 COMMENT '優先度',
    `Message` TEXT COMMENT 'メッセージテキスト',
    `ShowStartDateTime` DATETIME COMMENT '表示開始日時',
    `ShowEndDateTime` DATETIME COMMENT '表示終了日時',
    `Showable` TINYINT(1) UNSIGNED DEFAULT 1 COMMENT 'メッセージ表示フラグ(0:無効, 1:有効)',
    `CreateDate` DATETIME COMMENT 'レコード登録日',
    `UpdateDate` DATETIME COMMENT 'レコード更新日',
    `DeleteDate` DATETIME COMMENT 'レコード無効日',
    `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'レコード無効フラグ(0:有効, 1:無効)',
    PRIMARY KEY (`NoticeId`)
) ENGINE=InnoDB COMMENT '全体告知';
