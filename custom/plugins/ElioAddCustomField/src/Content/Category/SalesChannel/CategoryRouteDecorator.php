<?php declare(strict_types=1);

namespace Elio\AddCustomField\Content\Category\SalesChannel;

use Elio\AddCustomField\Content\Category\Exception\CategoryNotActiveException;
use Shopware\Core\Content\Category\SalesChannel\AbstractCategoryRoute;
use Shopware\Core\Content\Category\SalesChannel\CategoryRouteResponse;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;

class CategoryRouteDecorator extends AbstractCategoryRoute
{
    /**
     * @var AbstractCategoryRoute
     */
    private AbstractCategoryRoute $abstractCategoryRoute;

    public function __construct(AbstractCategoryRoute $abstractCategoryRoute)
    {
        $this->abstractCategoryRoute = $abstractCategoryRoute;
    }

    public function getDecorated(): AbstractCategoryRoute
    {
        return $this->abstractCategoryRoute;
    }

    public function load(string $navigationId, Request $request, SalesChannelContext $context): CategoryRouteResponse
    {

        $category = $this->getDecorated()->load($navigationId, $request, $context);

        if ($category->getCategory()->getExtension('pageActive')) {
            throw new CategoryNotActiveException($category->getCategory()->getId());
        }

        return $category;
    }
}