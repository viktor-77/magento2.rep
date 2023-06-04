<?php
declare(strict_types=1);

namespace Tsg\Blog\Model;

class Blog extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    private const TABLE_NAME = 'blog_details';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Tsg\Blog\Model\ResourceModel\Blog::class);
    }

    /**
     * @return string[]
     */
    public function getIdentities(): array
    {
        return [self::TABLE_NAME . '_' . $this->getId()];
    }
}
