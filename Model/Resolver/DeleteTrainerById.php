<?php

declare(strict_types=1);

namespace Icube\TrainingApiGraphql\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Icube\TrainingApi\Model\TrainerManagementFactory;

/**
 * DeleteTrainerById
 */
class DeleteTrainerById implements ResolverInterface
{
    /**
     * @var TrainerManagementFactory
     */
    private $trainerManagement;

    /**
     * DeleteTrainerById constructor.
     *
     * @param TrainerManagementFactory $trainerManagement
     */
    public function __construct(
        TrainerManagementFactory $trainerManagement
    ) {
        $this->trainerManagement = $trainerManagement;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $customerId = $context->getUserId();
        if (!$customerId && 0 === $customerId) {
            throw new GraphQlAuthorizationException(
                __('The current user cannot perform operations on deleteTrainerById')
            );
        }

        $trainerManagement = $this->trainerManagement->create();
        $trainer = $trainerManagement->deleteTrainerById(@$args["id"]);

        return $trainer;
    }
}
