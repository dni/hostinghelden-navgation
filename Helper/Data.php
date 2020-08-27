<?php
namespace Hostinghelden\Navigation\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper {

  public function getCategoryIconUrl($category) {
    $url   = false;
    $image = $category->getImageIcon();
    if ($image) {
      if (is_string($image)) {
        $url = $this->_storeManager->getStore()->getBaseUrl(
          \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        ) . 'catalog/category/' . $image;
      } else {
        throw new \Magento\Framework\Exception\LocalizedException(
          __('Something went wrong while getting the image url.')
        );
      }
    }
    return $url;
  }
}
