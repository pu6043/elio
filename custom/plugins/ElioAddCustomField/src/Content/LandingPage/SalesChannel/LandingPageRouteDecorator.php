<?php declare(strict_types=1);

namespace Elio\AddCustomField\Content\LandingPage\SalesChannel;

use Elio\AddCustomField\Content\LandingPage\Exception\LandingPageNotActiveException;
use Shopware\Core\Content\LandingPage\SalesChannel\AbstractLandingPageRoute;
use Shopware\Core\Content\LandingPage\SalesChannel\LandingPageRouteResponse;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;

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

        if ($landingpage->getLandingPage()->getExtension('pageActive')) {
            throw new LandingPageNotActiveException($landingpage->getLandingPage()->getId());
        }

        return $landingpage;
    }
}