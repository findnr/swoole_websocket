# swoole_websock

swoole开发一个实时聊天系统,使用websocket

# 后台相关介绍
- 后台使用swoole的websocket 服务端 (异步风格),调用异步风格服务端程序的 start 方法，此种启动方式会在事件回调中创建协程容器,所以是以协程的形式为客户进行交互
- 使用了Coroutine\Channel,可以通过实时通知有用户登录,然后把在所有的用户信息推送到每一个用户.
- 使用了高性能共享内存Swoole\Table来共享不同进程之间的相互通信,可以使用每一个进程都可以获取到所有的用户信息,保证数据的统一.
- 使用了毫秒定时器Swoole\Timer,检测到Coroutine\Channel中有数据就实时向前台推送所有在线的用户信息,5分钟检测一次在线用户,向用户推送所有的在线用户信息

# 仓库的的前台页面地址
https://github.com/findnr/swoole_websocket_html
# 前台开发流程
```sh
#第一步
git clone https://github.com/findnr/swoole_websocket_html
#第二步
cd swoole_websocket_html
#第三步
pnpm install
#第四步
pnpm dev
```
