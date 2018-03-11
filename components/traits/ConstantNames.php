<?php

namespace app\components\traits;

/**
 * Class ConstantNames
 * @package common\components\traits
 * Трейт предназначен для получения из класса перечня статусов, через рефлексию констант с префиксом STATUS_
 * Если у сущности есть константы STATUS_READY, STATUS_PENDING, STATUS_CLOSED, то должен быть метод,
 * который будет уметь возвращать список этих статусов в виде:
 * [
 *   STATUS_READY => 'Ready',
 *   STATUS_PENDING => 'Pending',
 *   STATUS_CLOSED  => 'Closed',
 * ]
 *
 * А также метод, который может вернуть имя текущего статуса сущности.
 */
trait ConstantNames
{
    /**
     * Возвращает массив с константами, имена которых начинаются с $prefix. Если подходящих констант не найдено,
     * возвращает пустой массив.
     *
     * @param string $prefix Префикс для констант, которые нужно получить. Например: 'STATUS_', 'TYPE_'
     * @return array
     */
    public static function getConstantNames($prefix)
    {
        $class     = new \ReflectionClass(static::class);
        $constants = $class->getConstants();

        $list = [];
        foreach ($constants as $name => $value) {
            if (strpos($name, $prefix) === 0) {
                $list[$value] = self::getStringNameOfConstant($name, $prefix);
            }
        }

        return $list;
    }

    /**
     * Создаёт строковое имя константы, без префикса
     * @param string $constant
     * @param string $prefix
     * @return string
     */
    private static function getStringNameOfConstant($constant, $prefix)
    {
        return ucfirst( strtolower( str_replace('_', ' ', substr($constant, strlen($prefix))) ) );
    }


    /**
     * Возвращает массив констант, где ключами будут ЗНАЧЕНИЕ констант, а значениями - их имена приведёные к строкам.
     * Например, пусть имеется три константы класса:
     * const STATUS_READY = 0;
     * const STATUS_FEATURED = 10;
     * const STATUS_CLOSED = 20;
     *
     * Для такого класса, метод вернёт массив следующего вида:
     * [
     *   0  => 'Ready',
     *   10 => 'Featured',
     *   20 => 'Closed'
     * ]
     *
     * @return array
     */
    public static function getStatusNames()
    {
        return self::getConstantNames('STATUS_');
    }
}