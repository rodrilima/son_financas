<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCategoryCost extends AbstractMigration
{
    public function up() 
    {
        $this->table('category_costs')
            ->addColumn('name', 'string')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->save();
    }

    public function down() 
    {
        $this->table('category_costs')
            ->drop()
            ->save();
    }
}
