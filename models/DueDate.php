<?php

namespace app\models;

class DueDate
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
            -14 => \Yii::t('app', 'Expired 2 or more weeks ago'),
            -7 => \Yii::t('app', 'Expired 1 - 2 weeks ago'),
            -1 => \Yii::t('app', 'Expired less than 1 week ago'),
            0 => \Yii::t('app', 'Should be paid today'),
            1 => \Yii::t('app', 'Should be paid tomorrow'),
            6 => \Yii::t('app', 'Less than 1 week left'),
            7 => \Yii::t('app', 'More than 1 week left'),
        ];
    }
}
