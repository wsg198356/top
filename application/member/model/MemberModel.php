<?php

namespace app\member\model;

use think\exception\PDOException;
use think\Model;

class MemberModel extends Model {

    protected $name = 'member';

    protected $autoWriteTimestamp = TRUE;

    protected $insert
        = [
            'status'     => 1,
            'group_id'   => 1,
            'sex'        => 1,
            'login_num'  => 0,
            'closed'     => 0,
            'session_id' => '',
        ];

    public function login($validateAcount) {
        try {
            $res = $this -> allowField(TRUE) -> where('account', $validateAcount['account']) -> inc('login_num')->update($validateAcount);
            if (FALSE !== $res) {
                return ['code' => 1, 'url' => '/', 'msg' => '用户登陆成功'];
            } else {
                return
                    [
                        'code' => 0,
                        'data' => '',
                        'msg'  => $this -> getError(),
                    ];
            }
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e -> getMessage()];
        }
    }

    /**
     * [register  用户注册]
     * @autor [王生功][1064860088@qq.com]
     */
    public function register($param) {
        try {
            $res = $this -> allowField(TRUE) -> save($param);
            if (FALSE !== $res) {
                return ['code' => 1, 'data' => 'index', 'msg' => '用户注册成功，请登录！'];
            } else {
                return json(
                    [
                        'code' => -1,
                        'data' => '',
                        'msg'  => $this -> getError(),
                    ]
                );
            }
        } catch (PDOException $e) {
            return ['code' => -2, 'data' => '', 'msg' => $e -> getMessage()];
        }
    }
}