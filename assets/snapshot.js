document.addEventListener('DOMContentLoaded', function() {
	const formatSelect = document.getElementById('snapshot-format');
	const preview = document.getElementById('snapshot-preview');
	const copyBtn = document.getElementById('copy-snapshot');
	const downloadBtn = document.getElementById('download-snapshot');

	// 形式切り替え時にプレビュー更新
	formatSelect.addEventListener('change', function() {
		preview.value = snapshotData[this.value];
	});

	// コピー処理
	copyBtn.addEventListener('click', function() {
		preview.select();
		document.execCommand('copy');
		copyBtn.textContent = 'Copied!';
		setTimeout(() => copyBtn.textContent = 'クリップボードにコピー', 2000);
	});

	// ダウンロード処理
	downloadBtn.addEventListener('click', function() {
		const format = formatSelect.value;
		const extensions = { text: 'txt', markdown: 'md', json: 'json' };
		const blob = new Blob([snapshotData[format]], { type: 'text/plain' });
		const url = window.URL.createObjectURL(blob);
		const a = document.createElement('a');
		a.href = url;
		a.download = `system-snapshot-${new Date().toISOString().slice(0,10)}.${extensions[format]}`;
		a.click();
	});
});