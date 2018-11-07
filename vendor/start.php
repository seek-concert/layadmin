<?php
use workerman\Worker;
require_once __DIR__ . '/workerman/Autoloader.php';

// 注意：这里与上个例子不同，使用的是websocket协议
$ws_worker = new Worker("websocket://0.0.0.0:40000");

// 启动4个进程对外提供服务
$ws_worker->count = 1;

// 当收到客户端发来的数据后返回hello $data给客户端
$ws_worker->onMessage = function($connection, $data) use ($ws_worker)
{
    $message = json_decode($data,true);
    $msg_type = $message['type'];
    switch($msg_type) {
        case 'init':
            //实例化pdo
            $db1 = new \PDO("mysql:host=172.16.0.8; dbname=layadmin", "layadmin", "zxc123");  //数据库链接
            // uid
            $uid = $message['id'];
            // 设置session
            $_SESSION = [
                'username' => $message['username'],
                'avatar'   => $message['avatar'],
                'id'       => $uid,
                'sign'     => $message['sign']
            ];

            // 通知当前客户端初始化,设置登录状态
            $db1->exec("UPDATE `lay_user` SET `status` = '1' WHERE id=" . $uid);

            $init_message = array(
                'msg_type' => 'init',
                'id'           => $uid,
            );

            $connection->send(json_encode($init_message));

            //查询最近1周有无需要推送的离线信息

            $time = time() - 7 * 3600 * 24;
            $sqls = "select lay_msg.*,lay_user.username as send_username,lay_user.avatar as send_avatar from lay_msg 
left join lay_user on lay_msg.send_id=lay_user.id where lay_msg.receive_id= {$uid} and lay_msg.created_at > {$time} and lay_msg.needsend = 1 order by created_at asc";
            $resMsg = $db1->prepare($sqls);
            $resMsg->execute();
            //var_export($resMsg);

            if( !empty( $resMsg ) ){
                foreach( $resMsg as $key=>$vo ){

                    $log_message = [
                        'msg_type' => 'logMessage',
                        'data' => [
                            'username' => $vo['send_username'],
                            'avatar'   => $vo['send_avatar'],
                            'id'       => $vo['send_id'],
                            'content'  => htmlspecialchars( $vo['content'] ),
                            'timestamp'=> $vo['created_at'] * 1000,
                        ]
                    ];
                    //设置推送状态为已经推送
                    $db1->exec("UPDATE `lay_msg` SET `needsend` = '0' WHERE send_id=" . $vo['send_id']." and receive_id=".$uid);
                    $connection->send(json_encode($log_message));
                }

            }

            return;
            break;
        case 'chatMessage':
            $db1 = new \PDO("mysql:host=172.16.0.8; dbname=layadmin", "layadmin", "zxc123");  //数据库链接
            // 聊天消息
            $to_id = $message['data']['to']['id'];
            $uid = $message['data']['mine']['id'];

            $chat_message = [
                'msg_type' => 'chatMessage',
                'data' => [
                    'username' => $message['data']['mine']['username'],
                    'avatar'   => $message['data']['mine']['avatar'],
                    'id'       => $to_id,
                    'content'  => htmlspecialchars($message['data']['mine']['content']),
                    'timestamp'=> time()*1000,
                ]
            ];

            $connection->send(json_encode($chat_message));
            //聊天记录数组
            $param = [
                'send_id' => $uid?:$message['data']['mine']['id'],
                'receive_id' => $to_id,
                'content' => htmlspecialchars($message['data']['mine']['content']),
                'created_at' => time(),
                'needsend' => 0
            ];

            // 插入
            $rs = $db1->exec("select status from lay_user where id = {$to_id}");
            if( empty( $rs['status'] ) ){
                $param['needsend'] = 1;  //用户不在线,标记此消息推送
            }
            $db1 = new \PDO("mysql:host=172.16.0.8; dbname=layadmin", "layadmin", "zxc123");
            $sqls = "insert into lay_msg(send_id,receive_id,content,created_at,needsend) values 
              ({$param['send_id']},{$param['receive_id']},'{$param['content']}',{$param['created_at']},{$param['needsend']})";
            $db1->exec($sqls);

            return;
            break;
        case 'hide':
        case 'online':
            $status_message = [
                'msg_type' => $msg_type,
                'id'           => $_SESSION['id'],
            ];
            $_SESSION['online'] = $msg_type;

            return;
            break;
        case 'ping':
            return;
        default:
            echo "unknown message $data" . PHP_EOL;
    }

};

// 运行worker
Worker::runAll();