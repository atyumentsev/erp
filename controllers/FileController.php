<?php
namespace app\controllers;

use app\models\File;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class FileController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors() : array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get file
     * @param integer $id
     * @throws NotFoundHttpException
     * @throws \yii\web\HttpException
     */
    public function actionGetFile($id)
    {
        $file = $this->getFile($id, 'view', true);
        $mimeType = $file->mime;
        /** @var resource $content */
        $content = $file->content;
        //todo: is there more beautiful way?
        $content = fread($content, 20*1024*1024);
        \Yii::$app->response->sendContentAsFile(
            $content,
            //$file->content,
            $file->name,
            ['mimeType' => $mimeType]
        );
    }

    public function actionDelete($id)
    {
        $file = $this->getFile($id, 'delete', true);
        if (!$file->delete()) {
            throw new ServerErrorHttpException('File deleting failed');
        }
        return 'OK';
    }

    /**
     * Load file from database
     *
     * @param integer $id
     * @param string  $perm
     * @param bool $withContent
     *
     * @return File
     * @throws NotFoundHttpException
     */
    private function getFile($id, $perm = 'view', $withContent = false)
    {
        $columns = ['id', 'name', 'mime', 'user_id', 'created_at'];
        if ($withContent) {
            $columns[] = 'content';
        }

        /** @var File $file */
        $file = File::find()
            ->select($columns)
            ->where(['id' => $id])
            ->one();

        if (!$file) {
            throw new NotFoundHttpException("File with id `$id` not found");
        }

        if (false &&  !\Yii::$app->user->can("file.$perm", ['file' => $file])) {
            \Yii::warning("Trying to $perm foreign file with id `$id` (owner: #{$file->user_id})", __METHOD__);
            throw new NotFoundHttpException("File with id `$id` not found");
        }
        return $file;
    }
}