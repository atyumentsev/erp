<?php

use app\components\widgets\RbacTree;
use app\models\rbac\PermissionCheckerForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var \yii\web\View         $this
 * @var PermissionCheckerForm $model
 * @var array                 $nodes
 * @var array                 $edges
 * @var array                 $weights
 * @var array                 $assignedRoles
 * @var array                 $permissionCheckResults
 * @var array                 $users
 */

$this->title = Yii::t('admin', 'Permission Checker');
$this->params['breadcrumbs'][] = $this->title;

?>
<style>
    #permission-table-legend {
        width: auto;
    }
</style>


<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'userId')->dropDownList($users); ?>
<?= $form->field($model, 'permissionName')->dropDownList($model->getAllPermissionNames()); ?>
<?= $form->field($model, 'entityId')->input('number'); ?>

<?= Html::submitButton(\Yii::t('admin', 'Check'), ['class' => 'btn btn-accent']) ?>

<?php ActiveForm::end()?>

<?php if (!empty($nodes)) : ?>
    <div class="row">
        <div class="col-md-9">
            <?= RbacTree::widget([
                'containerOptions' => [
                    'style' => [
                        'height'     => '500px',
                        'margin-top' => '30px',
                    ],
                ],
                'edges' => $edges,
                'items' => $nodes,
                'weights' => $weights,
                'assignedRoles' => $assignedRoles,
                'permissionCheckResults' => $permissionCheckResults,
                'layoutOptions' => [
                    'nodeSpacing' => 200,
                    'levelSeparation' => 100,
                    'edgeMinimization' => true,
                    'blockShifting' => true,
                    'treeSpacing' => 300,
                    'parentCentralization' => false,
                    'sortMethod' => 'directed',
                    'direction' => 'DU',
                ],
            ]); ?>
        </div>
        <div class="col-md-3">
            <table class="table table-striped table-bordered detail-view" id="permission-table-legend">
                <caption>Legend</caption>
                <tr>
                    <td style="background: <?= RbacTree::COLOR_PERMISSION_ALLOWED ?>"></td>
                    <td><?= \Yii::t('admin', 'This permission check returned TRUE') ?></td>
                </tr>
                <tr>
                    <td style="background: <?= RbacTree::COLOR_PERMISSION_DENIED ?>"></td>
                    <td><?= \Yii::t('admin', 'This permission check returned FALSE') ?></td>
                </tr>
                <tr>
                    <td style="background: <?= RbacTree::COLOR_ITEM_ASSIGNED ?>"></td>
                    <td><?= \Yii::t('admin', 'This role/permission is assigned to user') ?></td>
                </tr>
                <tr>
                    <td style="background: <?= RbacTree::COLOR_RULE ?>"></td>
                    <td><?= \Yii::t('admin', 'Access rule') ?></td>
                </tr>
                <tr>
                    <td><?= \Yii::t('admin', 'Rounded Rectangle') ?></td>
                    <td><?= \Yii::t('admin', 'Permission') ?></td>
                </tr>
                <tr>
                    <td><?= \Yii::t('admin', 'Ellipse') ?></td>
                    <td><?= \Yii::t('admin', 'Role') ?></td>
                </tr>
            </table>
        </div>
    </div>
<?php endif; ?>
