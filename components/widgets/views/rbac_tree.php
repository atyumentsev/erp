<?php

use app\components\widgets\RbacTree;
use yii\web\View;

/**
 * @var View                  $this
 * @var string                $container
 * @var bool                  $highlightParentsOnSelect
 * @var array                 $weights
 * @var array                 $edges
 * @var array                 $nodes
 * @var array                 $parentsByChild
 * @var array                 $layoutOptions
 */

/** @var RbacTree $widget */
$widget = $this->context;
?>

<?= $container ?>
<?php
$js = '
    $(function() {
        var container = document.getElementById("' . $widget->getId() . '");

        // provide the data in the vis format
        var data = {
            nodes: new vis.DataSet(' . json_encode($nodes) . '),
            edges: new vis.DataSet(' . json_encode($edges) . ')
        };

        var options = {
            edges: {
                arrows: {
                    to: {enabled: true, scaleFactor: 1, type: "arrow"},
                    middle: {enabled: false, scaleFactor: 1, type: "arrow"},
                    from: {enabled: false, scaleFactor: 1, type: "arrow"}
                }
            },
            nodes: {
                physics: false
            },
            layout: {
                hierarchical: ' . json_encode($layoutOptions) . '
            }
        };
';

if ($highlightParentsOnSelect) {
    $js .= '
        options.nodes.chosen = {
            node: function (values) {
                values.color = "orange";
            }
        };';
}

$js .= '
        // initialize your network!
        var network = new vis.Network(container, data, options);
        network.fit();
';

if ($highlightParentsOnSelect) {
    $js .= '
        var edgesByChild = ' . json_encode($parentsByChild) . ';

        network.on("selectNode", function(params) {
            var parents = getParents(params["nodes"][0]);
            parents.push(params["nodes"][0]);
            network.selectNodes(parents, false);
        });
        network.on("deselectNode", function() {
            network.unselectAll();
        });

        function getParents(node)
        {
            var ret = [];
            if (edgesByChild[node] === undefined) {
                return ret;
            }
            var parents = edgesByChild[node];
            for (var i = 0; i < parents.length; ++i) {
                ret.push(parents[i]);
                var ancestors = getParents(parents[i]);
                for (var j = 0; j < ancestors.length; ++j) {
                    ret.push(ancestors[j]);
                }
            }
            return ret;
        }
';
}
$js .= '});';

$this->registerJs($js);
