<?php
/**
 * Snapshot_Collector Class
 *
 * WordPress、サーバー、PHP、DB、プラグイン、テーマ情報を収集し、
 * 秘匿情報を保護した状態で各形式にフォーマットします。
 */

if (!defined('ABSPATH')) exit;

class Snapshot_Collector {

	/**
	 * すべての情報を収集し、サニタイズして返す
	 */
	public function get_full_snapshot() {
		$data = [
			'WordPress'    => $this->get_wp_info(),
			'Database_Env' => $this->get_database_env(),
			'Server'       => $this->get_server_info(),
			'PHP_Settings' => $this->get_php_settings(),
			'Htaccess'     => $this->get_htaccess_info(),
			'Plugins'      => $this->get_plugin_info(),
			'Theme'        => $this->get_theme_info(),
			'permissions'  => $this->get_permissions_info(),
			'cron'         => $this->get_cron_info(),
		];
		return $this->sanitize($data);
	}

	/**
	 * WordPress基本情報の取得
	 */
	private function get_wp_info() {
		global $wpdb;
		return [
			'Version'      => get_bloginfo('version'),
			'Site URL'     => site_url(),
			'Home URL'     => home_url(),
			'Multisite'    => is_multisite() ? 'Yes' : 'No',
			'Debug Mode'   => defined('WP_DEBUG') && WP_DEBUG ? 'Enabled' : 'Disabled',
			'Debug Log'    => defined('WP_DEBUG_LOG') && WP_DEBUG_LOG ? 'Enabled' : 'Disabled',
			'Memory Limit' => WP_MEMORY_LIMIT,
			'Table Prefix' => $wpdb->prefix,
			'Language'     => get_locale(),
		];
	}

	/**
	 * DB接続環境の取得
	 */
	private function get_database_env() {
		return [
			'DB_NAME'     => defined('DB_NAME') ? DB_NAME : 'N/A',
			'DB_USER'     => defined('DB_USER') ? DB_USER : 'N/A',
			'DB_PASSWORD' => defined('DB_PASSWORD') ? DB_PASSWORD : 'N/A', // sanitizeで伏せ字
			'DB_HOST'     => defined('DB_HOST') ? DB_HOST : 'N/A',
			'DB_CHARSET'  => defined('DB_CHARSET') ? DB_CHARSET : 'N/A',
			'DB_COLLATE'  => defined('DB_COLLATE') && DB_COLLATE ? DB_COLLATE : 'Default',
		];
	}

	/**
	 * サーバー環境情報の取得
	 */
	private function get_server_info() {
		global $wpdb;
		return [
			'Software'       => $_SERVER['SERVER_SOFTWARE'],
			'PHP Version'    => phpversion(),
			'MySQL Version'  => $wpdb->db_version(),
			'OS'             => PHP_OS,
			'Protocol'       => $_SERVER['SERVER_PROTOCOL'],
			'Document Root'  => ABSPATH, // sanitizeで匿名化
		];
	}

	/**
	 * PHP設定値の取得
	 */
	private function get_php_settings() {
		return [
			'Max Execution Time'  => ini_get('max_execution_time'),
			'Memory Limit'        => ini_get('memory_limit'),
			'Upload Max Filesize' => ini_get('upload_max_filesize'),
			'Post Max Size'       => ini_get('post_max_size'),
			'Max Input Vars'      => ini_get('max_input_vars'),
			'cURL Version'        => function_exists('curl_version') ? curl_version()['version'] : 'Disabled',
		];
	}

	/**
	 * .htaccessの内容取得
	 */
	private function get_htaccess_info() {
		$htaccess_path = ABSPATH . '.htaccess';
		if (file_exists($htaccess_path) && is_readable($htaccess_path)) {
			$content = file_get_contents($htaccess_path);
			return [
				'Status'  => 'File exists',
				'Content' => "\n" . trim($content)
			];
		}
		return ['Status' => 'File not found or not readable.'];
	}

	/**
	 * 有効なプラグイン一覧の取得
	 */
	private function get_plugin_info() {
		if (!function_exists('get_plugins')) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$all_plugins = get_plugins();
		$active_plugins = get_option('active_plugins');
		$list = [];

		foreach ($active_plugins as $path) {
			if (isset($all_plugins[$path])) {
				$list[] = $all_plugins[$path]['Name'] . ' (' . $all_plugins[$path]['Version'] . ')';
			}
		}
		return $list;
	}

	/**
	 * テーマ情報の取得
	 */
	private function get_theme_info() {
		$theme = wp_get_theme();
		return [
			'Name'        => $theme->get('Name'),
			'Version'     => $theme->get('Version'),
			'Author'      => $theme->get('Author'),
			'Child Theme' => is_child_theme() ? 'Yes' : 'No',
			'Parent Theme'=> is_child_theme() ? $theme->get('Template') : 'N/A',
		];
	}

	// ディレクトリ権限のチェック
	private function get_permissions_info() {
		$dirs = [
			'Root' => ABSPATH,
			'Uploads' => wp_upload_dir()['basedir'],
			'Plugins' => WP_PLUGIN_DIR,
			'Themes' => get_theme_root(),
		];
		$results = [];
		foreach ($dirs as $name => $path) {
			$results[$name] = is_writable($path) ? 'Writable' : 'Unwritable (!!!)';
		}
		return $results;
	}

	// Cronの直近スケジュール
	private function get_cron_info() {
		$cron = _get_cron_array();
		$next_jobs = [];
		if ($cron) {
			foreach ($cron as $timestamp => $jobs) {
				foreach ($jobs as $hook => $details) {
					$next_jobs[] = date('Y-m-d H:i:s', $timestamp) . " : $hook";
					if (count($next_jobs) >= 5) break 2;
				}
			}
		}
		return [
			'Next Scheduled Jobs' => $next_jobs,
			'WP_CRON Disabled' => defined('DISABLE_WP_CRON') && DISABLE_WP_CRON ? 'Yes' : 'No',
		];
	}

	/**
	 * 秘匿情報のサニタイズ（伏せ字・匿名化）
	 */
	private function sanitize($data) {
		$sensitive_keys = ['pass', 'key', 'secret', 'auth', 'salt', 'token', 'nonce', 'mail', 'sig'];

		array_walk_recursive($data, function(&$value, $key) use ($sensitive_keys) {
			// キー名による伏せ字
			foreach ($sensitive_keys as $s) {
				if (stripos($key, $s) !== false) {
					$value = '******** [PROTECTED]';
					return;
				}
			}

			// パス情報の匿名化（サーバー固有のユーザー名などを隠す）
			if (is_string($value) && strpos($value, ABSPATH) !== false) {
				$parts = explode(DIRECTORY_SEPARATOR, trim(ABSPATH, DIRECTORY_SEPARATOR));
				if (count($parts) > 2) {
					// /home/username/public_html -> /****** /public_html のように変換
					$masked_path = DIRECTORY_SEPARATOR . '******' . DIRECTORY_SEPARATOR . end($parts);
					$value = str_replace(ABSPATH, $masked_path . DIRECTORY_SEPARATOR, $value);
				}
			}
		});
		return $data;
	}

	/**
	 * Text形式フォーマッター
	 */
	public function format_to_text($data) {
		$output = "=== System Snapshot (" . date('Y-m-d H:i:s') . ") ===\n\n";
		foreach ($data as $section => $values) {
			$output .= "[$section]\n";
			foreach ($values as $k => $v) {
				if ($k === 'Content') {
					$output .= "--- File Content Start ---\n$v\n--- File Content End ---\n";
				} else {
					$output .= is_array($v) ? " - " . implode("\n - ", $v) . "\n" : "$k: $v\n";
				}
			}
			$output .= "\n";
		}
		return $output;
	}

	/**
	 * Markdown形式フォーマッター
	 */
	public function format_to_markdown($data) {
		$output = "## System Snapshot (" . date('Y-m-d H:i:s') . ")\n\n";
		foreach ($data as $section => $values) {
			$output .= "### $section\n";
			if (isset($values['Content'])) {
				$output .= "```apache\n" . $values['Content'] . "\n```\n";
				unset($values['Content']);
			}

			if (!empty($values)) {
				$output .= "| Item | Value |\n|---|---|\n";
				foreach ($values as $k => $v) {
					if (is_array($v)) $v = implode('<br>', $v);
					$v = str_replace("\n", " ", $v);
					$output .= "| $k | $v |\n";
				}
			}
			$output .= "\n";
		}
		return $output;
	}
}