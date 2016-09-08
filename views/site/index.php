<?php 

use app\components\treeBuilder; 

?>
<div class="site-index">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-4">
                <h4>Categories</h4>
                <?php echo treeBuilder::getTreeListLinkFull($data, '/?id=') ?>
            </div>
            <div class="col-lg-8">
                <h4>Products</h4>
                <ul>    
                    <?php foreach($product as $key => $value): ?>
                        <li> <?= $value->title ?> </li>
                    <?php endforeach; ?>
                </ul>    
            </div>
        </div>
    </div>
</div>
