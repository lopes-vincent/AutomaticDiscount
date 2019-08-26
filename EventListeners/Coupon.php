<?php


namespace AutomaticDiscount\EventListeners;

use AutomaticDiscount\Model\AutomaticDiscount;
use AutomaticDiscount\Model\AutomaticDiscountQuery;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\Coupon\CouponCreateOrUpdateEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Event\TheliaFormEvent;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Form\CouponCreationForm;

/**
 * Class Coupon
 * @package AutomaticDiscount\EventListeners
 * @author Baixas Alban <abaixas@openstudio.fr>
 */
class Coupon implements EventSubscriberInterface
{
    /** @var Request */
    protected $request;

    /**
     * Returns an array of event names this subscriber wants to listen to.
     * @return array The event names to listen to
     * @api
     */
    public static function getSubscribedEvents()
    {
        return [
            TheliaEvents::FORM_BEFORE_BUILD . '.' . CouponCreationForm::COUPON_CREATION_FORM_NAME => ['addAutomaticField', 128],
            TheliaEvents::COUPON_UPDATE => ['manageAutomaticCoupon', 80],
            TheliaEvents::COUPON_CREATE => ['manageAutomaticCoupon', 80],
        ];
    }

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param TheliaFormEvent $event
     */
    public function addAutomaticField(TheliaFormEvent $event)
    {
        $data = $event->getForm()->data;
        $data['automatic'] = false;

        if (isset($data['code'])) {
            $data['automatic'] = null !== AutomaticDiscountQuery::create()->findAutomaticDiscountByCouponCode($data['code']);
        }
        $formBuilder = $event->getForm()->getFormBuilder();

        $formBuilder->setData($data);
        $formBuilder->add('automatic', 'text', ['data' => $data['automatic']]);
    }

    /**
     * @param CouponCreateOrUpdateEvent $event
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function manageAutomaticCoupon(CouponCreateOrUpdateEvent $event)
    {
        $isAutomaticCoupon = $this->isAutomaticCoupon();
        $couponId = $event->getCouponModel()->getId();

        // Automatic discount entry already exist
        if (null !== $automatic = AutomaticDiscountQuery::create()->findPk($couponId)) {
            if (! $isAutomaticCoupon) {
                $automatic->delete();
            }
            return;
        }

        if (! $isAutomaticCoupon) {
            return;
        }

        (new AutomaticDiscount())
            ->setCouponId($couponId)
            ->save();
        ;
    }

    /**
     * @return bool
     */
    protected function isAutomaticCoupon()
    {
        return $this->getParam('automatic');
    }

    /**
     * @param $key
     * @return bool
     */
    protected function getParam($key)
    {
        if (null === $formData = $this->request->get(CouponCreationForm::COUPON_CREATION_FORM_NAME)) {
            return false;
        }

        return isset($formData[$key]) && $formData[$key] === 'on';
    }
}
