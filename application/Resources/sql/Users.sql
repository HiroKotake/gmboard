/************************************
 * ユーザ管理テーブル
 ************************************/
DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users`
(
    `UserId` BIGINT(12) UNSIGNED AUTO_INCREMENT COMMENT '管理ID',
    `AliasId` CHAR(16) NOT NULL COMMENT 'IDエリアス',
    `Mail` VARCHAR(256) COMMENT 'ログイン用メールアドレス',
    `Password` CHAR(60) NOT NULL COMMENT 'ハッシュ済みパスワード',
    `Nickname` VARCHAR(30) COMMENT 'ニックネーム',
    `LastLogin` DATETIME COMMENT '最終ログイン日時',
    `MailAuthed` TINYINT(1) DEFAULT 0 COMMENT 'メイル確認フラグ(0:未確認、1:確認済み)',
    `LoginExclude` TINYINT(1) DEFAULT 0 COMMENT 'ログイン除外フラグ(0:未除外、1:除外)',
    `CreateDate` DATETIME COMMENT 'レコード登録日',
    `UpdateDate` DATETIME COMMENT 'レコード更新日',
    `DeleteDate` DATETIME COMMENT 'レコード無効日',
    `DeleteFlag` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'レコード無効フラグ(0:有効, 1:無効)',
    PRIMARY KEY (`UserId`),
    INDEX `IdxMail`(`Mail`),
    INDEX `IdxAliasId` (`AliasId`)
) ENGINE=InnoDB COMMENT 'ユーザ';
