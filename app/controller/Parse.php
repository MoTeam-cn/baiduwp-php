<?php

namespace app\controller;

use app\BaseController;
use app\Request;
use app\Parse as GlobalParse;

class Parse extends BaseController
{
    /**
     * 解析链接 - 获取列表
     */
    public function list(Request $request)
    {
        $surl = $request->post('surl', ''); // 获取surl
        $pwd = $request->post('pwd', ''); // 获取密码
        $dir = $request->post('dir', ''); // 获取目录
        $result = GlobalParse::getList($surl, $pwd, $dir);
        return json($result);
    }

    /**
     * 解析链接 - 获取链接
     */
    public function link(Request $request)
    {
        $fs_id = $request->post('fs_id', '');
        $randsk = $request->post('randsk', '');
        $shareid = $request->post('shareid', '');
        $uk = $request->post('uk', '');
        $name = $request->post('name', '');
        $size = $request->post('size', 0);
        $md5 = $request->post('md5', '');
        $dlink = $request->post('dlink', '');
        $sign = $request->post('sign', '');

        // 验证签名
        if (md5($dlink . $fs_id . $size) !== $sign) {
            return json([
                'error' => -1,
                'msg' => '签名验证失败'
            ]);
        }

        $result = GlobalParse::download($fs_id, $randsk, $shareid, $uk, $name, $size, $md5, $dlink);
        return json($result);
    }
}
