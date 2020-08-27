<?php
namespace Hostinghelden\Navigation\Block;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;

class Topmenu extends Template {

  protected $menu = [];
  protected $category;
  protected $storemanager;


  /**
   * @param Template\Context $context
   * @param array $data
   */
  public function __construct(
    Template\Context $context,
    StoreManagerInterface $storemanager,
    Category $category,
    CollectionFactory $categoryCollection,
    array $data = []
  ) {
    parent::__construct($context, $data);
    $this->storemanager = $storemanager;
    $this->category = $category;
    $this->categoryCollection = $categoryCollection;
  }

  public function getCategories($catid) {
    $category = $this->category->load($catid);
    $collection = $category->getCollection();
    /* @var $collection \Magento\Catalog\Model\ResourceModel\Category\Collection */
    $collection->addAttributeToSelect(
      'url_key'
    )->addAttributeToSelect(
      'name'
    )->addAttributeToSelect(
      'image_icon'
    )->addAttributeToSelect(
      'all_children'
    )->addAttributeToSelect(
      'is_anchor'
    )->addAttributeToFilter(
      'is_active', 1
    )->addIdFilter(
      $category->getChildren()
    )->setOrder(
      'position',
      \Magento\Framework\DB\Select::SQL_ASC
    )->joinUrlRewrite();
    return $collection;
  }

  public function getStoreId() {
    return $this->storemanager->getStore()->getId();
  }
  public function getRootCategoryId() {
    return $this->storemanager->getStore()->getRootCategoryId();
  }

  public function getMediaUrl() {
    return $this->storemanager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
  }
  public function getMenu() {
    $menu = $this->createMenu($this->getRootCategoryId());
    return $menu;
  }

  public function createMenu($catid) {
    $menu = [];
    foreach($this->getCategories($catid) as $subcat) {
      $menu[] = $this->addItem($subcat);
    }
    return $menu;
  }

  public function getCategoryIconUrl($category) {
    $url   = false;
    $image = $category->getImageIcon();
    if ($image) {
      if (is_string($image)) {
        $url = $this->getMediaUrl() . "catalog/category/" . $image;
      } else {
        throw new \Magento\Framework\Exception\LocalizedException(
          __('Something went wrong while getting the image url.')
        );
      }
    }
    return $url;
  }

  public function addItem($_category) {
    $childs = $this->createMenu($_category->getId());
    return [
      "name" => $_category->getName(),
      "link" => $_category->getUrl(),
      "icon" => $this->getCategoryIconUrl($_category),
      "class" => "active",
      "children" => $childs
    ];
  }


}
