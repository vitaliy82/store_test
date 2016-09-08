<?php

use yii\db\Migration;

class m160905_075904_init_migrate extends Migration
{
    public function up()
    {
        $this->createTable('language', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'code' => $this->string()
        ]);

        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer()->defaultValue(0),
            'order_id' => $this->integer()->defaultValue(0),
            'name' => $this->string(),
            'img' => $this->string()
        ]);
        
        $this->createTable('category_translation', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'name' => $this->string(),
            'description' => $this->string(),
            'lang_id' => $this->integer()            
        ]);
       
        $this->createTable('product', [                
            'id' => $this->primaryKey(),
            'owner_id' => $this->integer(),//->notNull(),
            'category_id' => $this->integer(),//->defaultValue(1),
            'title' => $this->string(), 
        ]);
        
        $this->createTable('product_translation', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'title' => $this->string(),
            'body' => $this->text(),
            'lang_id' => $this->integer() 
        ]);

        
        $this->addForeignKey(
            'fk-product-category',
            'product',
            'category_id',
            'category',
            'id',
            'CASCADE',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-category-translation',
            'category_translation',
            'category_id',
            'category',
            'id',
            'CASCADE',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-product-translation',
            'product_translation',
            'product_id',
            'product',
            'id',
            'CASCADE',
            'CASCADE'
        );
        
        //
        $this->addForeignKey(
            'fk-category-translation-lang',
            'category_translation',
            'lang_id',
            'language',
            'id',
            'CASCADE',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-product-translation-lang',
            'product_translation',
            'lang_id',
            'language',
            'id',
            'CASCADE',
            'CASCADE'
        );

    }

    public function down()
    {
        $this->dropForeignKey('fk-product-category');
        $this->dropForeignKey('fk-category-translation');
        $this->dropForeignKey('fk-product-translation');
        $this->dropForeignKey('fk-category-translation-lang');
        $this->dropForeignKey('fk-product-translation-lang');
        $this->dropTable('language');
        $this->dropTable('category');
        $this->dropTable('category_translation');
        $this->dropTable('product');
        $this->dropTable('product_translation');
        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
