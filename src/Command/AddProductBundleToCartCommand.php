<?php

/*
 * This file is part of the Sylius ProductBundle Plugin package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\ProductBundlePlugin\Command;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class AddProductBundleToCartCommand implements OrderIdentityAwareInterface, ProductCodeAwareInterface
{
    public function __construct(
        private int $orderId,
        private string $productCode,
        #[Assert\Positive]
        private int $quantity = 1,
    ) {
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getProductCode(): string
    {
        return $this->productCode;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
