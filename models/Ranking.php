<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ranking".
 *
 * @property int $id
 * @property string $entity_type
 * @property int $entity_id
 * @property int $priority
 * @property int $created_at
 * @property int $updated_at
 */
class Ranking extends ActiveRecord
{
    const ENTITY_TYPE_USER              = 'user';
    const ENTITY_TYPE_CASHFLOW_ITEM     = 'cashflow_item';
    const ENTITY_TYPE_CUST_DEPARTMENT   = 'cust_department';
    const ENTITY_TYPE_COUNTERAGENT      = 'counteragent';
    const ENTITY_TYPE_URGENCY           = 'urgency';
    const ENTITY_TYPE_DUE_DATE          = 'due_date';

    const CLASS_MAP = [
        self::ENTITY_TYPE_CASHFLOW_ITEM     => CashflowItem::class,
        self::ENTITY_TYPE_USER              => User::class,
        self::ENTITY_TYPE_CUST_DEPARTMENT   => Department::class,
        self::ENTITY_TYPE_COUNTERAGENT      => CounterAgent::class,
        self::ENTITY_TYPE_URGENCY           => Urgency::class,
        self::ENTITY_TYPE_DUE_DATE          => DueDate::class,
    ];

    const CALCULATABLE_MAP = [
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ranking';
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
        ];
    }


    public static function getEntityTypes()
    {
        return [
            self::ENTITY_TYPE_CASHFLOW_ITEM     => \Yii::t('app', 'Cashflow Item'),
            self::ENTITY_TYPE_USER              => \Yii::t('app', 'User'),
            self::ENTITY_TYPE_CUST_DEPARTMENT   => \Yii::t('app', 'Customer Department'),
            self::ENTITY_TYPE_COUNTERAGENT      => \Yii::t('app', 'CounterAgent'),
            self::ENTITY_TYPE_DUE_DATE          => \Yii::t('app', 'Due Date'),
            self::ENTITY_TYPE_URGENCY           => \Yii::t('app', 'Urgency'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity_id', 'priority', 'created_at', 'updated_at'], 'integer'],
            [['entity_type'], 'string', 'max' => 60],
            [['entity_type', 'entity_id'], 'unique', 'targetAttribute' => ['entity_type', 'entity_id']],
            [['entity_id', 'priority', 'entity_type'], 'required'],
            ['entity_type', 'in', 'range' => array_keys(self::getEntityTypes())],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'entityTypeReadable' => Yii::t('app', 'Entity Type'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entityName' => Yii::t('app', 'Entity Name'),
            'priority' => Yii::t('app', 'Priority'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getEntityTypeReadable()
    {
        return self::getEntityTypes()[$this->entity_type];
    }

    public function getEntityName()
    {
        $modelClass = self::CLASS_MAP[$this->entity_type];
        if ($modelClass instanceof ActiveRecord) {
            $entity = $modelClass::findOne($this->entity_id);
            if ($entity) {
                return $entity->name;
            }
        } else {
            $list = $modelClass::getDropdownList();
            return $list[$this->entity_id];
        }
        return null;
    }
}
