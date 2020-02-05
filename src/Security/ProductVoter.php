<?php

/*
 * This file is part of the master-symfony package.
 *
 * (c) Matthieu Mota <matthieu@boxydev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Security;

use App\Entity\Product;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProductVoter extends Voter
{
    protected function supports(string $attribute, $subject)
    {
        return 'edit' === $attribute && $subject instanceof Product;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        /** @var Product $product */
        $product = $subject;
        // Si l'utilisateur connecté est le propriétaire du produit
        if ($user === $product->getUser()) {
            return true;
        }
    }
}
