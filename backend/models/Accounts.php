<?php

namespace app\models;

use Yii;
use yii\base\Model;
use common\models\User;
use borales\extensions\phoneInput\PhoneInputValidator;
//use app\models\RegisterUser;

//use common\models\User;
/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $Fullnames
 * @property string $username
 * @property string $user_department
 * @property string $user_phone_no
 * @property string $user_type
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $verification_token
 */
class Accounts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }



 

    /**
     * {@inheritdoc}
     */


    public function rules()
    {
         return [
            [['Fullnames',  'user_department', 'user_phone_no', 'user_type', 'created_at',  'office_location'], 'required'],
            [['Fullnames'], 'string', 'max' => 200],
            [['user_department', 'user_phone_no', 'user_type'], 'string', 'max' => 100],

            [['user_phone_no'], PhoneInputValidator::className(), 'region' => ['KE', 'UG', 'RW']],  

            ['password_hash', 'required' ],
            ['password_hash', 'string', 'min' => 6],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        
        return [
            'id' => 'ID',
            'Fullnames' => 'Fullnames',
            'username' => 'Username',
            'user_department' => 'User Department',
            'user_phone_no' => 'User Phone No',
            'user_type' => 'User Type',
             'office_location' =>  'Office Location', 
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'verification_token' => 'Verification Token',
        ];
    }

    public function setPassword($password_hash)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password_hash);
    }

  public function beforeSave($insert) {

        if ($insert) {
            $this->setPassword($this->password_hash);
        } else {
            if (!empty($this->password_hash)) {
                $this->setPassword($this->password_hash);
            } else {
                $this->password_hash = (string) $this->getOldAttribute('password_hash');
            }
        }
        return parent::beforeSave($insert);
    }
     

}
