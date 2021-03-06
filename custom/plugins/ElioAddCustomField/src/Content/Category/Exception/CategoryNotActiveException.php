<?php declare(strict_types=1);

namespace Elio\AddCustomField\Content\Category\Exception;

use Shopware\Core\Framework\ShopwareHttpException;
use Symfony\Component\HttpFoundation\Response;

class CategoryNotActiveException extends ShopwareHttpException
{
    public function __construct(string $categoryId)
    {
        parent::__construct(
            'Category "{{ categoryId }}" not found.',
            ['categoryId' => $categoryId]
        );
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_NOT_FOUND;
    }

    public function getErrorCode(): string
    {
        return 'CONTENT__CATEGORY_NOT_ACTIVE';
    }
}
