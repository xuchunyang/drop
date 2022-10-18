# Drop - 静态网站一键托管

拖拽上传静态网站，使用生成的子域名访问，可以选择绑定自己的域名，子域名和自定义域名都支持
HTTPS。[Netlify Drop](https://app.netlify.com/drop) 替代品。

> 线上演示： https://drop.xuchunyang.cn

由 Laravel + Caddy 实现：

- 利用 Caddy 的 [On-Demand TLS](https://caddyserver.com/docs/automatic-https#on-demand-tls) 技术，为客户自定义的域名，自动申请
  HTTPS 证书

## 安装部署

> 一个完整的 Caddy 配置：[./Caddyfile.example](./Caddyfile.example)

Drop 是一个简单的 Laravel 项目，需要提前了解 Laravel 项目部署。简单了解下 Caddy 也会很有帮组。

下面的例子中：

- `drop.xuchunyang.cn` 为部署域名
- `/srv/drop` 为项目位置

### Laravel 主网站，如 `drop.xuchunyang.cn`

这是我们的主网站，采用 Laravel + Caddy + php-fpm 标准的配置：

```
drop.xuchunyang.cn {
        root * /srv/drop/public

        # 不执行用户上传的 PHP 文件
        @storage not path /storage/*
        php_fastcgi @storage unix//run/php/php-fpm.sock

        file_server
}
```

Drop 把用户上传的静态网站，原封不动地保存到 /public/storage 中，用户可能会上传 PHP 脚本，为了安全，不能执行这些脚本。

### 子域名, 如  `billowing-band-1.drop.xuchunyang.cn`

用户上传的静态网站上传到 storage 后，会自动分配一个子域名，静态网站使用 `file_server` 指令：

```
*.drop.xuchunyang.cn {
        tls {
                dns dnspod {env.DNSPOD_TOKEN}
        }
        file_server browse {
                root `/srv/drop/public/storage/{host}`
        }
}
```

[泛域名证书](https://caddyserver.com/docs/automatic-https#wildcard-certificates)的申请，比单域名的严格，需要用 API 设置
DNS 解析，上面我使用了腾讯云 DNSPod 的 API。

### 用户自定义域名，如 `landing.cadr.xyz`

用户设置自定义域名，添加好 CNAME 解析：

```
$ host landing.cadr.xyz
landing.cadr.xyz is an alias for billowing-band-1.drop.xuchunyang.cn
```

这是我们需要托管用户的自定义域名，比如

    访问
    https://landing.cadr.xyz/css/styles.css
    应该返回：
    /public/storage/billowing-band-1.drop.xuchunyang.cn/css/style.css

这样的"转写"信息必须需要 PHP 代码读取数据库获得，而静态文件需要 Caddy 处理（考虑到性能，不用 PHP
发送静态文件），利用 `X-Accel-Redirect` 完成这一过程：

```
https:// {
        tls {
                on_demand
        }

        root * /srv/drop/public2
        php_fastcgi unix//run/php/php-fpm.sock {
                @accel header X-Accel-Redirect *
                handle_response @accel {
                        root    * /srv/drop/public/storage
                        rewrite * {rp.header.X-Accel-Redirect}
                        file_server
                }
        }
}
```

- `public2/index.php` 会读取 `.env` 中的 `APP_URL`，你要改成实际的，不能再用默认的 `APP_URL=http://localhost`

开启 On-Demand TLS 后，所有的访问都会自动申请 HTTPS 证书，每一个证书需要 Let's Encrypt 单独一一批准，为了安全，我们只考虑确确实实在绑定了域名的用户：

```
{
        on_demand_tls {
                ask https://drop.xuchunyang.cn/check
                interval 2m
                burst 5
        }
}
```
