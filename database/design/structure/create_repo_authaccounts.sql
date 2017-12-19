CREATE TABLE `repo_authaccounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` varchar(50) NOT NULL COMMENT 'ADgainerシステムのアカウントID',
  `media` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'どのメディアのアカウントなのか？\n0: adwords, 1: Yahoo Display Network, 2: Yahoo sopnsord search',
  `license` varchar(19) DEFAULT NULL COMMENT 'Yahoo! JAPANが発行するライセンス番号です。\n「xxxx-xxxx-xxxx-xxxx」の形式で入力ください。',
  `apiAccountId` varchar(19) DEFAULT NULL COMMENT 'Yahoo! JAPANが発行するAPIシステムに認証するためのIDです。\n「xxxx-xxxx-xxxx-xxxx」の形式で入力ください。',
  `apiAccountPassword` varchar(255) DEFAULT NULL COMMENT 'Yahoo APIアカウントIDにお客様自身がAPI管理ツールで設定したパスワードです。',
  `accountId` varchar(20) DEFAULT NULL COMMENT 'APIからアクセスするスポンサードサーチもしくはYahoo!ディスプレイアドネットワークのアカウントIDです。\n外部の運用ツールが代理店または広告主など別のユーザーの代わりにアクセスするために必要です。',
  `onBehalfOfAccountId` varchar(20) DEFAULT NULL COMMENT 'アプリケーションからアカウントIDにアクセスするためのAPIアクセス専用のアカウントID（アプリケーションアカウントID）です。\nAccountIdに対応したIDである必要があります。\n正しくセットされていない場合はエラーとなります。\n外部の運用ツールが代理店または広告主など別のユーザーの代わりにアクセスするために必要です。',
  `onBehalfOfPassword` varchar(255) DEFAULT NULL COMMENT 'アプリケーションアカウントID（onBehalfOfAccountID）に設定されたパスワードです。\n外部の運用ツールが代理店または広告主など別のユーザーの代わりにアクセスするために必要です。',
  `developerToken` varchar(22) DEFAULT NULL COMMENT 'AdWords API の開発者を個別に識別するための 22 文字の文字列です。\n開発者トークン文字列の例: ABcdeFGH93KL-NOPQ_STUv。\nMCCアカウントに対して発行されます。',
  `userAgent` text COMMENT 'リクエストの送信者と目的を定義するユーザー指定の文字列です。\n問題を 診断する際にリクエストが見つかりやすいように、アプリケーション名とバージョンを 設定してください。例: example.com:ReportDownloader:V7.18。',
  `clientCustomerId` varchar(12) DEFAULT NULL COMMENT '対象とする AdWords アカウントのお客様 ID です。\n通常は、 123-456-7890 のような形式になります。\nCustomerService と ReportDefinitionService を除くすべてのサービスに対するすべての呼び出しで必須です。',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `repo_authaccountsidx1` (`account_id`),
  KEY `repo_authaccountsidx2` (`media`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ADgainerアカウントとメディア（Google, Yahoo）の認証項目を連携する';
