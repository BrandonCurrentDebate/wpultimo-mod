<?php

namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\Matcher\Doctrine;

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\Matcher\Matcher;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Doctrine\Common\Persistence\Proxy;
/**
 * @final
 */
class DoctrineProxyMatcher implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\Matcher\Matcher
{
    /**
     * Matches a Doctrine Proxy class.
     *
     * {@inheritdoc}
     */
    public function matches($object, $property)
    {
        return $object instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Doctrine\Common\Persistence\Proxy;
    }
}
