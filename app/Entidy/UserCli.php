<?php

namespace App\Entidy;

use \App\Db\Database;

use \PDO;

class UserCli
{
    
    public $id;
    public $usuarios_id;
    public $clientes_id;

    public function cadastar()
    {


        $obdataBase = new Database('user_cli');

        $this->id = $obdataBase->insert([

            'usuarios_id'              => $this->usuarios_id,
            'clientes_id'              => $this->clientes_id

        ]);

        return true;
    }

    public function atualizar()
    {
        return (new Database('user_cli'))->update('id = ' . $this->id, [

            'usuarios_id'              => $this->usuarios_id,
            'clientes_id'              => $this->clientes_id
        ]);
    }


    public static function getList($fields = null, $table = null, $where = null, $order = null, $limit = null)
    {

        return (new Database('user_cli'))->select($fields, $table, $where, $order, $limit)
            ->fetchAll(PDO::FETCH_CLASS, self::class);
    }



    public static function getQtd($fields = null, $table = null, $where = null, $order = null, $limit = null)
    {

        return (new Database('user_cli'))->select('COUNT(*) as qtd', 'user_cli', null, null)
            ->fetchObject()
            ->qtd;
    }


    public static function getID($fields, $table, $where, $order, $limit)
    {
        return (new Database('user_cli'))->select($fields, $table, 'id = ' . $where, $order, $limit)
            ->fetchObject(self::class);
    }

    public static function getIDUserCli($fields, $table, $where, $order, $limit)
    {
        return (new Database('user_cli'))->select($fields, $table, 'usuarios_id = ' . $where, $order, $limit)
            ->fetchObject(self::class);
    }


    public static function getIDCli($fields, $table, $where, $order, $limit)
    {
        return (new Database('user_cli'))->select($fields, $table, 'usuarios_id = ' . $where, $order, $limit)
        ->fetchAll(PDO::FETCH_CLASS, self::class);
    }


    public function excluir()
    {
        return (new Database('user_cli'))->delete('id = ' . $this->id);
    }


    public static function getUsuarioPorEmail($email)
    {

        return (new Database('user_cli'))->select('email = "' . $email . '"')->fetchObject(self::class);
    }
}
