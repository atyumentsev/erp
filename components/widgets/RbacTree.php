<?php
namespace app\components\widgets;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\rbac\Item;

class RbacTree extends Widget
{
    const COLOR_ITEM_ASSIGNED      = 'yellow';
    const COLOR_PERMISSION_ALLOWED = '#88ff88';
    const COLOR_PERMISSION_DENIED  = '#FF8080';
    const COLOR_RULE               = '#357EC7';

    /** @var array of HTML options applied to container for Canvas with graph */
    public $containerOptions = [];

    /** @var bool should all the ancestors selected when user selects an item */
    public $highlightParentsOnSelect = false;

    /** @var array */
    public $edges = [];

    /** @var array */
    public $weights = [];

    /** @var array */
    public $items = [];

    /** @var array */
    public $assignedRoles;

    /** @var array */
    public $permissionCheckResults = [];

    /** @var array */
    public $layoutOptions = [];

    /** @var array */
    private $nodes = [];

    /** @var array */
    private $parentsByChild = [];

    /** @var array */
    private $edgesForJs = [];

    public function init()
    {
        $this->fillNodes();
        $this->fillEdges();
        $this->fillParentsByChild();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('rbac_tree', [
            'container'                 => $this->createContainer(),
            'edges'                     => $this->edgesForJs,
            'nodes'                     => $this->nodes,
            'weights'                   => $this->weights,
            'parentsByChild'            => $this->parentsByChild,
            'highlightParentsOnSelect'  => $this->highlightParentsOnSelect,
            'layoutOptions'             => $this->layoutOptions,
        ]);
    }

    /**
     * @return string
     */
    private function createContainer()
    {
        $options = ArrayHelper::merge([
            'tag'   => 'div',
            'style' => [
                'width' => '100%',
                'border' => '1px solid #ddd',
            ],
            'id'    => $this->getId()
        ], $this->containerOptions);

        $this->setId($options['id']);

        $tagName = $options['tag'];
        unset($options['tag']);

        return Html::tag($tagName, '', $options);
    }


    private function fillNodes()
    {
        foreach ($this->items as $item) {
            $node = [
                'id' => $item->name,
                'label' => $item->name,
                'shape' => $item->type == Item::TYPE_PERMISSION ? "box": "ellipse",
            ];

            if (!empty($this->weights[$item->name])) {
                $node['level'] = $this->weights[$item->name];
            }

            $bgColor = $this->getBackgroundColor($item->name);
            if (!empty($bgColor)) {
                $node['color']['background'] = $bgColor;
            }

            $this->nodes[$node['id']] = $node;

            if (isset($item->ruleName)) {
                $node = [
                    'id' => $item->ruleName,
                    'label' => $item->ruleName,
                    'shape' => 'box',
                    'color' => [
                        'background' => self::COLOR_RULE,
                    ],
                ];
                if (!empty($this->weights[$item->name])) {
                    $node['level'] = $this->weights[$item->name];
                }
                $this->nodes[$node['id']] = $node;
            }
        }
        $this->nodes = array_values($this->nodes);
    }

    private function fillEdges()
    {
        foreach ($this->edges as $edge) {
            $this->edgesForJs[] = ['from' => $edge['child'], 'to' => $edge['parent']];
        }

        foreach ($this->items as $item) {
            if (isset($item->ruleName)) {
                $this->edgesForJs[] = ['from' => $item->name, 'to' => $item->ruleName, 'dashes' => true];
            }
        }
    }

    private function fillParentsByChild()
    {
        foreach ($this->edges as $edge) {
            if (!isset($this->parentsByChild[$edge['child']])) {
                $this->parentsByChild[$edge['child']] = [];
            }
            $this->parentsByChild[$edge['child']][] = $edge['parent'];
        }
    }

    /**
     * @param $permissionName
     * @return string
     */
    private function getBackgroundColor($permissionName) : string
    {
        if (isset($this->permissionCheckResults[$permissionName])) {
            if (in_array($permissionName, $this->assignedRoles)) {
                return self::COLOR_ITEM_ASSIGNED;
            } elseif ($this->permissionCheckResults[$permissionName] === true) {
                return self::COLOR_PERMISSION_ALLOWED;
            } else {
                return self::COLOR_PERMISSION_DENIED;
            }
        }
        return '';
    }

}
