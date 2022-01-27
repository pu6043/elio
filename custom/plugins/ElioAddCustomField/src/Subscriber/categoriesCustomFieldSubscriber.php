<?php declare(strict_types=1);

namespace Elio\AddCustomField\Subscriber;

use Shopware\Core\Content\Category\CategoryEvents;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class categoriesCustomFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            CategoryEvents::CATEGORY_LOADED_EVENT => 'onCategoryLoaded'
        ];
    }

    public function onCategoryLoaded(EntityLoadedEvent $event)
    {
        $categories = $event->getEntities();
        $dateNow = strtotime(date('Y-m-d H:i:s'));
        foreach ($categories as $category) {

            if ($category->getCustomFields()) {
                $startDate = strtotime($category->getCustomFields()['elio_category_extension_period_date_start']);
                $endDate = strtotime($category->getCustomFields()['elio_category_extension_period_date_end']);

                if ($startDate) {
                    if ($startDate > $dateNow) {
                        $category->addExtension('pageActive', $category);
                    }
                }

                if ($endDate) {
                    if ($dateNow > $endDate) {
                        $category->addExtension('pageActive', $category);
                    }
                }

            }

        }
    }
}