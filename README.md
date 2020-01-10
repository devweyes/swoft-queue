# swoft queue

1\. 介绍
----------------
用户进程/进程池与queue的完美结合，注解切面版queue,支持 redis,rabbitmq(等官方发基础包 开发中)

2\. 使用
----------------
### composer

```
composer require devweyes/queue
```
### 用户进程更多消费者

新增进程数量配置
```php
<?php

use Jcsp\Queue\Helper\Tool;
...
'wsServer' => [
    'class' => \Swoft\WebSocket\Server\WebSocketServer::class,
    ...
    //可配置多个消息消费，视业务量而定
    'process' => array_merge(
                Tool::moreProcess('recvMessageProcess', bean(\Jcsp\WsCluster\Process\RecvMessageProcess::class), 3),
                [
                  //自定义进程
                ]
            )
]
```


### redis驱动queue消费

- redis内存数据库，并不可靠但性能及高，数据量小于10K出队入队速度显著，非常适用于实时异步短消息传输。否则，请使用rabbitmq或其他作为驱动
- 用户进程需继承```Jcsp\Queue\Contract\UserProcess```
- 进程池需实现```Jcsp\Queue\Contract\ProcessInterface```
- 进程需包含三个方法 ```run()```, ```receive()```,  ```fallback()```，分别实现 ```入口```，```消费```，```错误处理```逻辑
- ```run```方法内无需再实现```while(true)``` 的业务,甚至无需任何代码
- ```run()```方法内严禁使用 ```exit()``` ```return```等，```$this->queue```用于自定义队列名以覆盖注解
- ```receive```内```return Result::ACK```为正确消费，其他方法或发生异常均视为消费失败



```php
<?php declare(strict_types=1);

namespace App\Process;

use Jcsp\Queue\Annotation\Mapping\Pull;
use Jcsp\Queue\Result;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\BeanFactory;
use Swoft\Log\Helper\CLog;
use Swoft\Process\Process;
use Jcsp\Queue\Contract\UserProcess;

/**
 * Class MonitorProcess
 *
 * @since 2.0
 *
 * @Bean()
 */
class RecvMessageProcess extends UserProcess
{
    /**
     * @param Process $process
     * @Pull("queue")
     */
    public function run(Process $process): void
    {
        //add queue
        $this->queue = 'new_queue';
        //waite
    }
    /**
     * customer
     * @param $message
     * @return string
     */
    public function receive($message): string
    {
        return Result::ACK;
    }

    /**
     * when error callback
     * @param $message
     * @return string
     */
    public function fallback(\Throwable $throwable, $message): void
    {
        //
        vdump('error', $throwable->getMessage(), 'message',$message);
    }
}

```

### rabbitmq驱动queue消费


In development