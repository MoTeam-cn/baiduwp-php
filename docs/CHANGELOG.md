# Update
### `4.0.4` 版本：
- 更新日期：2025-06-22
- 修改内容：
  - 💥重要变更
    - 项目组织变更为 MoTeam-cn
    - 更新项目联系方式和官网信息
  - ♻代码优化
    - 简化 GitHub Actions 工作流
    - 移除 Docker 相关构建步骤
    - 优化安装文档和说明
    - 统一中英文文档结构

### `4.0.3` 版本：
- 更新日期：2023-11-24
- 修改内容：
  - ⚠错误修复
    - 修复无法自动更换帐号问题
    - 增加错误提示

### `4.0.2` 版本：
- 更新日期：2023-11-03
- 修改内容：
  - ⚠错误修复
    - 修复无法解析 4G 以上文件
  - 新功能
    - 支持 Apache 环境下的子目录和伪静态自动配置（Nginx 仍然需要手动设置）

### `4.0.1` 版本：
- 更新日期：2023-09-28
- 修改内容：
  - ⚠错误修复
    - 修复 docker 无法自动创建数据库的
    - 修复首页账号状态检测总是显示限速
  - ♻代码优化
    - 更新 API 文档

### `4.0.0` 版本：
- 更新日期：2023-09-24
- 修改内容：
  - 💥新增功能
    - 支持 SQLite 数据库
  - ♻代码优化
    - 使用 ThinkPHP 重构

### `3.0.2` 版本：
- 更新日期：2023-04-07
- 修改内容：
  - ⚠错误修复
    - 修复限速账号检查
    - 修复不安全网站无法复制下载地址
    - 修复后台无法获取SVIP账号状态
  - ♻代码优化
    - 改进后台管理错误提示
    - 改进翻译函数
    - 后端账号状态分离至API接口
    - 代码中版权信息改为项目地址

### `3.0.1` 版本：
- 更新日期：2023-04-05
- 修改内容：
  - ⚠错误修复
    - 修复根目录下压缩包被识别为文件夹的问题 #255 #256 #257

### `3.0.0` 版本：
- 更新日期：2023-04-04
- 修改内容：
  - 💥新增功能
    - 新增后端API接口及文档 #237
    - 后台获取账号状态增加缓存 #253
    - 更新UI
  - ♻代码优化
    - 重构解析有关操作为类

### `2.2.7` 版本：
- 更新日期：2023-03-15
- 修改内容：
  - 💥新增功能
    - 支持 docker 部署
  - ⚠错误修复
    - fix 未开启数据库无法下载 #251
  - ♻代码优化
    - 运行时检查 Cookie 设置
    - 优化错误提示

### `2.2.6` 版本：
- 更新日期：2023-03-09
- 修改内容：
  - ⚠错误修复
    - fix 更新提示"不再提示"逻辑 #197
    - fix 一直提示升级2.2.6 #244
  - ♻代码优化
    - 安装时自动检查Cookie设置
    - 链接失效自动重新生成sign
    - DEBUG模式相关信息显示在控制台中
    - 删除无用代码
    - 格式化部分代码

### `2.2.5` 版本：
- 更新日期：2022-11-24
- 修改内容：
  - ⚠错误修复
    - 修复 下载报错 9019 问题 #225 #227

### `2.2.4` 版本：
- 更新日期：2022-10-24
- 修改内容：
  - ⚠错误修复
    - 修复 因百度对下载接口调整导致的失效问题 #224
  - ♻代码优化
    - 可发送下载任务至文件蜈蚣下载 #187

### `2.2.3` 版本：
- 更新日期：2022-10-07
- 修改内容：
  - ⚠错误修复
    - 修复 因百度 09.28 对sign接口调整导致的失效问题 #219 #221
  - ♻代码优化
    - 支持识别新版本分享链接 #216
    - 提取码错误提示完善 #214 #217 #218
### `2.2.2` 版本：
- 更新日期：2022-09-22
- 修改内容：
  - ⚠错误修复
    - 完成 微信API 解析模式
    - 修复 因百度 09.21 调整导致的解析文件夹 失效问题

### `2.2.1` 版本：
- 更新日期：2022-09-17
- 修改内容：
  - ⚠错误修复
    - 修复 [#191 Possible SQLI and XSS vulnerabilities](https://github.com/yuantuo666/baiduwp-php/issues/191)
    - 修复 [#160 【BUG 反馈】文件名中的 ' 没有转义](https://github.com/yuantuo666/baiduwp-php/issues/160)
  - ♻代码优化
    - 代码结构调整

### `2.2.0` 版本：
- 更新日期：2021-09-21
- 修改内容：
  - 💥新增功能
    - ✨支持老版本链接兼容加载
    - ✨会员账号批量导入支持设置 STOKEN 以及独自用户名
      - 格式：BDUSS----STOKEN----账号名称
    - 增加默认语言选项
    - 更新提示增加展开动画，且可以取消提示
    - 语言切换支持苹果 Safari 浏览器 zh-cn 标识
    - 增加安装提示
  - ⚠错误修复
    - 修复加载或切换页面白屏问题
    - 修复深色模式下首页输入框高度错误
    - settings.php 以及 install.php 页面更新提示与 ready.js 同步
    - 修复二维码内容无法加载问题
    - 安装页面设置站点默认语言 bug 修复
  - ♻代码优化
    - 请求静态文件时会带上版本号，减少更新时的因缓存导致的bug
    - 小屏幕不展示二维码
      - 手机端展示二维码时会挡住下载链接，导致无法复制
    - 获取文件失败提示内容修改

### `2.1.9.1` 版本：
- 更新日期：2021-07-22
- 修改内容：
  - 安全性更新
    - 修复 API SQL 注入问题 #148
  - 问题修复
    - 修复 `install` 和 `settings` 页面更新提示有误的问题
    - 修复 `install` 写入 HTTP 响应正文后仍写入响应头的 bug（取消了自动跳转功能）

### `2.1.9` 版本：
- 更新日期：2021-07-14
- 修改内容：
  - 新增功能
    - 下载链接生成二维码 #123
    - 自动检查更新
  - 功能优化
    - 在跟随浏览器模式下，颜色模式随浏览器设置实时更新
    - 优化颜色模式逻辑
    - 优化用户设置页面前端逻辑
    - 优化二维码生成
    - 优化样式表加载
    - 优化 JS 逻辑和加载
  - 错误修复
    - 修复了正则表达式有误导致的子域名无法正常使用 Aira2 下载问题 #137
  - 其它
    - 更新 .gitignore
    - 格式化代码

### `2.1.8` 版本：
- 更新日期：2021-06-27
- 修改内容：
  - 💥新增功能
    - 增加对 IP 段的黑白名单设置
  - ⚠错误修复
    - 修复带单引号的文件名写入数据库时出错的问题 #131
    - 修复用户界面显示的解析链接有效时间并不会随设置变化的问题 #132

### `2.1.7` 版本：
- 更新日期：2021-05-18
- 修改内容：
  - 💥新增功能
    - 新增aria2安卓端
    - 新增解析链接失效时间设置
    - 关于页面自动生成二维码
  - ⚠错误修复
    - 修复语言设置错误提示中转跳用户设置页面链接错误
    - 修复手机端推送 aria2 因文件夹不存在出错
  - ♻代码优化
    - 在新标签页打开Motrix首页
    - ws(s)链接检测支持IPv6

### `2.1.6` 版本：
- 更新日期：2021-05-01
- 修改内容：
  - 💥新增功能
    - 新增Motrix下载方式
  - ⚠错误修复
    - 修复aria2推送token问题
  - ♻代码优化
    - 隐藏未启用数据库时奇怪的提示
    - 增加推送错误提示
    - 处理一些文字格式
    - ws(s)链接时检测是否有效
    - aira存储信息方式修改为localStorage

### `2.1.5` 版本：
- 更新日期：2021-04-03
- 修改内容：
  - 💥新增功能
    - 账号状态检查
    - Aria2在线管理页面
  - ⚠错误修复
    - 修复未开启数据库情况下无法正常获取文件下载地址   GitHub: @kwxiaozhu
    - 修复GetDir()支持PHP8  GitHub: @zzjin
    - 改用WebSocket推送下载链接到aria2
  - ♻代码优化
    - 增加账号失效提示
    - 版权信息改为Github项目页面
    - 删除不必要的版本检测和版本号

### `2.1.4` 版本：
- 更新日期：2021-03-28
- 修改内容：
  - 💥新增功能
    - 支持通过微信API获取50MB以下文件
  - ⚠错误修复
    - 修复由于百度网盘更新页面js代码引起的项目失效

### `2.1.3` 版本：
- 更新日期：2021-03-14
- 修改内容：
  - 💥新增功能
    - 兼容老方法获取randsk(BDCLND) #84
    - aria2推送时附上文件名 #78 GitHub: @kwxiaozhu
  - ⚠错误修复
    - 安装页面未处理默认情况丢失前缀 #76
  - ♻代码优化
    - 首页安装后状态提示完善 #76 #81
    - 根页面获取失败提示完善
    - 账号拉黑提示 #83 #84 #86

###  `2.1.2` 版本：
- 更新日期：2021-02-20
- 备注：2.1.0版本和2.1.1版本存在安装问题，请勿安装
- 修改内容：
  - ⚠错误修复
    - 修复无法安装问题

### [不稳定] `2.1.1` 版本：
- 更新日期：2021-02-18
- 修改内容：
  - ⚠错误修复
    - 修复数据库中会员账号失效后一直刷新页面问题
    - 修复旧版本无法正常升级问题

## [不稳定] `2.1.0` 版本：
- 更新日期：2021-02-17
<!-- 同志们，写更新日志要细致啊，不要写笼统的！ -->
- 修改内容：
  - 💥新增功能
    - 安装程序 `install.php` 自动检测旧版本配置文件 `config.php` 是否存在，若存在自动导入旧版本配置
    - 增加选择是否取消下载次数提醒功能
    - ✨安装时支持保留数据库数据
    - ✨后台管理页面支持删除数据
    - ✨增加四种SVIP账号切换模式
    - 增加首页公告自定义功能
  - 💪安全增强
    - 安装程序 `install.php` 自动检测是否安装过，如果安装则需进入管理员页面登录
  - ⚠错误修复
    - 修复部分页面检查密码功能失效问题
    - 修复首页小圆点无颜色错误
    - 修复不支持色彩模式的浏览器无法显示 `Sweetalert2` 弹窗问题
    - 修复解析数据一直为 `2.00GB` 问题
    - 修复管理员密码错误不提示
  - ♻代码优化
    - ✨将 `settings.php` 内部分请求方式改为 `ajax` ，增加加载提示框 <!-- 搞了四个小时，累死 -->
    - 优化提示文本（语法、严谨程度等），给一些提示框增加图标
    - 增加部分配置异常地处理程序
    - 优化部分 PHP 和 JavaScript 代码

## [后台管理系统] `1.4.5`：
- 更新日期：2021-01-25
- 修改内容
  - 增加后台系统
  - 增加数据统计功能
  - 增加限速账号自动切换功能
  - 增加API接口文件
  - 增加解析记录查询
  - IP黑白名单
  - 下载次数限制修改
-  [Commit记录](https://github.com/yuantuo666/baiduwp-php/commit/a2f76c9d9f4c70d349279631d0d0dba01cee07ef) （1,169 additions and 62 deletions.）

http://www.dupan.cc/ （站长QQ33703259） 所发布的源码已被篡改，添加了后台并加密，添加的bduss会被上传网站后台，请勿下载使用

## [稳定] `1.4.3` 版本：
- 更新日期：2020-10-21
- 修改内容
  - 后台增加MySQL数据库，保存8小时内解析文件。
  - 限制同一IP及设备的解析次数。

### `1.4.2` 版本：
- 更新日期：2020-08-29
- 修改内容
  - 列表页面新增超时提醒，5min后弹窗提示。
  - 修复在线播放功能，在设置UA情况下可以播放50MB以上文件。
  - 优化代码，删除打开文件夹每次查询密码是否正确代码。
  - 加入运行时间计算，在控制台中可以查看。
  - 将SVIP的BDUSS分离开，便于后期维护。
  - 隐藏旧链接显示的sharelinkXXX-XXX文件夹（此文件夹无法正常打开）。
  - 增加调试模式，便于反馈问题。
  - 增加自动从分享文本中提取验证密码功能。

### `1.4.1` 版本：
- 更新日期：2020-08-27
- 修改内容
  - 修改POST内容，让调用接口暂时失效
  - 增加直链解析，可以不设置UA下载（不过并不稳定，且只支持50MB以下文件）

### `1.4.0` 版本：
- 更新日期：2020-08-27
- 修改内容
  - 增加推送至aria2功能
  - 增加面包屑导航，便于寻找指定文件夹
  - 完善错误提示，获取列表超时会提示
  - 修复特殊路径报错（url传入时未编码）

### `1.3.7` 版本：
- 更新日期：2020-08-25
- 修改内容
  - 修复密码验证问题
  - 增加版本显示

### `1.3.6` 版本：
- 更新日期：2020-08-19
- 修改内容
  - 修改了因为浮点数精度造成下载未知错误的问题 GitHub: @apiee （因为合并的问题，导致丢失了贡献者信息）

### `1.3.5` 版本：
- 更新日期：2020-08-17
- 修改内容：
  - 优化后端逻辑和效率
  - 优化代码
  - 优化错误时提示
  - 修复浏览器中点击下载链接，传递 Referer 导致概率性出错的问题
  - 增加PHP版本过老提示

### `1.3.3` 版本：
- 更新日期：2020-08-17
- 修改内容：
  - 修复缺少文件或直接访问 `config.php` 和 `functions.php` 时出错的问题

### `1.3.1` 版本：
- 更新日期：2020-08-16
- 修改内容：
  - 优化错误时提示

### `1.3.0` 版本：
- 更新日期：2020-08-15
- 修改内容：
  - 支持打开子文件夹
  - 支持下载子文件夹内的文件
  - 使用 SESSION 保存客户端登录状态

### `1.2.3` 版本：
- 更新日期：2020-08-15
- 修改内容：
  - 优化文件加载
  - 将一些可能会丢失的远程文件复制到本地
  - 减少访问百度 API 的次数
  - 优化代码
  - 正在支持文件夹中（仍在实现中）
  - 此版本可能不稳定，谨慎升级！

### `1.2.2` 版本：
- 更新日期：2020-08-14
- 修改内容：
  - 配置、函数与程序分离（`后端`）
  - 优化后端逻辑和效率
  - 优化用户体验
  - 优化发生错误时的用户体验

### `1.2.1` 版本：
- 更新日期：2020-08-14
- 修改内容：
  - 配置、函数与程序分离（`php`）
  - 修复 POST 方法访问 `?download` 参数不齐全出错的问题
  - 修复未配置或配置了普通用户的 `BDUSS` 和 `STOKEN` 时显示空链接的问题
  - 优化程序效率
  - 使用函数减少重复工作的代码量
  - 增加注释
  - 优化前端代码

### `1.2.0` 版本：
- 更新日期：2020-08-14
- 修改内容：
  - 优化打开文件夹的表现（原来是直接提示不可用，现在可以跳转到百度网盘官方的分享页面）
  - 样式、JavaScript 与页面分离（`前端`）

### `1.1.2` 版本：
- 更新日期：2020-08-14
- 修改内容：
  - 修复 GET 方法访问 `index.php?download` 出错的问题
  - 修复 POST 方法访问 `index.php?download` 参数不齐全出错的问题
  - 修复未配置或者配置了普通用户的 BDUSS 和 STOKEN 时无法获取下载链接显示空链接的问题
  - 优化数据传输
  - 优化用户体验


### `1.1.1` 版本：
- 更新日期：2020-08-13
- 修改内容：
  - 修复 errno 不是 -21 且不正常时 HTTP 500 服务器错误的问题
  - 格式化代码


### `1.1` 版本：
- 更新日期：2020-08-13
- 修改内容：
  - 选择是否需要密码功能
  - 配置与程序分离

### `1.0` 版本：
- 更新日期：2020-08-11
- 修改内容：项目创建
