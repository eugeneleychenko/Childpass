<?php

class DirectoryBehavior extends CActiveRecordBehavior
{
    public function getList()
    {
        $items = $this->owner->findAll(array('order' => 'name'));

        $result = array();
        foreach ($items as $item) {
            $result[$item->primaryKey] = $item->name;
        }

        return $result;
    }
}