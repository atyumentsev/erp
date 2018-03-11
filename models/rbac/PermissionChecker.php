<?php
namespace app\models\rbac;

use app\components\rbac\DbManager;
use yii\db\Query;
use yii\rbac\Role;

class PermissionChecker
{
    /** @var \app\components\rbac\DbManager  */
    private $auth;

    /** @var array */
    public $assignedRoles;

    /** @var \yii\rbac\Item[]|Role[] */
    private $rbacItems;

    /** @var array */
    public $edges = [];

    /** @var array */
    public $nodes = [];

    /** @var array */
    public $weights = [];

    /** @var int */
    public $maxRoleWeight = 0;

    /** @var array */
    public $permissionCheckResults = [];

    public function __construct(int $userId, array $params)
    {
        /** @var DbManager auth */
        $this->auth = \Yii::$app->authManager;
        $this->rbacItems = array_merge($this->auth->getRoles(), $this->auth->getPermissions());
        $this->userId = $userId;
        $this->params = $params;

        $assignedRoles = [];
        foreach ($this->auth->getAssignments($userId) as $assignment) {
            $assignedRoles[] = $assignment->roleName;
        }
        foreach ($this->auth->defaultRoles as $role) {
            $assignedRoles[] = $role;
        }
        $this->assignedRoles = $assignedRoles;
    }

    /**
     * fills $this->edges, $this->nodes and $this->rules
     *
     * @param string $permissionName
     * @param int $level
     */
    public function createPermissionTree(string $permissionName, $level = 1)
    {
        $this->weights[$permissionName] = isset($this->weights[$permissionName]) ? max($level, $this->weights[$permissionName]) : $level;
        if (isset($this->nodes[$permissionName])) {
            return;
        }

        $this->nodes[$permissionName] = $this->rbacItems[$permissionName];
        $this->permissionCheckResults[$permissionName] = $this->auth->checkAccess($this->userId, $permissionName, $this->params);

        $query = new Query();

        $parents = $query->select(['parent'])
            ->from($this->auth->itemChildTable)
            ->where(['child' => $permissionName])
            ->column();

        $level++;

        foreach ($parents as $parent) {
            $this->edges[] = ['parent' => $parent, 'child' => $permissionName];
            $this->createPermissionTree($parent, $level);
        }
    }
}
