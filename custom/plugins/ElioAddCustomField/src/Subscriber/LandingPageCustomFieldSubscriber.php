<?php declare(strict_types=1);

namespace Elio\AddCustomField\Subscriber;

use Shopware\Core\Content\LandingPage\LandingPageEvents;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LandingPageCustomFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            LandingPageEvents::LANDING_PAGE_LOADED_EVENT => 'onLandingPageLoaded'
        ];
    }
    public function onLandingPageLoaded(EntityLoadedEvent $event)
    {
        $landingpages = $event->getEntities();
        $dateNow = strtotime(date('Y-m-d H:i:s'));
        foreach ($landingpages as $landingpage)
        {
            if ($landingpage->getCustomFields()) {
                $startDate = strtotime($landingpage->getCustomFields()['elio_category_extension_period_date_start']);
                $endDate = strtotime($landingpage->getCustomFields()['elio_category_extension_period_date_end']);

                if ($startDate) {
                    if ($startDate > $dateNow) {
                        $landingpage->addExtension('pageActive', $landingpage);
                    }
                }

                if ($endDate) {
                    if ($dateNow > $endDate) {
                        $landingpage->addExtension('pageActive', $landingpage);
                    }
                }

            }
        }

    }


}