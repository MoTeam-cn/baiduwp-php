<?php

namespace app\controller\admin;

use app\BaseController;
use app\Request;

class Setting extends BaseController
{
    public static $setting = [
        'site_name' => ['网站名称', 'text', '将会显示在网站标题处'],
        'program_version' => ['程序版本', 'readonly', ''],
        'footer' => ['页脚信息', 'textarea', '将会显示在网站底部，支持HTML代码'],

        'admin_password' => ['管理员密码', 'text', '后台管理密码，若为空，则无法进入后台管理，否则输入正确密码才能进入后台管理'],
        'password' => ['首页密码', 'text', '首页解析使用的密码，留空则无密码'],

        'db' => ['是否启用数据库', 'readonly', '若启用，则会将解析记录保存到数据库中，否则将不会被保存，如不启用数据库，也无法使用后台管理、限制次数和流量等功能。'],
        'enable_cache' => ['启用下载缓存', 'radio', '是否启用下载链接缓存，启用后相同文件8小时内使用缓存的下载地址'],
        'link_expired_time' => ['链接有效期', 'number', '链接有效期，单位为小时'],
        'times' => ['解析次数', 'number', '解析次数，单IP每日限制解析次数'],
        'flow' => ['解析流量', 'number', '解析流量，单IP每日限制解析流量，单位为GB'],

        'check_speed_limit' => ['限速检测', 'radio', '是否开启限速检测，开启后会在解析时检测是否限速'],
        'random_account' => ['随机账号', 'radio', '是否开启随机账号，开启后会在多个账号中随机使用一个账号解析'],
        'cookie' => ['普通账号Cookie', 'textarea', '普通账号Cookie，用于获取文件列表'],
        'svip_cookie' => ['SVIP账号Cookie', 'textarea', 'SVIP账号Cookie，用于获取下载链接，留空则使用普通账号获取'],
        'user_agent' => ['下载UA', 'textarea', '此处填写下载时使用的User-Agent，用于伪装浏览器信息，如不填写则使用系统默认UA。'],
    ];
    public function list(Request $request)
    {
        $data = [];
        foreach (self::$setting as $key => $value) {
            $data[] = [
                'key' => $key,
                'name' => $value[0],
                'value' => config('baiduwp.' . $key),
                'type' => $value[1],
                'description' => $value[2],
            ];
        }
        return json([
            'error' => 0,
            'msg' => 'success',
            'data' => $data,
        ]);
    }
    public function update(Request $request)
    {
        $data = $request->post();
        try {
            self::updateConfig($data);
            return json([
                'error' => 0,
                'msg' => '保存成功',
            ]);
        } catch (\Exception $e) {
            return json([
                'error' => 1,
                'msg' => $e->getMessage(),
            ]);
        }
    }
    public static function updateConfig($data, $force = false)
    {
        $default = [
            'site_name' => 'PanDownload',
            'program_version' => \app\controller\Index::$version,
            'password' => '',
            'admin_password' => env('ADMIN_PASSWORD'),
            'db' => env('DB', false),
            'link_expired_time' => 8,
            'times' => 20,
            'flow' => 10,
            'check_speed_limit' => true,
            'random_account' => false,
            'cookie' => '',
            'svip_cookie' => '',
            'footer' => '',
            'user_agent' => '',
        ];

        $config = config('baiduwp');
        if (!$config) {
            $config = $default;
        }
        foreach ($data as $key => $value) {
            if (array_key_exists($key, self::$setting)) {
                if (self::$setting[$key][1] == 'number') {
                    $value = (int)$value;
                }
                if (self::$setting[$key][1] == 'radio') {
                    $value = $value === 'true' ? true : false;
                }
                if (self::$setting[$key][1] == 'text' || self::$setting[$key][1] == 'textarea') {
                    $value = trim($value);
                    // 验证user_agent必须以netdisk;开头
                    if ($key === 'user_agent' && !empty($value) && !str_starts_with($value, 'netdisk;')) {
                        throw new \Exception('下载UA必须以netdisk;开头');
                    }
                }
                if (self::$setting[$key][1] == 'readonly') {
                    if (!$force) continue;
                    if ($value === 'true') $value = true;
                    if ($value === 'false') $value = false;
                }
                $config[$key] = $value;
            }
        }
        $config = var_export($config, true);

        // 写入配置文件
        $config = <<<PHP
<?php
// +----------------------------------------------------------------------
// | Baiduwp-php 应用设置
// +----------------------------------------------------------------------
//
// 本文件由程序自动生成，请勿随意修改，以免失效！
return {$config};
PHP;
        try {
            $configPath = '.' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'baiduwp.php';
            if (!is_dir(dirname($configPath))) {
                mkdir(dirname($configPath), 0755, true);
            }
            if (file_put_contents($configPath, $config) === false) {
                throw new \Exception('无法写入配置文件');
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
