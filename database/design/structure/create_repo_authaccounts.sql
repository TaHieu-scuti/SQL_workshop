CREATE TABLE IF NOT EXISTS `ADGAINER_db_SECURE`.`repo_authaccounts` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `account_id` VARCHAR(50) NOT NULL COMMENT 'ADgainerシステムのアカウントID',
  `license` VARCHAR(19) NULL COMMENT 'Yahoo! JAPANが発行するライセンス番号です。\n「xxxx-xxxx-xxxx-xxxx」の形式で入力ください。',
  `apiAccountId` VARCHAR(19) NULL COMMENT 'Yahoo! JAPANが発行するAPIシステムに認証するためのIDです。\n「xxxx-xxxx-xxxx-xxxx」の形式で入力ください。',
  `apiAccountPassword` VARCHAR(255) NULL COMMENT 'Yahoo APIアカウントIDにお客様自身がAPI管理ツールで設定したパスワードです。',
  `accountId` VARCHAR(20) NULL COMMENT 'APIからアクセスするスポンサードサーチもしくはYahoo!ディスプレイアドネットワークのアカウントIDです。\n外部の運用ツールが代理店または広告主など別のユーザーの代わりにアクセスするために必要です。',
  `onBehalfOfAccountId` VARCHAR(20) NULL COMMENT 'アプリケーションからアカウントIDにアクセスするためのAPIアクセス専用のアカウントID（アプリケーションアカウントID）です。\nAccountIdに対応したIDである必要があります。\n正しくセットされていない場合はエラーとなります。\n外部の運用ツールが代理店または広告主など別のユーザーの代わりにアクセスするために必要です。',
  `onBehalfOfPassword` VARCHAR(255) NULL COMMENT 'アプリケーションアカウントID（onBehalfOfAccountID）に設定されたパスワードです。\n外部の運用ツールが代理店または広告主など別のユーザーの代わりにアクセスするために必要です。',
  `developerToken` VARCHAR(22) NULL COMMENT 'AdWords API の開発者を個別に識別するための 22 文字の文字列です。\n開発者トークン文字列の例: ABcdeFGH93KL-NOPQ_STUv。\nMCCアカウントに対して発行されます。',
  `userAgent` TEXT NULL COMMENT 'リクエストの送信者と目的を定義するユーザー指定の文字列です。\n問題を 診断する際にリクエストが見つかりやすいように、アプリケーション名とバージョンを 設定してください。例: example.com:ReportDownloader:V7.18。',
  `clientCustomerId` VARCHAR(12) NULL COMMENT '対象とする AdWords アカウントのお客様 ID です。\n通常は、 123-456-7890 のような形式になります。\nCustomerService と ReportDefinitionService を除くすべてのサービスに対するすべての呼び出しで必須です。',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `repo_authaccountsidx1` (`account_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'ADgainerアカウントとメディア（Google, Yahoo）の認証項目を連携する';