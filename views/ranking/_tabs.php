<?php
/**
 * @var $active_tab
 */
$items = [
    [
        'label' => \Yii::t('app', 'Entire List'),
        'url' => '/ranking/index',
        '_key' => 'index',
    ],
    [
        'label' => \Yii::t('app', 'Cashflow Items'),
        'url' => '/ranking/cashflow-items',
        '_key' => 'cashflow_items',
    ],
    [
        'label' => \Yii::t('app', 'Urgency'),
        'url' => '/ranking/urgency',
        '_key' => 'urgency',
    ],
    [
        'label' => \Yii::t('app', 'Due Date'),
        'url' => '/ranking/due-date',
        '_key' => 'due_date',
    ],
    [
        'label' => \Yii::t('app', 'CounterAgents'),
        'url' => '/ranking/counteragents',
        '_key' => 'counteragents',
    ],
    [
        'label' => \Yii::t('app', 'Users'),
        'url' => '/ranking/users',
        '_key' => 'users',
    ],
    [
        'label' => \Yii::t('app', 'Departments'),
        'url' => '/ranking/departments',
        '_key' => 'departments',
    ],

];

foreach ($items as $i => $item) {
    $items[$i]['active'] = ($item['_key'] == $active_tab);
}

echo \yii\bootstrap\Tabs::widget([
    'items' => $items,
]);
?>
<br>
