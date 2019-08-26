<?php


namespace AutomaticDiscount\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

/**
 * Class CouponHook
 * @package AutomaticDiscount\Hook
 * @author Baixas Alban <abaixas@openstudio.fr>
 */
class CouponHook extends BaseHook
{
    public function onCouponJs(HookRenderEvent $event)
    {
        $event->add($this->render('automatic-input.html'));
    }
}
