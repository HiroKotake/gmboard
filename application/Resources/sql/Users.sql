/************************************
 * ユーザ管理テーブル
 ************************************/
DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users`
(
    `UserId` BIGINT(12) UNSIGNED AUTO_INCREMENT COMMENT '管理ID',
    `Nickname` VARCHAR(30) NOT NULL COMMENT 'ニックネーム',
    `Password` CHAR(60) NOT NULL COMMENT 'ハッシュ済みパスワード',
    `Mail` VARCHAR(256) COMMENT '連絡先メールアドレス',
    `LastLogin` DATETIME COMMENT '最終ログイン日時',
    `CerateDate` DATETIME COMMENT 'レコード登録日',
    `UpdateDate` DATETIME COMMENT 'レコード更新日',
    `DeleteDate` DATETIME COMMENT 'レコード無効日',
    `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'レコード無効フラグ(0:有効, 1:無効)',
    PRIMARY KEY (`UserId`)
) ENGINE=InnoDB COMMENT 'ユーザ';
