# Baiduwp-PHP

[ENGLISH](README.md) | [中文](README_ZH.md)

## ⚠️ Disclaimer
Please note that this project is only for learning and research purposes, and the author is not responsible for any legal consequences caused by the use of this project.

This project will not store any files on the server, completely relying on the Baidu Netdisk API interface. If you have question about copyright, please contact Baidu: [https://newcopyright.baidu.com/](https://newcopyright.baidu.com/)

## 📝 Introduction
This project is a PHP version of [baiduwp](htttps://github.com/TkzcM/baiduwp).

It can get the file information of Baidu Netdisk through the API interface and the Cookie (BDUSS) of the SVIP account, and then display it on the web page.

The essence is to use the premium account (SVIP account) to get the download link and send it to the visitor.

## 💡 Contact Us
- Email: moteam.org@gmail.com
- Website: https://www.moteam.top
- GitHub: https://github.com/MoTeam-cn

## 🔧 Installation

### Panel Installation
1. Download the project files from [Releases](https://github.com/MoTeam-cn/baiduwp-php/releases)
2. Create a website in your panel, upload and extract the files to the root directory
3. Bind the domain to the `public` subdirectory
4. Set the rewrite rule to `thinkphp` in your panel
5. Visit `http://<your-domain>/install` and follow the installation guide
6. Go to `http://<your-domain>/admin` to set up normal and SVIP account cookies

### Docker Installation
#### Using SQLite / No Database
1. Install docker
2. Run the following commands:
```
docker pull moteam/baiduwp-php
docker run -d -p 8080:8000 moteam/baiduwp-php
```

#### Using MySQL Database
1. Install docker
2. Run the following commands:
```
docker pull mysql
docker network create --subnet 172.28.0.0/16 mysql-network
docker run -d -e MYSQL_ROOT_PASSWORD="root" --network mysql-network --ip 172.28.0.2 mysql

docker pull moteam/baiduwp-php
docker run -d --network mysql-network --ip 172.28.0.3 -p 8080:8000 moteam/baiduwp-php
```

## 📌 Usage Tips
- Recommended installation order: Docker > Panel > Manual Installation
- Only supports **PHP 8 and 8+**
- Project uses `ThinkPHP` framework
- For manual environment setup, refer to the installation commands in `Dockerfile`
- The API used in this project may cause account speed limits
- You need to configure two `complete Cookies` (both normal and SVIP accounts work) to get download links
  - For Cookie capture guide, see [this tutorial](https://blog.imwcr.cn/2022/11/24/%e5%a6%82%e4%bd%95%e6%8a%93%e5%8c%85%e8%8e%b7%e5%8f%96%e7%99%be%e5%ba%a6%e7%bd%91%e7%9b%98%e7%bd%91%e9%a1%b5%e7%89%88%e5%ae%8c%e6%95%b4-cookie/)
  - Do not log out or change password after getting the Cookie, or it will become invalid

## 📚 Further Reading
- [Changelog](docs/CHANGELOG.md)
- [About This Project](docs/About.md)
- [API Documentation](docs/API.md)

## 🖥️ Demo
![浅色及英文模式](https://s2.loli.net/2023/02/04/cs1EtFXpHDPS2AB.png)
![文件列表](https://s2.loli.net/2023/02/04/hL2pDEyHQFb6BKR.png)
![解析详情](https://s2.loli.net/2023/02/04/GZBsmz6xgShjuA2.png)

## 💡Contact
- Email: yuantuo666@gmail.com
- Telegram: https://t.me/yuantuo666

## Related Works
Following are some related works with this project. If you want to apply to add new project here, please draft new issue with brief introduction. 
- [alist-org/alist](https://github.com/alist-org/alist) 🗂️A file list/WebDAV program that supports multiple storages, powered by Gin and Solidjs.
- [codehub666/94list](https://github.com/codehub666/94list) Baidu netdisk share link analysis and rendering list
- [huankong233/94list-laravel](https://github.com/huankong233/94list-laravel) 94list rebuilt with Laravel
- [z-mio/baiduwp-bot](https://github.com/z-mio/baiduwp-bot) A Baidu netdisk parsing bot based on baiduwp-php API
- [monkeyWie/gopeed-extension-baiduwp](https://github.com/monkeyWie/gopeed-extension-baiduwp) Gopeed Baidu netdisk download extension

## 📃 License
[MIT](LICENSE)

## 🔔 Thanks
- [baiduwp JavaScript Version](https://github.com/TkzcM/baiduwp)
- [PanDownload Website](https://pandownload.com/)
- [Bootstrap Dark Mode](https://github.com/vinorodrigues/bootstrap-dark)
- [ThinkPHP](https://github.com/top-think/think)