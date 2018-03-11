<?php

namespace app\components\rbac;

class DbManager extends \yii\rbac\DbManager
{
    /**
     * @var array
     */
    private $assignmentsCache = [];

    /**
     * @inheritdoc
     */
    public function getAssignments($userId)
    {
        if (!isset($this->assignmentsCache[$userId])) {
            $this->assignmentsCache[$userId] = parent::getAssignments($userId);
        }

        return $this->assignmentsCache[$userId];
    }

    /**
     * Returns true if there is at least one assignment made to user.
     * If user has only default roles it will return false.
     *
     * @param int $userId
     * @return bool
     */
    public function hasAssignments($userId) : bool
    {
        return count($this->getAssignments($userId)) > 0;
    }
}
