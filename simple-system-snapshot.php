<?php
/*
Plugin Name: Simple System Information "Snapshot"
Description: サイトの環境情報をワンクリックでText/MD/JSON形式で取得・コピー。
Version: 1.0.0
Tested up to: 6.9.4
Requires PHP: 8.3.23
Author: masato shibuya (Image-box Co., Ltd.)
*/

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/class-collector.php';

class SimpleSystemSnapshot {
	public function __construct() {
		add_action('admin_menu', [$this, 'add_menu']);
		add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
	}

	public function add_menu() {
		add_management_page('System Snapshot', 'System Snapshot', 'manage_options', 'system-snapshot', [$this, 'render_page']);
	}

	public function enqueue_assets($hook) {
		if ($hook !== 'tools_page_system-snapshot') return;
		wp_enqueue_script('snapshot-js', plugin_dir_url(__FILE__) . 'assets/snapshot.js', [], '1.0.0', true);
	}

	public function render_page() {
		$collector = new Snapshot_Collector();
		$all_data = $collector->get_full_snapshot();
		?>
		<div class="wrap">
			<h1>System Information Snapshot</h1>
			<p>エンジニアに共有するための環境情報を、安全に（秘匿情報を伏せて）書き出します。</p>

			<table class="form-table">
				<tr>
					<th scope="row">出力形式を選択</th>
					<td>
						<select id="snapshot-format">
							<option value="text">Plain Text (.txt)</option>
							<option value="markdown">Markdown (.md)</option>
							<option value="json">JSON (.json)</option>
						</select>
					</td>
				</tr>
			</table>

			<div style="margin-top: 20px;">
				<button id="copy-snapshot" class="button button-primary">クリップボードにコピー</button>
				<button id="download-snapshot" class="button">ファイルとして保存</button>
			</div>

			<div style="margin-top: 20px;">
				<textarea id="snapshot-preview" readonly style="width:100%; height:400px; font-family:monospace; background:#f0f0f0;"><?php echo esc_textarea($collector->format_to_text($all_data)); ?></textarea>
			</div>

			<!-- JSへ渡すデータ -->
			<script>
				const snapshotData = <?php echo json_encode([
					'text' => $collector->format_to_text($all_data),
					'markdown' => $collector->format_to_markdown($all_data),
					'json' => json_encode($all_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
				]); ?>;
			</script>
		</div>
		<?php
	}
}
new SimpleSystemSnapshot();


require_once __DIR__ . '/plugin-update-checker/plugin-update-checker.php';
$updateChecker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
    'https://github.com/ms13th-cyber/simple-system-snapshot/',
    __FILE__,
    'simple-system-snapshot'
);
$updateChecker->setBranch('main');