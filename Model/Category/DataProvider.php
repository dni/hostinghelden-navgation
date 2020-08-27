<?php
namespace Hostinghelden\Navigation\Model\Category;

class DataProvider extends \Magento\Catalog\Model\Category\DataProvider {
  protected function getFieldsMap()
  {
    $fields = parent::getFieldsMap();
    $fields['content'][] = 'image_icon'; // NEW FIELD
    return $fields;
  }
}
