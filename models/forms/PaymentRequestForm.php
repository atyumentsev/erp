<?php

namespace app\models\forms;

use app\models\File;
use app\models\PaymentRequest;
use app\models\PaymentRequestFile;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class PaymentRequestForm extends PaymentRequest
{
    const MAX_FILE_SIZE = 20*1024*1024;

    public $invoice_date_readable = null;
    public $payment_date_readable = null;
    public $deadline_readable = null;
    public $attachment = null;

    public function rules()
    {
        $rules = [
            //dates
            [[
                'invoiceDateReadable',
                'paymentDateReadable',
                'dueDateReadable',
                'expectedDeliveryReadable',
                'desiredPaymentDateReadable',
            ], 'date', 'format' => 'php:d.m.Y'],
            [[
                'invoiceDateReadable',
                'paymentDateReadable',
                'dueDateReadable',
                'contractСDateReadable',
                'expectedDeliveryReadable',
            ], 'safe'],
            // money
            [[
                'originalPriceReadable',
                'requiredPaymentReadable',
                'priceRubReadable',
                'requiredPaymentRubReadable',
            ], 'number'],
            ['requiredPaymentReadable', 'compare', 'compareAttribute' => 'originalPriceReadable', 'operator' => '<=', 'type' => 'number'],
            ['requiredPaymentRubReadable', 'compare', 'compareAttribute' => 'priceRubReadable', 'operator' => '<=', 'type' => 'number'],
            // attachments
            [['attachment'], 'file',
                'skipOnEmpty' => true,
                'extensions'  => File::MIME_ATTACHMENT,
                'maxFiles'    => 4,
                'maxSize'     => self::MAX_FILE_SIZE,
            ],
            [[
                'originalPriceReadable',
                'requiredPaymentReadable',
            ], 'required'],
        ];

        return ArrayHelper::merge(parent::rules(), $rules);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'originalPriceReadable' => \Yii::t('app', 'Original Price'),
            'requiredPaymentReadable' => \Yii::t('app', 'Required Payment'),
            'priceRubReadable' => \Yii::t('app', 'Price, RUB'),
            'requiredPaymentRubReadable' => \Yii::t('app', 'Required Payment, RUB'),
        ];
        return ArrayHelper::merge($labels, parent::attributeLabels());
    }

    /**
     * @param $value
     */
    public function setInvoiceDateReadable($value)
    {
        if (!empty($value)) {
            $this->invoice_date = date('Y-m-d', strtotime($value));
        }
    }

    /**
     * @return null|string
     */
    public function getInvoiceDateReadable()
    {
        return parent::getInvoiceDateReadable();
    }

    /**
     * @param string $value
     */
    public function setPaymentDateReadable(string $value)
    {
        if (!empty($value)) {
            $this->payment_date = date('Y-m-d', strtotime($value));
        }
    }

    /**
     * @return null|string
     */
    public function getPaymentDateReadable()
    {
        return parent::getPaymentDate();
    }

    /**
     * @param string $value
     */
    public function setDueDateReadable(string $value)
    {
        if (!empty($value)) {
            $this->due_date = date('Y-m-d', strtotime($value));
        }
    }

    /**
     * @param string $value
     */
    public function setContractDateReadable(string $value)
    {
        if (!empty($value)) {
            $this->contract_date = date('Y-m-d', strtotime($value));
        }
    }

    /**
     * @param string $value
     */
    public function setExpectedDeliveryReadable(string $value)
    {
        if (!empty($value)) {
            $this->expected_delivery = date('Y-m-d', strtotime($value));
        }
    }

    /**
     * @return null|string
     */
    public function getExpectedDeliveryReadable()
    {
        return parent::getExpectedDelivery();
    }

    /**
     * @param string $value
     */
    public function setDesiredPaymentDateReadable(string $value)
    {
        if (!empty($value)) {
            $this->desired_payment_date = date('Y-m-d', strtotime($value));
        };
    }

    /**
     * @return null|string
     */
    public function getDesiredPaymentDateReadable()
    {
        return parent::getDesiredPaymentDate();
    }

    /* --- Human-readable sums -------------------------------------------------------------------------------------- */
    public function setOriginalPriceReadable($value)
    {
        if (empty($value)) {
            return;
        }
        if (!empty($this->originalCurrency)) {
            $this->original_price = $this->originalCurrency->getMoneyAmountFromUnits($value);
        }
        $this->original_price = $value * 100;
    }

    public function setRequiredPaymentReadable($value)
    {
        if (empty($value)) {
            return;
        }
        if (!empty($this->originalCurrency)) {
            $this->required_payment = $this->originalCurrency->getMoneyAmountFromUnits($value);
        }
        $this->required_payment = $value * 100;
    }

    public function setPriceRubReadable($value)
    {
        if (empty($value)) {
            return;
        }
        $this->original_price = $value * 100;
    }

    public function setRequiredPaymentRubReadable($value)
    {
        if (empty($value)) {
            return;
        }
        $this->original_price = $value * 100;
    }

    public function load($data = [], $formName = null)
    {
        $file = UploadedFile::getInstance($this, 'attachment');

        if (!empty($file) && $file->error !== UPLOAD_ERR_OK) {
            if ($file->error === UPLOAD_ERR_INI_SIZE || $file->error === UPLOAD_ERR_FORM_SIZE) {
                $this->addError('attachment', \Yii::t('yii', 'The file "{file}" is too big. Its size cannot exceed {formattedLimit}.', [
                    'file' => $file->name,
                    'formattedLimit' => \Yii::$app->formatter->asShortSize(self::MAX_FILE_SIZE)]
                ));
            } else {
                $this->addError('attachment', \Yii::t('app', 'Fail to upload file "{file}", error code: {code}', [
                    'file' => $file->name,
                    'code' => $file->error
                ]));
            }

            return false;
        }

//        if (DynamicModel::validateData(compact('files'), [
//            ['files', 'file', 'extensions' => File::MIME_FILE, 'maxFiles' => 4]
//        ])->validate()) {
//            $this->attachment = $files;
//        }

        return parent::load($data, $formName)
            || !empty($file_res);
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $transaction = \Yii::$app->getDb()->beginTransaction();
        if (!parent::save($runValidation, $attributeNames)) {
            $transaction->rollBack();
            return false;
        }

        $file = UploadedFile::getInstance($this, 'attachment');
        if ($file) {
            try {
                $file = new File([
                    'name' => $file->name,
                    'mime' => $file->type,
                    'content' => file_get_contents($file->tempName),
                    'user_id' => \Yii::$app->user->id,
                ]);
                if (!$file->save()) {
                    throw new Exception(\Yii::t('app', 'Failed saving file'));
                }

                $prFile = new PaymentRequestFile([
                    'file_id' => $file->id,
                    'payment_request_id' => $this->id,
                ]);
                $prFile->save();
            } catch (Exception $e) {
                $transaction->rollBack();
                $this->addError('attachment', \Yii::t('app', 'Fail to upload file "{file}", error: {error}', [
                    'file' => $file->name,
                    'error' => $e->getMessage()
                ]));
                return false;
            }
        }
        $transaction->commit();
        return true;
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $year = date('Y');
            $cnt = self::find()
                ->select('id')
                ->where([
                    '>=', 'created_at', strtotime(date("{$year}-01-01"))
                ])
                ->count();
            $this->internal_number = ($cnt + 1) . '-' . ($year % 100);
        }
        return parent::beforeSave($insert);
    }
}
