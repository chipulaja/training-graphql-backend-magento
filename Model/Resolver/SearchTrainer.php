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
use Icube\TrainingApiGraphql\Helper\SearchCriteriaHelper;

/**
 * SearchTrainer
 */
class SearchTrainer implements ResolverInterface
{
    /**
     * @var TrainerManagementFactory
     */
    private $trainerManagement;

    /**
     * @var SearchCriteriaHelper
     */
    private $searchCriteriaHelper;

    /**
     * SearchTrainer constructor.
     *
     * @param TrainerManagementFactory $trainerManagement
     * @param SearchCriteriaHelper $searchCriteriaHelper
     */
    public function __construct(
        TrainerManagementFactory $trainerManagement,
        SearchCriteriaHelper $searchCriteriaHelper
    ) {
        $this->trainerManagement = $trainerManagement;
        $this->searchCriteriaHelper = $searchCriteriaHelper;
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
                __('The current user cannot perform operations on searchTrainer')
            );
        }

        if ($args['currentPage'] < 1) {
            throw new GraphQlInputException(__('currentPage value must be greater than 0.'));
        }

        if ($args['pageSize'] < 1) {
            throw new GraphQlInputException(__('pageSize value must be greater than 0.'));
        }

        $trainerManagement = $this->trainerManagement->create();
        $searchCriteria = $this->searchCriteriaHelper->build($args);
        $searchResult = $trainerManagement->getTrainers($searchCriteria);
        $pageSize = (int) $searchCriteria->getPageSize();
        $totalCount = (int) $searchResult->getTotalCount();
        $totalPages = ceil($totalCount / $pageSize);

        $data = [
            'total_count' => $totalCount,
            'items' => $searchResult->getItems(),
            'page_info' => [
                'page_size' => $pageSize,
                'current_page' => $searchCriteria->getCurrentPage(),
                'total_pages' => $totalPages
            ]
        ];

        return $data;
    }
}
