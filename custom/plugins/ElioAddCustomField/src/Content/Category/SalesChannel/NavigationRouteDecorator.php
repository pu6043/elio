<?php declare(strict_types=1);

namespace Elio\AddCustomField\Content\Category\SalesChannel;

use Elio\AddCustomField\Content\Category\Exception\CategoryNotActiveException;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Shopware\Core\Content\Category\CategoryEntity;
use Shopware\Core\Content\Category\SalesChannel\AbstractCategoryRoute;
use Shopware\Core\Content\Category\SalesChannel\AbstractNavigationRoute;
use Shopware\Core\Content\Category\SalesChannel\CategoryRouteResponse;
use Shopware\Core\Content\Category\SalesChannel\NavigationRouteResponse;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;
use function PHPUnit\Framework\throwException;

class NavigationRouteDecorator extends AbstractNavigationRoute
{
    /**
     * @var AbstractNavigationRoute
     */
    private AbstractNavigationRoute $abstractNavigationRoute;

    public function __construct(AbstractNavigationRoute $abstractNavigationRoute)
    {
       $this->abstractNavigationRoute = $abstractNavigationRoute;
    }

    public function getDecorated(): AbstractNavigationRoute
    {
        return $this->abstractNavigationRoute;
    }


    public function load(
        string $activeId,
        string $rootId,
        Request $request,
        SalesChannelContext $context,
        Criteria $criteria
    ): NavigationRouteResponse
    {
       $navigation = $this->getDecorated()->load($activeId, $rootId, $request, $context, $criteria);
        $dates = $navigation->getCategories()->getElements();
        foreach ($dates as $date){

        }

       return $navigation;
    }
}