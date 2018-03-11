<?php

use app\components\widgets\RbacTree;

/**
 * @var \yii\web\View         $this
 * @var array                 $weights
 * @var array                 $edges
 * @var array                 $items
 */

$this->title = Yii::t('admin', 'RBAC Tree');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= RbacTree::widget([
    'containerOptions' => [
        'style' => [
            'height' => '800px',
        ]
    ],
    'edges' => $edges,
    'weights' => $weights,
    'items' => $items,
    'highlightParentsOnSelect' => true,
    'layoutOptions' => [
        'nodeSpacing' => 80,
        'levelSeparation' => 250,
        'edgeMinimization' => true,
        'blockShifting' => true,
        'sortMethod' => 'directed',
        'direction' => 'LR'
    ],
]); ?>
