<?php
namespace app\models\rbac;

use app\models\User;
use yii\base\Model;
use yii\rbac\Role;

class PermissionCheckerForm extends Model
{
    /** @var \yii\rbac\ManagerInterface */
    private $auth;

    /** @var string */
    public $permissionName;

    /** @var int */
    public $entityId;

    /** @var int */
    public $userId;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['permissionName'], 'required'],
            [['entityId', 'userId'], 'integer'],
            [['userId'], 'required'],
            [['userId'], 'userExists'],
        ];
    }

    /**
     * @param $attribute
     */
    public function userExists($attribute)
    {
        if(!User::findOne($this->$attribute)) {
            $this->addError($attribute, \Yii::t('admin', 'User with this id not found'));
        }
    }

    /**
     * Role constructor.
     *
     * @param Role|null $role
     * @param array     $config
     */
    public function __construct(Role $role = null, array $config = [])
    {
        parent::__construct($config);
        $this->auth = \Yii::$app->authManager;
    }

    /**
     * @return array
     */
    public function getAllPermissionNames() : array
    {
        $permissions = $this->auth->getPermissions();
        $ret = [];
        foreach ($permissions as $permission) {
            $ret[$permission->name] = $permission->name;
        }
        asort($ret);
        return $ret;
    }

    /**
     * @return bool
     */
    public function check() : bool
    {
        $params = [];
        if (!empty($this->entityId)) {
            $params['entity_id'] = $this->entityId;
        }
        return $this->auth->checkAccess($this->userId, $this->permissionName, $params);
    }
}
