<?php declare(strict_types=1);

namespace Elio\AddCustomField\Content\Category\SalesChannel;

use Elio\AddCustomField\Content\Category\Exception\CategoryNotActiveException;
use phpDocumentor\Reflection\PseudoTypes\True_;
use phpDocumentor\Reflection\Types\Array_;
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
        string              $activeId,
        string              $rootId,
        Request             $request,
        SalesChannelContext $context,
        Criteria            $criteria
    ): NavigationRouteResponse
    {
        $navigation = $this->getDecorated()->load($activeId, $rootId, $request, $context, $criteria);
        $dates = $navigation->getCategories()->getElements();

        $dateNow = strtotime(date('Y-m-d H:i:s'));

        foreach ($dates as $date) {
            if ($date->getCustomFields()) {
                $startDate = strtotime($date->getCustomFields()['elio_category_extension_period_date_start']);
                $endDate = strtotime($date->getCustomFields()['elio_category_extension_period_date_end']);

                if ($startDate) {
                    if ($startDate > $dateNow) {
                        $date->addExtension('pageActive', $date);
                    }
                }

                if ($endDate) {
                    if ($dateNow > $endDate) {
                        $date->addExtension('pageActive', $date);
                    }
                }


            }
        }

        return $navigation;
    }
}