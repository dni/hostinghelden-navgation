<?php
namespace Hostinghelden\Navigation\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface {
  /**
   * @var \Magento\Eav\Setup\EavSetupFactory
   */
  private $eavSetupFactory;

  public function __construct(\Magento\Eav\Setup\EavSetupFactory $eavSetupFactory) {
    $this->eavSetupFactory = $eavSetupFactory;
  }
  public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context) {
    $setup->startSetup();
    if (version_compare($context->getVersion(), '9.0.1', '<')) {
      $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
      $eavSetup->addAttribute(
        \Magento\Catalog\Model\Category::ENTITY,
        'image_icon', [
          'type'      	=> 'varchar',
          'label'      	=> 'Image - Icon',
          'input'     	=> 'image',
          'required' 	=> false,
          'sort_order'  => 6,
          'backend'	=> 'Hostinghelden\Navigation\Model\Category\Attribute\Backend\Icon',
          'global'    	=> \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
          'group'    	=> 'General Information',
        ]
      );
    }
    $setup->endSetup();
  }
}
?>
