# Baiduwp-PHP

[ENGLISH](README.md) | [中文](README_ZH.md)

PanDownload 网页复刻版，PHP 语言版<br/>

## ⚠️ 重要声明
本项目是 [baiduwp](htttps://github.com/TkzcM/baiduwp) 的 PHP 语言实现，使用 PHP 语言重写了原项目的功能。项目使用百度公开 API 接口获取下载链接。需要配置您自己的百度网盘账号后才可以使用。

本工具无任何破解功能，所有代码开源，仅供学习参考，请遵守相关法律法规，不得将本项目用于商业用途，使用本项目造成的一切后果与项目开发者无关。

相关法律案例如下：
1. 中国裁判文书网《林蔚群提供侵入、非法控制计算机信息系统程序、工具罪一审刑事判决书》
2. 中国裁判文书网《北京度友科技有限公司等与罗庆等不正当竞争纠纷一审民事判决书》

## 🔎 实现原理
首先，网站访客输入分享链接地址，本工具会连接百度服务器获取分享链接中的文件信息，处理后展示在网页中。在访客选定需要下载的文件后，本工具会通过百度网盘的公开 API 接口获取下载链接并显示给访客。

本质就是模拟用户操作，获取下载地址后发送给网站访客。本工具仅显示下载链接，不提供实际的下载服务。

如您的帐号未开通百度网盘的 SVIP 服务，则只能获取到限速的下载链接，使用第三方工具下载时的下载速度和使用网盘客户端一致。

不论是否开通百度网盘 SVIP，使用本项目可能都导致您的 IP 或账号等被百度限速，甚至封禁。

## 📝 联系我们
- Email: moteam.org@gmail.com
- 官网: https://www.moteam.top
- GitHub: https://github.com/MoTeam-cn

## 📝 介绍

![浅色首页](https://s2.loli.net/2023/04/04/yegBh8HXaNCqixb.png)
![深色首页](https://s2.loli.net/2023/04/04/Ff1ub4MJxVsHYhZ.png)
![文件列表](https://s2.loli.net/2023/04/04/4XOrj9xlFYqSyhw.png)
![解析详情](https://s2.loli.net/2023/04/04/aVPoxJ52zCZLpIK.png)
![后台页面](https://s2.loli.net/2023/04/04/dzvxNqO82WrM4lQ.png)

## 🔧 安装及设置

### 宝塔面板 / 虚拟主机安装
1. 进入 [Releases](https://github.com/MoTeam-cn/baiduwp-php/releases) 下载项目文件
2. 在宝塔面板创建网站，上传项目文件到网站根目录并解压
3. 在 网站 —— 站点修改 —— 子目录绑定 中，绑定网站域名到子目录 `public`
4. 在 网站 —— 站点修改 —— 伪静态 中，选择 `thinkphp` 并保存
5. 进入网站 `http://<网站域名>/install`，按照页面提示进行安装
6. 进入 `http://<网站域名>/admin` 的系统设置页面中设置 普通 和 SVIP账号 的 Cookie

### Docker 安装
#### 使用 SQLite 数据库 / 不使用数据库
1. 安装 docker
2. 执行下面的命令
```
docker pull moteam/baiduwp-php
docker run -d -p 8080:8000 moteam/baiduwp-php
```

#### 使用 MySQL 数据库
1. 安装 docker
2. 执行下面的命令
```
docker pull mysql
docker network create --subnet 172.28.0.0/16 mysql-network
docker run -d -e MYSQL_ROOT_PASSWORD="root" --network mysql-network --ip 172.28.0.2 mysql

docker pull moteam/baiduwp-php
docker run -d --network mysql-network --ip 172.28.0.3 -p 8080:8000 moteam/baiduwp-php
```

## 📌 使用提示
- 推荐安装方式： Docker > 宝塔/EasyPanel 面板 > 手动安装
- 仅支持 **PHP 8 和 8+**
- 项目采用 `ThinkPHP` 框架
- 如果想自行搭建环境，请参考 `Dockerfile` 中的安装命令配置环境
- 本项目使用的接口容易导致账号限速
- 需要配置两个 `完整 Cookie`(普通账号和SVIP账号均可) 才可以获取下载链接，获取方法需抓包
  - 获取 Cookie 参考 [图文教程](https://blog.imwcr.cn/2022/11/24/%e5%a6%82%e4%bd%95%e6%8a%93%e5%8c%85%e8%8e%b7%e5%8f%96%e7%99%be%e5%ba%a6%e7%bd%91%e7%9b%98%e7%bd%91%e9%a1%b5%e7%89%88%e5%ae%8c%e6%95%b4-cookie/)
  - 获取之后请勿退出账号或修改密码，否则 Cookie 会失效

## 📚 进一步阅读
- [更新日志](docs/CHANGELOG.md)
- [关于这个项目](docs/About.md)
- [API 文档](docs/API.md)

## 📝 项目计划
因内外多种因素，本项目将不会增加新功能，仅维护现存的 BUG。若接口失效，本项目将停止更新。

## 💡 寻求帮助
如果遇到问题请先 **仔细阅读此文档** 、查看[以前的议题](https://github.com/yuantuo666/baiduwp-php/issues)<br />
如果还是无法解决，请在 [Issues](https://github.com/yuantuo666/baiduwp-php/issues) 中按模板提出问题，不符合规范的议题可能被直接关闭。

## 相关作品
下面是一些与本项目相关的作品，如果你想申请添加新项目，请提起新的议题。
- [alist-org/alist](https://github.com/alist-org/alist) 🗂️A file list/WebDAV program that supports multiple storages, powered by Gin and Solidjs. / 一个支持多存储的文件列表/WebDAV程序，使用 Gin 和 Solidjs。
- [codehub666/94list](https://github.com/codehub666/94list) 百度网盘分享链接分析渲染列表辅助下载开源程序
- [huankong233/94list-laravel](https://github.com/huankong233/94list-laravel) 94list使用laravel重构
- [z-mio/baiduwp-bot](https://github.com/z-mio/baiduwp-bot) 一个基于baiduwp-php API的百度网盘解析bot
- [monkeyWie/gopeed-extension-baiduwp](https://github.com/monkeyWie/gopeed-extension-baiduwp) Gopeed 百度网盘下载扩展。

## 📃 License
[MIT](LICENSE)

## 🔔 Thanks
- [baiduwp JavaScript 版](https://github.com/TkzcM/baiduwp "baiduwp 项目")
- [PanDownload 网站](https://pandownload.com/ "PanDownload 网站")
- [Bootstrap 深色模式](https://github.com/vinorodrigues/bootstrap-dark "bootstrap-dark 项目")
- [ThinkPHP](https://github.com/top-think/think)
