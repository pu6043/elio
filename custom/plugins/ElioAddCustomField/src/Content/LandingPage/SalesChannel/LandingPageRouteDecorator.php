<?php declare(strict_types=1);

namespace Elio\AddCustomField\Content\LandingPage\SalesChannel;

use Elio\AddCustomField\Content\LandingPage\Exception\LandingPageNotActiveException;
use Shopware\Core\Content\LandingPage\SalesChannel\AbstractLandingPageRoute;
use Shopware\Core\Content\LandingPage\SalesChannel\LandingPageRouteResponse;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;
use function PHPUnit\Framework\throwException;

class LandingPageRouteDecorator extends AbstractLandingPageRoute
{
    /**
     * @var AbstractLandingPageRoute
     */
    private AbstractLandingPageRoute $abstractLandingPageRoute;

    public function __construct(AbstractLandingPageRoute $abstractLandingPageRoute)
    {
        $this->abstractLandingPageRoute = $abstractLandingPageRoute;
    }

    public function getDecorated(): AbstractLandingPageRoute
    {
        return $this->abstractLandingPageRoute;
    }

    public function load(string $landingPageId, Request $request, SalesChannelContext $context): LandingPageRouteResponse
    {

        $landingpage = $this->getDecorated()->load($landingPageId, $request, $context);


        $dates = $landingpage->getLandingPage()->getCustomFields();

        if ($dates) {
            $startDate = strtotime($dates['elio_category_extension_period_date_start']);
            $endDate = strtotime($dates['elio_category_extension_period_date_end']);
            $date = strtotime(date('Y-m-d H:i:s'));

            if ($startDate) {
                if ($startDate > $date) {
                    throw new LandingPageNotActiveException($landingPageId);
                }
            }

            if ($endDate) {
                if ($date > $endDate) {

                   throw new LandingPageNotActiveException($landingPageId);
                }
            }
        }

        return $landingpage;
    }

}