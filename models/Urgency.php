<?php

namespace app\models;

class Urgency
{
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
