<?php

namespace rent\useCases\manage\Shop;

use http\Exception\RuntimeException;
use rent\entities\Shop\Order\Order;
use rent\entities\Shop\Service;
use rent\forms\manage\Shop\ServiceForm;
use rent\readModels\Shop\OrderReadRepository;
use rent\repositories\Shop\ServiceRepository;

class ServiceManageService
{
    private ServiceRepository $services;
    private OrderReadRepository $orderReadRepository;

    public function __construct(ServiceRepository $services, OrderReadRepository $orderReadRepository)
    {
        $this->services = $services;
        $this->orderReadRepository = $orderReadRepository;
    }

    public function create(ServiceForm $form): Service
    {
        $entity = Service::create(
            $form->name,
            $form->percent,
            $form->is_depend,
            $form->defaultCost,
            $form->status
        );
        $this->services->save($entity);
        return $entity;
    }

    public function edit($id, ServiceForm $form): void
    {
        $entity = $this->services->get($id);
        $entity->edit(
            $form->name,
            $form->percent,
            $form->is_depend,
            $form->defaultCost,
            $form->status
        );
        $this->services->save($entity);
    }

    public function remove($id): void
    {
        $entity = $this->services->get($id);
        //проверить есть ли активные заказы с этой услугой
        $activeOrders=$this->orderReadRepository->getAllOrders();
        /** @var Order $activeOrder */
        foreach ($activeOrders as $activeOrder) {
            if ($activeOrder->hasService($entity)) {
                throw new \DomainException('Имеются заказы с этой услугой. Пока невозможно удалить');
            }
        }
        $this->services->remove($entity);
    }

    public function onDelete(int $id)
    {
        $entity = $this->services->get($id);
        //проверить есть ли активные заказы с этой услугой
        $activeOrders=$this->orderReadRepository->getActiveOrders();
        /** @var Order $activeOrder */
        foreach ($activeOrders as $activeOrder) {
            if ($activeOrder->hasService($entity)) {
                throw new \DomainException('Не возможно удалить услугу при наличии не закрытых заказов с этой услугой');
            }
        }
        $entity->onDelete();
        $this->services->save($entity);
    }

    public function createDefault()
    {
        if (!Service::hasIsDepend()){
            $entity = Service::create(
                'Монтаж/демонтаж и расходные материалы (15% от стоимости)',
                15,
                1,
                null,
                null
            );
            $this->services->save($entity);
        }

        $entity = Service::create(
            'Транспортные расходы (газель+легковая)',
            null,
            null,
            1000,
            null
        );
        $this->services->save($entity);
        $entity = Service::create(
            'Монтаж/демонтаж и расходные материалы ',
            null,
            null,
            1000,
            null
        );
        $this->services->save($entity);
    }
}