<?php

namespace app\models;

class Urgency
{
    const VERY_URGENT = 0;
    const URGENT = 1;
    const MEDIUM = 2;
    const LOW = 3;

    /**
     * @return array
     */
    public static function getDropdownList() : array
    {
        return [null => ''] + self::getList();
    }

    /**
     * @return array
     */
    public static function getList() : array
    {
        return [
            0 => \Yii::t('app', 'Very Urgent'),
            1 => \Yii::t('app', 'Urgent'),
            2 => \Yii::t('app', 'Medium'),
            3 => \Yii::t('app', 'Low'),
        ];
    }
}
