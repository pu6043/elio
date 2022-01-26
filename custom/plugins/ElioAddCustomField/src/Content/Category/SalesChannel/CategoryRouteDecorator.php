<?php declare(strict_types=1);

namespace Elio\AddCustomField\Content\Category\SalesChannel;

use phpDocumentor\Reflection\PseudoTypes\True_;
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
        $dates = $category->getCategory()->getCustomFields();
        if ($dates) {
            $startDate = strtotime($dates['elio_category_extension_period_date_start']);
            $endDate = strtotime($dates['elio_category_extension_period_date_end']);
            $date = strtotime(date('Y-m-d H:i:s'));

            if ($startDate) {
                if ($startDate > $date) {
                    $category->getCategory()->setActive(false);
                }
            }

            if ($endDate) {
                if ($date > $endDate) {
                    $category->getCategory()->setActive(false);
                }
            }
        }
        return $category;
    }

}