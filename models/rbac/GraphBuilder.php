<?php

namespace app\models\rbac;

use yii\db\Query;
use yii\rbac\Item;
use yii\rbac\Role;

class GraphBuilder
{
    /** @var \app\components\rbac\DbManager  */
    private $auth;

    /** @var \yii\rbac\Item[]|Role[] */
    private $rbacItems;

    /** @var array */
    public $weights = [];

    /** @var array */
    private $rootRoles = [];

    /** @var int */
    public $maxRoleWeight = 0;

    public function __construct()
    {
        $this->auth = \Yii::$app->authManager;
        $this->rbacItems = array_merge($this->auth->getRoles(), $this->auth->getPermissions());
        foreach($this->rbacItems as $item) {
            $this->weights[$item->name] = 0;
        }
    }

    /**
     * Fills $this->>weights with items weight recursively
     *
     * @param string $permissionName
     * @param int $level
     */
    public function createRbacTreeRecursive(string $permissionName, int $level)
    {
        $this->weights[$permissionName] = max($this->weights[$permissionName], $level);
        if ($this->rbacItems[$permissionName]->type == Item::TYPE_ROLE) {
            $this->maxRoleWeight = max($this->maxRoleWeight, $this->weights[$permissionName]);
        }

        $parents = (new Query())->select(['child'])
            ->from($this->auth->itemChildTable)
            ->where(['parent' => $permissionName])
            ->column();

        ++$level;
        foreach ($parents as $parent) {
            $this->createRbacTreeRecursive($parent, $level);
        }
    }

    /**
     * @return array i.e. ["ADMIN", "USER"]
     */
    public function getRootRoles()
    {
        if (empty($this->rootRoles)) {
            $this->rootRoles = (new Query)->select('name')
                ->from($this->auth->itemTable . " i")
                ->leftJoin($this->auth->itemChildTable . " ic", "ic.child = i.name")
                ->where('child IS NULL')
                ->orderBy("name DESC")
                ->column();
        }
        return $this->rootRoles;
    }

    /**
     * Creates RBAC Tree, fills weights of all roles and permissions
     */
    public function createRbacTree()
    {
        $rootRoles = $this->getRootRoles();
        foreach ($rootRoles as $roleName) {
            $this->createRbacTreeRecursive($roleName, 1);
        }
    }

    /**
     * Returns array like
     * [
     *     ['parent' => 'ADMIN',  'child' => 'file.delete'],
     *  ...
     * ]
     *
     * @return array
     */
    public function getEdges() : array
    {
        return (new Query())->select(['child', 'parent'])->from($this->auth->itemChildTable)->all();
    }

    /**
     * @return \yii\rbac\Item[]
     */
    public function getItems() : array
    {
        return array_merge($this->auth->getRoles(), $this->auth->getPermissions());
    }

    /**
     * Returns array like
     * [
     *     'ADMIN' => 1,
     *     'aml.view' => 5,
     * ...
     *
     * Weight of permissions could not be smaller than weight of roles.
     *
     * @return array
     */
    public function getCorrectedWeights() : array
    {
        $weights = $this->weights;
        foreach ($weights as $permissionName => $weight) {
            if ($this->rbacItems[$permissionName]->type == Item::TYPE_PERMISSION) {
                $weights[$permissionName] = $weight + $this->maxRoleWeight;
            }
        }
        return $weights;
    }
}
