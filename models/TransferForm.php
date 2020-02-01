<?php


namespace app\models;


class TransferForm extends Users
{
    public $username;
    public $amount;

    public function rules()
    {
        return [
            [['username','amount'], 'required'],
            ['username' , 'isSelf'],
            ['amount','notThousand'],
            [['amount'], 'double','min' => 0]
        ];
    }
//Check transfer username. user cannot transfer money to self and non-user
    public function isSelf($attr, $params){
        if (\Yii::$app->user->identity->username == $this->username){
            $this->addError($attr,'You cannot transfer to yourself');
        }
        $user = Users::findByUsername($this->username);
        if (!isset($user)){
            $this->addError($attr,'User Not Found 404');

        }
    }
//Check balance if after transfer -1000 return error
    public function notThousand($attr, $params){
        if ((\Yii::$app->user->identity->balance - $this->amount) < -1000){
            $this->addError($attr,'Minimum balance -1000');
        }
    }

}