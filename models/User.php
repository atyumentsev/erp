<?php

namespace app\models;

use app\components\traits\ConstantNames;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * Class User
 * @package app\models
 *
 * @property integer $id
 * @property string $code_1c
 * @property string $username
 * @property string $short_name
 * @property string $password_hash
 * @property string $token
 * @property string $name
 * @property string $parent_name
 * @property string $locale
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    use ConstantNames;

    const STATUS_ACTIVE     = 1;
    const STATUS_INACTIVE   = 2;
    const STATUS_DELETED    = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password_hash'], 'required'],
            [['username', 'access_token', 'name', 'password_hash', 'code_1c', 'parent_name', 'locale', 'short_name'], 'string'],
            ['username', 'unique'],
            [['access_token'], 'default', 'value'=> null],
            ['status', 'in', 'range' => array_keys(self::getStatusNames())],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
            ],
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'status'     => self::STATUS_DELETED,
                    'updated_at' => time(),
                ],
                'replaceRegularDelete' => true, // mutate native `delete()` method
            ],
        ];
    }

    public static function findByUsername($username)
    {
        return self::findOne([
            'username'  => $username,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * User constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->status = self::STATUS_ACTIVE;

        parent::__construct($config);
    }

    /**
     * @param string $password
     *
     * @return $this
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        if (empty($password)) {
            $this->password_hash = '';

            return $this;
        }

        $this->password_hash = self::getSaltedPassword($password);
        return $this;
    }

    /**
     * @param string $password plain password
     *
     * @return bool
     */
    public function isPasswordValid($password)
    {
        if (empty($this->password_hash)) {
            return false;
        }
        return $this->password_hash === self::getSaltedPassword($password);
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isDeleted()
    {
        return $this->status == self::STATUS_DELETED;
    }

    public static function getSaltedPassword($password) : string
    {
        return md5(\Yii::$app->params['password_salt'] . $password);
    }

//    public function beforeSave($insert)
//    {
//        if (parent::beforeSave($insert)) {
//            if ($this->isNewRecord) {
//                $this->auth_key = \Yii::$app->security->generateRandomString();
//            }
//            return true;
//        }
//        return false;
//    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return self|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne([
            'id' => $id,
        ]);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return self|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return array
     */
    public static function getDropdownList() : array
    {
        $currencies = self::find()
            ->select('id, name')
            ->orderBy('name ASC')
            ->asArray()
            ->all();

        return [null => ''] + array_column($currencies, 'name', 'id');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSettings()
    {
        return $this->hasOne(UserSettings::className(), ['user_id' => 'id']);
    }

    private static function transliterate($name)
    {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        return strtr($name, $converter);
    }

    public static function getUsername($name)
    {
        $ret = self::transliterate($name);
        if (($pos = strpos($ret, '(')) > 0) {
            $ret = substr($ret, 0, $pos - 1);
        }
        $ret = strtolower($ret);
        $ret = str_replace(['.', ' '], '', $ret);
        $ret = str_replace('\'', 'y', $ret);
        return $ret;
    }

    public static function getShortName(string $name)
    {
        if (preg_match('/([a-zA-Zа-яА-ЯёЁ]+) ([a-zA-Zа-яА-ЯёЁ]+) ([a-zA-Zа-яА-ЯёЁ]+)/u', $name, $matches)) {
            print_r($matches);
            return $matches[1] . ' ' . mb_substr($matches[2], 0, 1) . '.' . mb_substr($matches[2], 0, 1) . '.';
        } else {
            return $name;
        }
    }
}
