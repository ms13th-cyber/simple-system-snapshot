# Simple System Information "Snapshot"

A high-performance, minimalist diagnostic tool for WordPress administrators and developers. Capture a full "snapshot" of your environment in one click—securely masked and ready for troubleshooting.

---

## Key Features

- **One-Click Snapshots**: Instantly generate a comprehensive report of your WordPress, Server, and PHP environment.
- **Privacy-First (Auto-Masking)**: Automatically detects and hides sensitive data such as DB passwords, API keys, salts, and nonces using an intelligent sanitization engine.
- **Multiple Export Formats**: Choose the best format for your needs:
    - **Plain Text**: Ideal for quick emails or chat messages.
    - **Markdown**: Perfect for GitHub Issues, Notion, or project management tools.
    - **JSON**: Structured data for programmatic analysis or logs.
- **Deep Diagnostics**: Includes critical info often missed by other tools, such as `.htaccess` contents, cron schedules, and directory write permissions.
- **Path Anonymization**: Automatically masks server-specific usernames in file paths to protect server infrastructure privacy.
- **Ultra-Lightweight**: No heavy background processes or constant database polling. It only runs when you need it.

## Installation

1. Upload the `simple-system-snapshot` folder to your `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to **Tools > System Snapshot** to generate your first snapshot.
4. *Tip: Use the "Copy to Clipboard" button for the fastest way to share info with your development team.*

---

## 主な機能（日本語）

保守運用・不具合調査の効率を最大化する、軽量・高機能な診断レポート生成プラグインです。ワンクリックで環境情報を抽出し、安全に共有可能な形式で出力します。

- **ワンクリック・スナップショット**: WordPress、サーバー、PHP設定、DB環境を一括で取得。
- **強力な秘匿情報保護**: DBパスワード、APIキー、ソルト、nonceなどの機密情報を独自の検知エンジンで自動伏せ字化。
- **3種類の出力形式**:
    - **テキスト**: メールやチャット（Slack等）への貼り付けに最適。
    - **Markdown**: GitHubのIssueやBacklog、Notionへの報告に最適。
    - **JSON**: ログ保存や外部ツールでの解析に最適。
- **現場主義の診断項目**: `.htaccess` の内容、Cron（予約タスク）の実行予定、ディレクトリの書き込み権限など、プロが本当に欲しい情報を網羅。
- **パスの匿名化**: サーバー固有のユーザー名が含まれるフルパスを自動で隠蔽し、サーバーの機密性を維持します。
- **究極の軽量設計**: 設定画面を持たず、必要な時だけ動作するため、サイトのパフォーマンスに一切影響を与えません。

## インストール・使用方法

1. `simple-system-snapshot` フォルダを `/wp-content/plugins/` にアップロードします。
2. 管理画面の「プラグイン」から有効化してください。
3. **「ツール」 > 「System Snapshot」**から、必要な形式を選んで「コピー」または「保存」をクリックしてください。

## 開発者情報
- **Author**: masato shibuya (Image-box Co., Ltd.)
- **Version**: 1.0.0
- **Update**: [https://github.com/ms13th-cyber/simple-system-snapshot/](https://github.com/ms13th-cyber/simple-system-snapshot/)