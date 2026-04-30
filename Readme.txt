=== Simple System Information "Snapshot" ===
Contributors: masato shibuya(Image-box Co., Ltd.)
Tags: system info, debug, diagnostic, server, developer tool
Requires at least: 5.0
Tested up to: 6.9.4
Requires PHP: 8.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

エンジニアへの不具合報告や環境調査に。秘匿情報を安全に伏せた状態で、サイトの全環境情報をText/MD/JSON形式で書き出す軽量診断ツール。

== Description ==

Simple System Information "Snapshot" は、WordPressの保守運用を行うエンジニアや管理者のための実用的な診断プラグインです。

不具合調査の際、「サーバーのバージョンは？」「PHPの設定値は？」「.htaccessはどうなってる？」といったやり取りを、このプラグイン一つで完結させます。

主な特徴：

* **網羅的なデータ収集**: WP基本情報、DB環境、サーバー設定、PHP定数、プラグイン/テーマ一覧、Cron予定、ディレクトリ権限、.htaccess内容まで網羅。
* **インテリジェント・サニタイズ**: パスワード、APIキー、各種ソルト、nonce、秘密鍵などを自動検知して[PROTECTED]に置換。安全に公開フォーラム等へ貼り付け可能です。
* **選べる3フォーマット**: Plain Text、Markdown、JSONに対応し、用途に合わせてワンクリックでコピーまたはダウンロードが可能。
* **パスの匿名化ロジック**: サーバー上の絶対パスからユーザー名等の特定部位を伏せ字にし、サーバー構成の露出を防ぎます。
* **パフォーマンス重視**: 余計なDBアクセスや常駐処理を行わない、Image-box Co., Ltd.基準の超軽量設計。

== Installation ==

1. プラグインフォルダを配置します。
   `wp-content/plugins/simple-system-snapshot/`

2. WordPress管理画面の「プラグイン」から有効化します。

3. 「ツール > System Snapshot」から出力形式を選択し、情報を取得してください。

== Usage ==

* **コピー**: プレビューエリアの内容を確認し、「クリップボードにコピー」をクリックします。
* **保存**: 「ファイルとして保存」をクリックすると、選択した形式のファイルがダウンロードされます。
* **サニタイズの確認**: 出力結果にパスワード等の生データが含まれていないか、念のためプレビューで確認してから共有してください。

== Settings ==

「ツール > System Snapshot」から以下の操作が可能です：

* **出力形式の切り替え**: Text / Markdown / JSON。
* **プレビュー表示**: 取得した情報をその場で確認。

== Notes ==

* このプラグインは情報の「表示と抽出」のみを行い、サイトの設定を変更することはありません。
* セキュリティ上の理由から、管理者（manage_options権限）のみがこのツールにアクセス可能です。

== Changelog ==

= 1.0.0 =
* 初版リリース
* 秘匿情報の自動伏せ字（Sanitizer）機能の実装
* Text / Markdown / JSON のマルチフォーマット出力対応
* .htaccess および Cron実行予定の取得機能実装
* ファイルパスの匿名化機能の実装

== License ==

This plugin is licensed under the GPLv2 or later.