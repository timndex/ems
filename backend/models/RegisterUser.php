<?php

namespace app\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\behaviors\TimestampBehavior;
use borales\extensions\phoneInput\PhoneInputValidator;


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
class RegisterUser extends Model
{
    /**
     * {@inheritdoc}
     */
    public $username;
    public $email;
    public $password_hash;
    public $Fullnames;
    public $user_department;
    public $user_phone_no;
    public $office_location;
    public $user_type;
    public $created_at = 0;
    public $updated_at = 0;

    /**
     * {@inheritdoc}
     */
    public $newPassword;


    public function rules()
    {
         return [
            [['Fullnames',  'user_department', 'user_phone_no', 'user_type', 'created_at','office_location'], 'required'],
            [['Fullnames'], 'string', 'max' => 200],
            [['user_department', 'user_phone_no', 'user_type'], 'string', 'max' => 100],

             [['user_phone_no'], PhoneInputValidator::className(), 'region' => ['KE', 'UG', 'RW']],            

           ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password_hash', 'required' ,'on' => 'insert'],
            ['password_hash', 'string', 'min' => 6],

        ];
    }

    /**
     * {@inheritdoc}
     */
 
     public function registeruser()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->Fullnames = $this->Fullnames;
        $user->user_department = $this->user_department;
        $user->user_phone_no = $this->user_phone_no;
        $user->user_type = $this->user_type;
        $user->office_location = $this->office_location;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->created_at = $this->created_at;
        //$user->updated_at = $this->updated_at;
        $user->setPassword($this->password_hash);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
       return $user->save();
       // && $this->sendEmail($user);

    }

    public function updateuser()

    {
        $user = User::findOne($id);
        $user->Fullnames = $this->Fullnames;
        $user->user_department = $this->user_department;
        $user->user_phone_no = $this->user_phone_no;
        $user->user_type = $this->user_type;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->updated_at = $this->updated_at;
        $user->password_hash = Yii::$app->getSecurity()->generatePasswordHash($this->password_hash);
           return $user->update();

        } 


      protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
