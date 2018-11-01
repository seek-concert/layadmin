<?php
use workerman\Worker;
require_once __DIR__ . '/workerman/Autoloader.php';

// 注意：这里与上个例子不同，使用的是websocket协议
$ws_worker = new Worker("websocket://0.0.0.0:40000");

// 启动4个进程对外提供服务
$ws_worker->count = 4;

$ws_worker->onConnect =function ($connection){
    $connection->send(json_encode(['msg_type'=>'init']));
};

// 当收到客户端发来的数据后返回hello $data给客户端
$ws_worker->onMessage = function($connection, $data)
{
    $message = json_decode($data,true);
    $message_type = $message['type'];
//    switch($message_type) {
//        case 'init':
//            // uid
//            $uid = $message['id'];
//            // 设置session
//            $_SESSION = [
//                'username' => $message['username'],
//                'avatar'   => $message['avatar'],
//                'id'       => $uid,
//                'sign'     => $message['sign']
//            ];
//
//            // 通知当前客户端初始化
//            $init_message = array(
//                'message_type' => 'init',
//                'id'           => $uid,
//            );
//            $connection->send(json_encode($init_message));
//
//            //查询最近1周有无需要推送的离线信息
//            $db1 = new \PDO("mysql:Server=172.16.0.8; Database=cmDB", "sa", "123456");  //数据库链接
//            $time = time() - 7 * 3600 * 24;
//            $sqls = "select id,fromid,fromname,fromavatar,timeline,content from snake_chatlog where toid= {$uid} and timeline > {$time} and type = 'friend' and needsend = 1";
//            $resMsg = $db1->prepare($sqls);
//            $resMsg->execute();
//            //var_export($resMsg);
//            if( !empty( $resMsg ) ){
//
//                foreach( $resMsg as $key=>$vo ){
//
//                    $log_message = [
//                        'message_type' => 'logMessage',
//                        'data' => [
//                            'username' => $vo['fromname'],
//                            'avatar'   => $vo['fromavatar'],
//                            'id'       => $vo['fromid'],
//                            'type'     => 'friend',
//                            'content'  => htmlspecialchars( $vo['content'] ),
//                            'timestamp'=> $vo['timeline'] * 1000,
//                        ]
//                    ];
//
//                    Gateway::sendToUid( $uid, json_encode($log_message) );
//
//                    //设置推送状态为已经推送
//                    $db1->exec("UPDATE `snake_chatlog` SET `needsend` = '0' WHERE id=" . $vo['id']);
//
//                }
//            }
//
//            //查询当前的用户是在哪个分组中,将当前的链接加入该分组
//            $ret = $db1->exec("select `groupid` from `snake_groupdetail` where `userid` = {$uid} group by `groupid`");
//            if( !empty( $ret ) ){
//                foreach( $ret as $key=>$vo ){
//                    Gateway::joinGroup($client_id, $vo['groupid']);  //将登录用户加入群组
//                }
//            }
//            unset( $ret );
//            unset($db1);
//            return;
//            break;
//        case 'addUser' :
//            //添加用户
//            $add_message = [
//                'message_type' => 'addUser',
//                'data' => [
//                    'type' => 'friend',
//                    'avatar'   => $message['data']['avatar'],
//                    'username' => $message['data']['username'],
//                    'groupid'  => $message['data']['groupid'],
//                    'id'       => $message['data']['id'],
//                    'sign'     => $message['data']['sign']
//                ]
//            ];
//            Gateway::sendToAll( json_encode($add_message), null, $client_id );
//            return;
//            break;
//        case 'delUser' :
//            //删除用户
//            $del_message = [
//                'message_type' => 'delUser',
//                'data' => [
//                    'type' => 'friend',
//                    'id'       => $message['data']['id']
//                ]
//            ];
//            Gateway::sendToAll( json_encode($del_message), null, $client_id );
//            return;
//            break;
//        case 'addGroup':
//            //添加群组
//            $uids = explode( ',', $message['data']['uids'] );
//            $client_id_array = [];
//            foreach( $uids as $vo ){
//                $ret = Gateway::getClientIdByUid( $vo );  //当前组中在线的client_id
//                if( !empty( $ret ) ){
//                    $client_id_array[] = $ret['0'];
//
//                    Gateway::joinGroup($ret['0'], $message['data']['id']);  //将这些用户加入群组
//                }
//            }
//            unset( $ret, $uids );
//
//            $add_message = [
//                'message_type' => 'addGroup',
//                'data' => [
//                    'type' => 'group',
//                    'avatar'   => $message['data']['avatar'],
//                    'id'       => $message['data']['id'],
//                    'groupname'     => $message['data']['groupname']
//                ]
//            ];
//            Gateway::sendToAll( json_encode($add_message), $client_id_array, $client_id );
//            return;
//            break;
//        case 'joinGroup':
//            //加入群组
//            $uid = $message['data']['uid'];
//            $ret = Gateway::getClientIdByUid( $uid ); //若在线实时推送
//            if( !empty( $ret ) ){
//                Gateway::joinGroup($ret['0'], $message['data']['id']);  //将该用户加入群组
//
//                $add_message = [
//                    'message_type' => 'addGroup',
//                    'data' => [
//                        'type' => 'group',
//                        'avatar'   => $message['data']['avatar'],
//                        'id'       => $message['data']['id'],
//                        'groupname'     => $message['data']['groupname']
//                    ]
//                ];
//                Gateway::sendToAll( json_encode($add_message), [$ret['0']], $client_id );  //推送群组信息
//            }
//
//            return;
//            break;
//        case 'addMember':
//            //添加群组成员
//            $uids = explode( ',', $message['data']['uid'] );
//            $client_id_array = [];
//            foreach( $uids as $vo ){
//                $ret = Gateway::getClientIdByUid( $vo );  //当前组中在线的client_id
//                if( !empty( $ret ) ){
//                    $client_id_array[] = $ret['0'];
//
//                    Gateway::joinGroup($ret['0'], $message['data']['id']);  //将这些用户加入群组
//                }
//            }
//            unset( $ret, $uids );
//
//            $add_message = [
//                'message_type' => 'addGroup',
//                'data' => [
//                    'type' => 'group',
//                    'avatar'   => $message['data']['avatar'],
//                    'id'       => $message['data']['id'],
//                    'groupname'     => $message['data']['groupname']
//                ]
//            ];
//            Gateway::sendToAll( json_encode($add_message), $client_id_array, $client_id );  //推送群组信息
//            return;
//            break;
//        case 'removeMember':
//            //将移除群组的成员的群信息移除，并从讨论组移除
//            $ret = Gateway::getClientIdByUid( $message['data']['uid'] );
//            if( !empty( $ret ) ){
//
//                Gateway::leaveGroup($ret['0'], $message['data']['id']);
//
//                $del_message = [
//                    'message_type' => 'delGroup',
//                    'data' => [
//                        'type' => 'group',
//                        'id'       => $message['data']['id']
//                    ]
//                ];
//                Gateway::sendToAll( json_encode($del_message), [$ret['0']], $client_id );
//            }
//
//            return;
//            break;
//        case 'delGroup':
//            //删除群组
//            $del_message = [
//                'message_type' => 'delGroup',
//                'data' => [
//                    'type' => 'group',
//                    'id'       => $message['data']['id']
//                ]
//            ];
//            Gateway::sendToAll( json_encode($del_message), null, $client_id );
//            return;
//            break;
//        case 'chatMessage':
//            $db1 = new \PDO("sqlsrv:Server=192.168.1.157; Database=cmDB", "sa", "123456");  //数据库链接
//            // 聊天消息
//            $type = $message['data']['to']['type'];
//            $to_id = $message['data']['to']['id'];
//            $uid = $_SESSION['id']?:$message['data']['mine']['id'];
//
//            $chat_message = [
//                'message_type' => 'chatMessage',
//                'data' => [
//                    'username' => $_SESSION['username']?:$message['data']['mine']['username'],
//                    'avatar'   => $_SESSION['avatar']?:$message['data']['mine']['avatar'],
//                    'id'       => $type === 'friend' ? $uid : $to_id,
//                    'type'     => $type,
//                    'content'  => htmlspecialchars($message['data']['mine']['content']),
//                    'timestamp'=> time()*1000,
//                ]
//            ];
//            //聊天记录数组
//            $param = [
//                'fromid' => $uid?:$message['data']['mine']['id'],
//                'toid' => $to_id,
//                'fromname' => $_SESSION['username']?:$message['data']['mine']['username'],
//                'fromavatar' => $_SESSION['avatar']?:$message['data']['mine']['avatar'],
//                'content' => htmlspecialchars($message['data']['mine']['content']),
//                'timeline' => time(),
//                'needsend' => 0
//            ];
//            switch ($type) {
//                // 私聊
//                case 'friend':
//                    // 插入
//                    $param['type'] = 'friend';
//                    if( empty( Gateway::getClientIdByUid( $to_id ) ) ){
//                        $param['needsend'] = 1;  //用户不在线,标记此消息推送
//                    }
//
//                    $db1 = new \PDO("sqlsrv:Server=192.168.1.157; Database=cmDB", "sa", "123456");  //数据库链接
//                    $sqls = "insert into snake_chatlog(\"fromid\",\"toid\",\"fromname\",\"fromavatar\",\"content\",\"timeline\",\"needsend\",\"type\") values
//                      ('".$param['fromid']."','".$param['toid']."',N'".$param['fromname']."','".$param['fromavatar']."',N'".$param['content']."','".$param['timeline']."','".$param['needsend']."','".$param['type']."')";
//                    $resMsg = $db1->exec($sqls);
//                    return Gateway::sendToUid($to_id, json_encode($chat_message));
//                // 群聊
//                case 'group':
//                    $param['type'] = 'group';
//                    $db1->insert('snake_chatlog')->cols( $param )->query();
//                    return Gateway::sendToGroup($to_id, json_encode($chat_message), $client_id);
//            }
//            return;
//            break;
//        case 'hide':
//        case 'online':
//            $status_message = [
//                'message_type' => $message_type,
//                'id'           => $_SESSION['id'],
//            ];
//            $_SESSION['online'] = $message_type;
//            Gateway::sendToAll(json_encode($status_message));
//            return;
//            break;
//        case 'ping':
//            return;
//        default:
//            echo "unknown message $data" . PHP_EOL;
//    }

};

// 运行worker
Worker::runAll();