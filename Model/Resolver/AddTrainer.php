<?php

declare(strict_types=1);

namespace Icube\TrainingApiGraphql\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Icube\TrainingApi\Model\TrainerFactory;
use Icube\TrainingApi\Model\TrainerManagementFactory;

/**
 * AddTrainer
 */
class AddTrainer implements ResolverInterface
{
    /**
     * @var TrainerFactory
     */
	protected $trainerFactory;

    /**
     * @var TrainerManagementFactory
     */
    private $trainerManagement;

    /**
     * GetTrainerById constructor.
     *
     * @param TrainerFactory $trainerFactory
     * @param TrainerManagementFactory $trainerManagement
     */
    public function __construct(
        TrainerFactory $trainerFactory,
        TrainerManagementFactory $trainerManagement
    ) {
        $this->trainerFactory = $trainerFactory;
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
                __('The current user cannot perform operations on addTrainer')
            );
        }

        $trainerManagement = $this->trainerManagement->create();
        $trainer = $this->trainerFactory->create();
        $trainer->setName(@$args['input']['name']);
        $trainer->setDivisi(@$args['input']['divisi']);
        $trainer = $trainerManagement->postTrainer($trainer);

        return $trainer;
    }
}
