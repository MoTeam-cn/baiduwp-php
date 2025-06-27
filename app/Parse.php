<?php

namespace app;

use app\Req;
use app\Tool;
use think\facade\Db;

/**
 * PanDownload 网页复刻版，PHP 语言版
 *
 * @author Yuan_Tuo <yuantuo666@gmail.com>
 * @link https://github.com/yuantuo666/baiduwp-php
 */
class Parse
{
	public static function decodeSceKey($seckey)
	{
		$seckey = str_replace("-", "+", $seckey);
		$seckey = str_replace("~", "=", $seckey);
		return str_replace("_", "/", $seckey);
	}

	public static function decryptMd5($md5)
	{
		if (preg_match('/^.{9}[a-f0-9]/', $md5) && ctype_xdigit(substr($md5, 9, 1))) {
			return $md5;
		}
		$key = dechex(ord(substr($md5, 9, 1)) - ord('g'));
		$key2 = substr($md5, 0, 9) . $key . substr($md5, 10, strlen($md5));
		$key3 = "";
		for ($a = 0; $a < strlen($key2); $a++) {
			$key3 .= dechex(hexdec($key2[$a]) ^ (15 & $a));
		}
		return substr($key3, 8, 8) . substr($key3, 0, 8) . substr($key3, 24, 8) . substr($key3, 16, 8);
	}

	public static function getList($surl, $pwd, $dir): array
	{
		$message = [];
		$IsRoot = $dir == "";
		$file_list = [];
		$Page = 1;
		
		// 获取所有文件 fix #86
		while (true) {
			$Filejson = self::getListApi($surl, $dir, $IsRoot, $pwd, $Page);
			if (config('app.debug')) $message[] = json_encode($Filejson);
			if ($Filejson["errno"] ?? 999 !== 0) {
				return self::listError($Filejson, $message);
			}
			foreach ($Filejson['data']['list'] as $v) {
				$file_list[] = $v;
			}
			if (count($Filejson['data']["list"]) < 1000) break;
			$Page++;
		}
		
		$randSk = urlencode(self::decodeSceKey($Filejson["data"]["seckey"]));
		$shareid = $Filejson["data"]["shareid"];
		$uk = $Filejson["data"]["uk"];

		// breadcrumb
		$DirSrc = [];
		if (!$IsRoot) {
			$Dir_list = explode("/", $dir);
			for ($i = 1; $i <= count($Dir_list) - 2; $i++) {
				if ($i == 1 and strstr($Dir_list[$i], "sharelink")) continue;
				$fullsrc = strstr($dir, $Dir_list[$i], true) . $Dir_list[$i];
				$DirSrc[] = array("isactive" => 0, "fullsrc" => $fullsrc, "dirname" => $Dir_list[$i]);
			}
			$DirSrc[] = array("isactive" => 1, "fullsrc" => $dir, "dirname" => $Dir_list[$i]);
		}
		
		$Filenum = count($file_list);
		$FileData = [];
		$RootData = array(
			"src" => $DirSrc,
			"randsk" => $randSk,
			"shareid" => $shareid,
			"surl" => $surl,
			"pwd" => $pwd,
			"uk" => $uk,
		);

		foreach ($file_list as $file) {
			if ($file["isdir"] == 0) { // 根目录返回的居然是字符串 #255
				//文件
				$FileData[] = array(
					"isdir" => 0,
					"name" => $file["server_filename"],
					"fs_id" => number_format($file["fs_id"], 0, '', ''),
					"size" => $file["size"],
					"uploadtime" => $file["server_ctime"],
					"md5" => $file["md5"] ?? '',
					"dlink" => $file["dlink"] ?? ''
				);
			} else {
				//文件夹
				$FileData[] = array(
					"isdir" => 1,
					"name" => $file["server_filename"],
					"path" => $file["path"],
					"size" => $file["size"],
					"uploadtime" => $file["server_ctime"]
				);
			}
		}

		return array(
			"error" => 0,
			"isroot" => $IsRoot,
			"dirdata" => $RootData,
			"filenum" => $Filenum,
			"filedata" => $FileData,
			"message" => $message
		);
	}

	/**
	 * 转存文件到自己的网盘
	 * @param string $fs_id 文件ID
	 * @param string $randsk 随机密钥
	 * @param string $share_id 分享ID
	 * @param string $uk 用户ID
	 * @return array [errno, path, msg]
	 */
	private static function transfer($fs_id, $randsk, $share_id, $uk)
	{
		$url = "https://pan.baidu.com/share/transfer?app_id=250528&async=1&channel=chunlei&clienttype=0&ondup=newcopy&web=1";
		$url .= "&sekey=" . $randsk . "&shareid=" . $share_id . "&from=" . $uk;

		$data = [
			"fsidlist" => "[$fs_id]",
			"path" => "/我的资源"
		];

		$header = [
			"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36",
			"Cookie: " . config('baiduwp.cookie'),
			"Referer: https://pan.baidu.com/disk/home"
		];

		$result = json_decode(Req::POST($url, http_build_query($data), $header), true);
		if (($result["errno"] ?? 1) === 0 && isset($result["extra"]["list"][0]["to"])) {
			return [0, $result["extra"]["list"][0]["to"], ""];
		} else {
			return [
				$result["errno"] ?? 999,
				"",
				$result["show_msg"] ?? "转存失败"
			];
		}
	}

	/**
	 * 获取下载链接
	 * 
	 * @param string $fs_id 文件ID
	 * @param string $randsk 随机密钥
	 * @param string $share_id 分享ID
	 * @param string $uk 用户ID
	 * @param string $name 文件名
	 * @param int $size 文件大小
	 * @param string $md5 文件MD5
	 * @param string $dlink 文件下载链接
	 */
	public static function download($fs_id, $randsk, $share_id, $uk, $name = '', $size = 0, $md5 = '', $dlink = '')
	{
		if (!$fs_id || !$randsk || !$share_id || !$uk) {
			return [
				'error' => -1,
				'msg' => '参数错误',
			];
		}
		$message = [];

		// 检查缓存
		if (config('baiduwp.enable_cache', true) && config('baiduwp.db')) {
			$cache = Db::table('records')->where('fs_id', $fs_id)->where('expires_at', '>', date('Y-m-d H:i:s'))->find();
			if ($cache) {
				return [
					'error' => 0,
					'filedata' => [
						'filename' => $name,
						'size' => $size,
						'md5' => $md5
					],
					'directlink' => $cache['link'],
					'urls' => null,
					'user_agent' => config('baiduwp.user_agent', 'netdisk;18.0.0.12;PC;bdwp'),
					'parse_time' => $cache['expires_at'],
					'message' => ['使用缓存的下载链接'],
					'is_cached' => true,
					'cache_time' => $cache['time'],
					'expires_at' => $cache['expires_at']
				];
			}
		}

		// 检查是否有可用的 SVIP 账号
		$cookie = config('baiduwp.svip_cookie') ? config('baiduwp.svip_cookie') : config('baiduwp.cookie');
		if (!$cookie) {
			return [
				'error' => -1,
				'msg' => '账号未配置，请联系站长',
			];
		}

		// 检查账号状态
		$test_url = "https://pan.baidu.com/api/checkapl/download";
		$test_header = [
			"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36",
			"Cookie: " . $cookie
		];
		$test_result = json_decode(Req::GET($test_url, $test_header), true);
		if (($test_result['errno'] ?? -6) === -6) {
			return [
				'error' => -1,
				'msg' => '账号登录失效，请联系站长更新Cookie',
			];
		}
		if (($test_result['errno'] ?? 1) === 0 && ($test_result['anti']['ban_status'] ?? false)) {
			return [
				'error' => -1,
				'msg' => '账号被限制，请联系站长更新Cookie',
			];
		}
		if (config('app.debug')) $message[] = json_encode($test_result);

		$ip = Tool::getIP();
		$isipwhite = FALSE;
		if (config('baiduwp.db')) {
			$data = Db::connect()->table("ip")->where('ip', $ip)->find();
			if ($data) {
				// 存在 判断类型
				if ($data->type === -1) {
					// 黑名单
					return array("error" => -1, "msg" => "当前ip已被加入黑名单，请联系站长解封", "ip" => $ip);
				} elseif ($data->type === 0) {
					// 白名单
					$message[] = "当前ip为白名单~ $ip";
					$isipwhite = TRUE;
				}
			}
		}

		$FileData = array(
			"filename" => $name,
			"size" => $size,
			"md5" => $md5
		);

		// 转存文件
		list($transfer_errno, $transfer_path, $transfer_msg) = self::transfer($fs_id, $randsk, $share_id, $uk);
		if ($transfer_errno !== 0) {
			return array(
				"error" => -1,
				"title" => "转存失败",
				"msg" => $transfer_msg,
				"message" => $message
			);
		}

		// 通过PCS接口获取下载直链
		$to_path = rawurlencode($transfer_path);
		$url = "https://d.pcs.baidu.com/rest/2.0/pcs/file?ant=1&apn_id=33_13&app_id=250528&channel=0&check_blue=1&clienttype=17&cuid=08E271F7046B366BE1BF9F1F30DF0689%7CVFZVYSRCU&deviceid=611777535803319847&devuid=08E271F7046B366BE1BF9F1F30DF0689%7CVFZVYSRCU&dtype=1&eck=1&ehps=1&err_ver=1.0&es=1&esl=1&freeisp=0&method=locatedownload&network_type=4G&path=$to_path&queryfree=0&rand=0854bec9ad10241680eb16aaf3e9ab3912f0f429&time=1744558717&use=0&ver=4.0&version=2.2.101.242&version_app=12.25.3&vip=0&psign=aa42ffc322b4c71d2f39e422aa83607e";

		$header = [
			"User-Agent: " . (config('baiduwp.user_agent') ?: "netdisk;18.0.0.12;PC;"),
			"Cookie: " . $cookie
		];

		if (config('app.debug')) {
			$message[] = "PCS API URL: " . $url;
			$message[] = "Transfer Path: " . $transfer_path;
		}

		$response = Req::GET($url, $header);
		$res = json_decode($response, true);

		if (!isset($res['urls'])) {
			if (config('app.debug')) {
				$message[] = "PCS API Response: " . $response;
			}
			$msg = isset($res['errmsg']) ? $res['errmsg'] : '未知错误';
			return array(
				"error" => -1,
				"title" => "获取下载链接失败",
				"msg" => $msg,
				"message" => $message,
				"debug" => $res
			);
		}

		// 获取下载链接
		$urls = [];
		foreach ($res['urls'] as $url) {
			$link = $url['url'] . "&origin=dlna";
			if (!str_contains($link, "qdall01.baidupcs.com")) {
				$urls[] = $link;
			}
		}

		if (config('app.debug')) {
			$message[] = "Available download links count: " . count($urls);
			foreach ($urls as $index => $url) {
				$message[] = "Download link " . ($index + 1) . ": " . $url;
			}
		}

		// 检查是否所有链接都是限速链接
		if (empty($urls)) {
			if (config('baiduwp.db')) {
				Db::connect()->table('account')->where('cookie', $cookie)->update(['status' => -1]);
			}
			return array(
				"error" => -1,
				"title" => "获取下载链接失败",
				"msg" => "解析失败，账号可能已限速，请稍后重试",
				"message" => $message
			);
		}

		// 使用第一个链接作为directlink
		$directlink = $urls[0];

		// 解析过期时间
		$expires_at = date('Y-m-d H:i:s', strtotime('+8 hours')); // 默认8小时
		if (preg_match('/expires=(\d+)h/', $directlink, $matches)) {
			$expires_at = date('Y-m-d H:i:s', strtotime('+' . $matches[1] . ' hours'));
		}

		// 检查缓存
		if (config('baiduwp.db') && config('baiduwp.enable_cache', true)) {
			// 先查找是否有未过期的缓存
			$cache = \think\facade\Db::table('records')
				->where('fs_id', $fs_id)
				->where('expires_at', '>', date('Y-m-d H:i:s'))
				->order('id', 'desc')
				->find();

			if ($cache) {
				// 使用缓存的链接
				return array(
					"error" => 0,
					"filedata" => $FileData,
					"directlink" => $cache['link'],
					"urls" => json_decode($cache['link'], true),
					"user_agent" => config('baiduwp.user_agent') ?: "netdisk;18.0.0.12;PC;",
					"parse_time" => $cache['time'],
					"message" => array_merge($message, ["使用缓存的下载链接"]),
					"is_cached" => true,  // 添加缓存标记
					"cache_time" => $cache['time'],  // 缓存时间
					"expires_at" => $cache['expires_at']  // 过期时间
				);
			}

			// 记录新的解析结果
			$ip = \app\Tool::getIP();
			$ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
			\think\facade\Db::table('records')->insert([
				'time' => date('Y-m-d H:i:s'),
				'name' => $FileData['filename'],
				'size' => $FileData['size'],
				'md5' => $FileData['md5'],
				'link' => $directlink,
				'ip' => $ip,
				'ua' => $ua,
				'account' => -1,
				'fs_id' => $fs_id,
				'expires_at' => $expires_at
			]);
		}

		return array(
			"error" => 0,
			"filedata" => $FileData,
			"directlink" => $directlink,
			"urls" => $urls,
			"user_agent" => config('baiduwp.user_agent') ?: "netdisk;18.0.0.12;PC;",
			"parse_time" => date("Y-m-d H:i:s"),
			"message" => $message,
			"is_cached" => false  // 添加缓存标记
		);
	}

	private static function getListApi(string $Shorturl, string $Dir, bool $IsRoot, string $Password, int $Page = 1)
	{
		$Url = 'https://pan.baidu.com/share/wxlist?channel=weixin&version=2.2.2&clienttype=25&web=1';
		$Root = ($IsRoot) ? "1" : "0";
		$Dir = urlencode($Dir);
		if (substr($Shorturl, 0, 1) == "2") {
			$Shorturl = substr($Shorturl, 1);
			$Shorturl = base64_decode($Shorturl);
			[$uk, $share_id] = explode("&", $Shorturl);
			$params = "&uk=$uk&shareid=$share_id";
		} else {
			$params = "&shorturl=$Shorturl";
		}
		$Data = "$params&dir=$Dir&root=$Root&pwd=$Password&page=$Page&num=1000&order=time";
		$BDUSS = Tool::getSubstr(config('baiduwp.cookie'), 'BDUSS=', ';');
		$header = ["User-Agent: netdisk", "Cookie: BDUSS=$BDUSS", "Referer: https://pan.baidu.com/disk/home"];
		return json_decode(Req::POST($Url, $Data, $header), true);
	}

	private static function getDlink(string $fs_id, string $randsk, string $share_id, string $uk, int $app_id = 250528)
	{ // 获取下载链接
		$url = 'https://pan.baidu.com/api/sharedownload?app_id=' . $app_id . '&channel=chunlei&clienttype=12&web=1'; // 获取下载链接

		if (strstr($randsk, "%")) $randsk = urldecode($randsk);
		$data = "encrypt=0" . "&extra=" . urlencode('{"sekey":"' . $randsk . '"}') . "&fid_list=[$fs_id]" . "&primaryid=$share_id" . "&uk=$uk" . "&product=share&type=nolimit";
		$header = array(
			"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36 Edg/110.0.1587.69",
			"Cookie: " . config('baiduwp.cookie'),
			"Referer: https://pan.baidu.com/disk/home"
		);
		return json_decode(Req::POST($url, $data, $header), true);
	}

	private static function listError($Filejson, $message): array
	{
		if (empty($Filejson)) {
			return [
				"error" => -1,
				"title" => "获取到的列表为空",
				"msg" => "请确保已正确设置 Cookie 且服务器网络连接正常",
				"message" => $message
			];
		}
		// 解析异常
		$ErrorCode = $Filejson["errtype"] ?? ($Filejson["errno"] ?? 999);
		$ErrorMessage = [
			"mis_105" => "你所解析的文件不存在~",
			"mispw_9" => "提取码错误",
			"mispwd-9" => "提取码错误",
			"mis_2" => "不存在此目录",
			"mis_4" => "不存在此目录",
			5 => "不存在此分享链接或提取码错误",
			3 => "此链接分享内容可能因为涉及侵权、色情、反动、低俗等信息，无法访问！",
			0 => "啊哦，你来晚了，分享的文件已经被删除了，下次要早点哟。",
			10 => "啊哦，来晚了，该分享文件已过期",
			8001 => "普通账号可能被限制，请检查普通账号状态",
			9013 => "普通账号被限制，请检查普通账号状态",
			9019 => "普通账号 Cookie 状态异常，请检查：账号是否被限制、Cookie 是否过期（前往网站设置页面修改）",
			999 => "错误 -> " . json_encode($Filejson)
		];
		return [
			"error" => -1,
			"title" => "获取列表错误 ($ErrorCode)",
			"msg" => $ErrorMessage[$ErrorCode] ?? "未知错误，如多次出现请向提出issue反馈",
			"message" => $message
		];
	}

	private static function downloadError($json4, $message): array
	{
		$errno = $json4["errno"] ?? 999;
		$error = [
			999 => ["请求错误", "请求百度网盘服务器出错，请检查网络连接或重试"],
			-20 => ["触发验证码(-20)", "请等待一段时间，再返回首页重新解析。"],
			-9 => ["文件不存在(-9)", "请返回首页重新解析。"],
			-6 => ["账号未登录(-6)", "请检查普通账号是否正常登录。"],
			-1 => ["文件违规(-1)", "您下载的内容中包含违规信息"],
			2 => ["下载失败(2)", "下载失败，请稍候重试"],
			112 => ["链接超时(112)", "获取链接超时，每次解析列表后只有5min有效时间，请返回首页重新解析。"],
			113 => ["传参错误(113)", "获取失败，请检查参数是否正确。"],
			116 => ["链接错误(116)", "该分享不存在"],
			118 => ["没有下载权限(118)", "没有下载权限，请求百度服务器时，未传入sekey参数或参数错误。"],
			110 => ["服务器错误(110)", "服务器错误，可能服务器IP被百度封禁，请切换 IP 或更换服务器重试。"],
			121 => ["服务器错误(121)", "你选择操作的文件过多，减点试试吧"],
			8001 => ["普通账号错误(8001)", "普通账号可能被限制，请检查普通账号状态"],
			9013 => ["普通账号错误(9013)", "普通账号被限制，请检查普通账号状态"],
			9019 => ["普通账号错误(9019)", "普通账号 Cookie 状态异常，请检查：账号是否被限制、Cookie 是否过期（前往网站设置页面修改）"],
		];

		if (isset($error[$errno])) return [
			"error" => -1,
			"title" => $error[$errno][0],
			"msg" => $error[$errno][1],
			"message" => $message
		];
		else return [
			"error" => -1,
			"title" => "获取下载链接失败 ($errno)",
			"msg" => "未知错误！错误：" . json_encode($json4),
			"message" => $message
		];
	}

	private static function realLinkError($body_decode, $message): array
	{
		$ErrorCode = $body_decode["errno"] ?? ($body_decode["error_code"] ?? 999);
		$ErrorMessage = [
			8001 => "SVIP 账号可能被限制，请检查 SVIP 的 Cookie 是否设置正确且有效",
			9013 => "SVIP 账号被限制，请检查更换 SVIP 账号",
			9019 => "SVIP 账号可能被限制，请检查 SVIP 的 Cookie 是否设置正确且有效",
			31360 => "下载链接超时，请刷新页面重试。若重试后仍报错，请检查普通帐号 Cookie 是否过期",
			31362 => "下载链接签名错误，请检查 UA 是否正确",
			999 => "错误 -> " . json_encode($body_decode)
		];

		return [
			"error" => -1,
			"title" => "获取下载链接失败 ($ErrorCode)",
			"msg" => $ErrorMessage[$ErrorCode] ?? "未知错误！错误：" . json_encode($body_decode),
			"message" => $message
		];
	}
}
