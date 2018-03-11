<?php
namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * @property int    $id
 * @property string $name
 * @property string $mime
 * @property string $content
 * @property int    $user_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @mixin TimestampBehavior
 */
class File extends ActiveRecord
{
    const MIME_IMAGE = ['png', 'jpg', 'jpeg', 'tiff'];
    const MIME_FILE  = ['pdf'];
    const MIME_ATTACHMENT  = ['pdf', 'png', 'jpg', 'jpeg', 'tiff'];

    /**
     * @inheritdoc
     * @return ActiveQuery
     */
    public static function find()
    {
        // по умолчанию убираем из выборки колонки с содержимым файлов, для экономии памяти
        return parent::find()->select(['id', 'name', 'mime', 'user_id', 'created_at', 'updated_at']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'mime', 'content', 'user_id'], 'required'],
        ];
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

    public function getUrl()
    {
        return Url::to(['/file/get-file', 'id' => $this->id]);
    }


    /* --- Factories ------------------------------------------------------------------------------------------------ */

    /**
     * @param UploadedFile $file
     * @return File
     */
    public static function fromUploadFile(UploadedFile $file)
    {
        return new self([
            'name'    => $file->baseName . '.' . $file->extension,
            'mime'    => $file->type,
            'content' => file_get_contents($file->tempName)
        ]);
    }
}