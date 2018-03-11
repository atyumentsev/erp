<?php
namespace app\components;

use yii\base\Event;
use yii\console\Controller;
use yii\console\Exception;
use yii\db\ActiveRecord;
use yii\helpers\Console;

/**
 * Инициализация справочников
 */
class CommandsController extends Controller
{
    public $color = true;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        // ловим ошибки валидации при сохранении
        // ! не обрабатывается ситуация, когда beforeValidate в самой модели вернул false
        // ! сохранения не произойдет, но мы об этом не узнаем по эвентам
        Event::on(ActiveRecord::class, ActiveRecord::EVENT_AFTER_VALIDATE, function ($event) {
            /** @var ActiveRecord $model */
            $model = $event->sender;

            if ($model->hasErrors()) {
                $this->stderr("Error: cant save model: ", Console::FG_RED);
                $this->stderr("{$model->formName()}" . json_encode($model->toArray()) . " ");
                print_r($model->getFirstErrors());
                throw new Exception("creation data failed");
            }
        });

        return parent::beforeAction($action);
    }
}