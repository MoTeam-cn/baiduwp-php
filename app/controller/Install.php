<?php

namespace app\controller;

use app\BaseController;
use app\controller\admin\Setting;
use app\Request;
use Exception;
use think\facade\Config;
use think\facade\Db;

class Install extends BaseController
{
    public function index()
    {
        return view('admin/install', [
            'site_name' => 'baiduwp-php 安装向导',
            'program_version' => config('baiduwp.program_version', ''),
        ]);
    }
    public function upgrade(Request $request)
    {
        if ($request->isPost()) {
            if ($request->post('ADMIN_PASSWORD', '') !== config('baiduwp.admin_password', '')) {
                return json([
                    'error' => -1,
                    'msg' => '管理员密码错误',
                ]);
            }

            // TODO: 数据库升级的 migration

            Setting::updateConfig([
                'program_version' => Index::$version,
            ], true);
            return json([
                'error' => 0,
                'msg' => 'success',
            ]);
        }

        return view('admin/upgrade', [
            'site_name' => 'baiduwp-php 升级向导',
            'program_version' => config('baiduwp.program_version', ''),
        ]);
    }
    public static function testDbConnect(Request $request)
    {
        $driver = $request->post('driver');
        $host = $request->post('host');
        $name = $request->post('name');
        $user = $request->post('user');
        $pass = $request->post('pass');

        if ($driver == 'mysql') {
            $dsn = "mysql:host=$host;charset=utf8mb4";
        } else {
            $name = '.' . DIRECTORY_SEPARATOR . $name;
            $dsn = "sqlite:$name";
        }
        try {
            $db = new \PDO($dsn, $user, $pass, [\PDO::ATTR_TIMEOUT => 5]);
            // 如果不存在则创建数据库
            if ($driver == 'mysql') {
                $db->exec("CREATE DATABASE IF NOT EXISTS `$name` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
            }
            return json([
                'error' => 0,
                'msg' => 'success',
            ]);
        } catch (\PDOException $e) {
            return json([
                'error' => -1,
                'msg' => $e->getMessage(),
            ]);
        }
    }
    public function install(Request $request)
    {
        $USING_DB = $request->post('USING_DB', '');
        $ADMIN_PASSWORD = $request->post('ADMIN_PASSWORD', '');
        $driver = $request->post('driver', '');
        $host = $request->post('host', '');
        $name = $request->post('name', '');
        $user = $request->post('user', '');
        $pass = $request->post('pass', '');
        if ($driver == 'sqlite') {
            $name = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . $name;
        }

        $env = <<<EOF
APP_DEBUG = false
ADMIN_PASSWORD = $ADMIN_PASSWORD

# 数据库配置
DB=$USING_DB
DB_DRIVER=$driver
DB_HOST=$host
DB_NAME=$name
DB_USER=$user
DB_PASS=$pass
DB_PORT = 3306
DB_CHARSET = utf8

DEFAULT_LANG = zh-cn
EOF;
        $envPath = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . '.env';
        file_put_contents($envPath, $env);
        if ($USING_DB == 'true') {
            try {
                // 动态配置数据库
                $origin = Config::get('database');
                $origin['connections']['install'] = [
                    'type' => $driver,
                    'database' => $name,
                    'charset' => 'utf8mb4',
                    'prefix' => '',
                    'debug' => true,
                ];
                if ($driver == 'mysql') {
                    $origin['connections']['install']['hostname'] = $host;
                    $origin['connections']['install']['username'] = $user;
                    $origin['connections']['install']['password'] = $pass;
                    $origin['connections']['install']['hostport'] = '3306';
                } else {
                    $origin['connections']['install']['database'] = $name;
                }

                Config::set($origin, 'database');
                $this->initDb($driver);
            } catch (Exception $e) {
                // 删除.env文件
                unlink($envPath);
                return json([
                    'error' => -1,
                    'msg' => '数据库初始化失败',
                    'trace' => [$e->getMessage(), $e->getTraceAsString()]
                ]);
            }
        }

        $result = Setting::updateConfig([
            'db' => $USING_DB == 'true' ? true : false,
            'program_version' => \app\controller\Index::$version,
            'admin_password' => $ADMIN_PASSWORD,
            'site_name' => 'PanDownload',
        ], true);

        if (!$result) {
            // 删除.env文件
            unlink($envPath);
            return json([
                'error' => -1,
                'msg' => '配置文件写入失败',
            ]);
        }

        return json([
            'error' => 0,
            'msg' => 'success',
        ]);
    }
    private static function initDb($type = 'mysql')
    {
        $sqls = [
            "CREATE TABLE IF NOT EXISTS `records` (
                `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                `time` DATETIME NOT NULL,
                `size` bigint NOT NULL,
                `name` varchar(255) NOT NULL,
                `link` TEXT NOT NULL,
                `md5` varchar(255) NOT NULL,
                `ip` varchar(255) NOT NULL,
                `ua` varchar(255) NOT NULL,
                `account` int NOT NULL,
                `fs_id` varchar(255) NOT NULL,
                `expires_at` DATETIME NOT NULL
            )",
            "CREATE INDEX IF NOT EXISTS `idx_fs_id` ON `records` (`fs_id`)",
            "CREATE TABLE IF NOT EXISTS `ip` (
                `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                `type` int(1) NOT NULL,
                `ip` varchar(255) NOT NULL,
                `created_at` DATETIME NOT NULL,
                `remarks` varchar(255) NOT NULL
            )",
            "CREATE TABLE IF NOT EXISTS `account` (
                `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                `name` varchar(255) NOT NULL,
                `cookie` TEXT NOT NULL,
                `status` int(1) NOT NULL,
                `created_at` DATETIME NOT NULL,
                `last_used_at` DATETIME NOT NULL,
                `remarks` varchar(255) NOT NULL
            )"
        ];
        // 启动事务
        Db::connect('install')->startTrans();
        try {
            foreach ($sqls as $sql) {
                if ($type == 'sqlite') {
                    $sql = str_replace('AUTO_INCREMENT', '', $sql);
                    $sql = str_replace('int(11)', 'INTEGER', $sql);
                    $sql = str_replace('int(1)', 'INTEGER', $sql);
                    $sql = str_replace('ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;', '', $sql);
                }
                Db::connect('install')->execute($sql);
            }
            // 提交事务
            Db::connect('install')->commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::connect('install')->rollback();
            throw $e;
        }
    }
}
