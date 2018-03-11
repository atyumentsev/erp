<?php
namespace app\commands;

use app\models\User;
use yii\base\Event;
use yii\console\Controller;
use yii\console\Exception;
use yii\db\ActiveRecord;
use yii\helpers\Console;

/**
 * Инициализация справочников
 */
class DictsController extends Controller
{
    public $color = true;

    private $ts;

    const ADMIN_USERNAME = 'admin';
    const DEFAULT_PASSWORD = 'admin';

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

    public function actionIndex()
    {
        if (!Console::confirm("All tables will be cleaned and filled again! Continue?")) {
            return;
        }

        $this->createUsers();

        $this->stdout(" - done\n");
    }


    /**
     * Создание пользователей (для паблишеров, админов и т.д.) со всеми доступами, аттрибутами
     *
     * - Все пользователи создаются с паролем password
     */
    private function createUsers()
    {
        $this->startOperation("Creating users");

        \Yii::$app->db->createCommand()->truncateTable(User::tableName());


        $this->stopOperation();
    }

    /* -------------------------------------------------------------------------------------------------------------- */

    private function startOperation($msg)
    {
        $this->stdout(" - $msg ... ");
        $this->ts = microtime(1);
    }

    private function stopOperation()
    {
        $this->stdout("done ", Console::FG_GREEN);
        $this->stdout("(" . number_format(microtime(1) - $this->ts, 3) . "s)", Console::FG_GREY);
        $this->stdout("\n");
    }
}
