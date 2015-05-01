<?php

namespace Common\Manager;

use Common\DAO\AuctionDAO;
use Common\DAO\AuctionRateDAO;
use Common\Entity\Asset;
use Common\Entity\Auction;
use Common\Entity\Comment;
use Doctrine\Common\Collections\Collection;

class AuctionManager extends BaseEntityManager
{
    protected $dao = null;
    protected $daoRate = null;

    /**
     * @return AuctionDAO|null
     */
    public function getDAO()
    {
        if ($this->dao === null){
            $this->dao = new AuctionDAO($this->getServiceLocator());
        }
        return $this->dao;
    }

    /**
     * @return AuctionRateDAO|null
     */
    public function getDAORate()
    {
        if ($this->daoRate === null) {
            $this->daoRate = new AuctionRateDAO($this->getServiceLocator());
        }
        return $this->daoRate;
    }

    public function getStatusNameForSelect()
    {
        return [
            Auction::STATUS_ACTIVE => $this->getTranslatorManager()->translate('Active'),
            Auction::STATUS_FINISH => $this->getTranslatorManager()->translate('Finish'),
            Auction::STATUS_CLOSE => $this->getTranslatorManager()->translate('Close'),
        ];
    }

    public function getStatusName($name)
    {

        $data = $this->getStatusNameForSelect();

        if (isset($data[$name])) {
            return $data[$name];
        }
        return $name;
    }

    /** @return array */
    public function getAuctionDatesForSelect()
    {
        $result = [];
        $dayName = $this->getTranslatorManager()->translate('days');
        foreach($this->getAuctionDates() as $date) {
            $result[$date] = $date.' '.$dayName;
        }

        return $result;
    }

    public function getAuctionDates()
    {
        return [5, 6, 7];
    }

    public function getStatus()
    {
        return [
            Auction::STATUS_ACTIVE,
            Auction::STATUS_FINISH,
            Auction::STATUS_CLOSE,
        ];
    }

    public function getCommentDataForTable($id, $offset, $limit)
    {
        $commentManager = new CommentManager($this->getServiceLocator());

        return [
            'count' => $commentManager->getDAO()->countAllNew(Comment::COMMENT_AUCTION, $id),
            'data' => $commentManager->getDAO()->findAllOffsetAndLimitNew(Comment::COMMENT_AUCTION, $id, $offset, $limit)
        ];
    }

    public function getRatesDataForTable(Auction $auction, $offset, $limit)
    {
        return [
            'count' => $this->getDAORate()->countAllNew($auction->getId()),
            'data' => $this->getDAORate()->findAllOffsetAndLimitNew($auction->getId(), $offset, $limit)
        ];
    }

    protected function setEndDate(Auction $auction)
    {
        if ($auction->getLengthDate()) {
            if ($auction->getEndDate() === null) {
                $auction->setEndDate(clone $auction->getStartDate());
            }
            $auction->getEndDate()->add(new \DateInterval('P' . $auction->getLengthDate() . 'D'));
        }
    }

    public function addEndDatesMin(Auction $auction, $min = 5)
    {
        $auction->getEndDate()->add(new \DateInterval('PT' . $min. 'D'));
    }

    public function saveAuction(Auction $auction)
    {
        $assetManager = new AssetManager($this->getServiceLocator());
        $this->setEndDate($auction);

        foreach($auction->assetsGetMethods() as $method)
        {
            if ($auction->{$method}() instanceof Asset)
            {
                $assetManager->saveAsset($auction->{$method}());
            }
            elseif ($auction->{$method}() instanceof Collection)
            {
                foreach($auction->{$method}() as $asset)
                {
                    $assetManager->saveAsset($asset);
                }
            }
        }

        $this->getDAO()->save($auction);
    }

    public function auctionTable()
    {
        return [
            [
                'type' => TableManager::TYPE_TABLE,
                'ajaxRoute' => [
                    'route' => 'admin/default',
                    'parameters' => ['controller' => 'auction'],
                ],
                'tableId' => 'admin-list-all-auctions',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_STRING,
                'name' => '#id',
                'percent' => '5%',
                'property' => 'id',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_STRING,
                'name' => 'Title',
                'percent' => '30%',
                'property' => 'title',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($patient, self $auctionManager) {
                    return $patient->getTitle();
                },
                'name' => 'Patient',
                'percent' => '18%',
                'property' => 'patient',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($user, self $auctionManager) {
                    return $user->getFullName();
                },
                'name' => 'User',
                'percent' => '15%',
                'property' => 'user',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($status, self $auctionManager) {
                    return $auctionManager->getStatusName($status);
                },
                'name' => 'Status',
                'percent' => '7%',
                'property' => 'status',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_DATETIME,
                'name' => 'Created',
                'percent' => '10%',
                'property' => 'created',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_BUTTON,
                TableManager::TYPE_COLUMN_BUTTON => [
                    [
                        'url' => [
                            'route' => 'admin/default',
                            'parameters' => [
                                [
                                    'name' => 'controller',
                                    'value' => 'auction'
                                ],
                                [
                                    'name' => 'action',
                                    'value' => 'edit'
                                ],
                                [
                                    'name' => 'id',
                                    'property' => 'id'
                                ],
                            ]
                        ],
                        'name' => 'Edit',
                        'type' => TableManager::BUTTON_TYPE_DEFAULT,
                        TableManager::BUTTON_TYPE_DEFAULT => 'edit',
                    ],
                    [
                        'url' => [
                            'route' => 'admin/default',
                            'parameters' => [
                                [
                                    'name' => 'controller',
                                    'value' => 'auction'
                                ],
                                [
                                    'name' => 'action',
                                    'value' => 'delete',
                                ],
                                [
                                    'name' => 'id',
                                    'property' => 'id'
                                ]
                            ]
                        ],
                        'modal' => [
                            'title' => 'Title text',
                            'description' => 'Description "" \"" text >text </span>',
                            'button' => 'Remove',
                            'color' => 'btn btn-danger',
                        ],
                        'name' => 'Remove',
                        'type' => TableManager::BUTTON_TYPE_DEFAULT,
                        TableManager::BUTTON_TYPE_DEFAULT => 'delete',
                    ],

                ],
                'name' => 'Actions',
                'percent' => '15%',
                'property' => 'actions',
            ],
        ];
    }

    public function commentTable($id)
    {
        return [
            [
                'type' => TableManager::TYPE_TABLE,
                'ajaxRoute' => [
                    'route' => 'admin/default',
                    'parameters' => [
                        'controller' => 'auction',
                        'action' => 'comment',
                        'id' => $id
                    ],
                ],
                'tableId' => 'admin-list-all-auctions-comments',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_STRING,
                'name' => '#id',
                'percent' => '5%',
                'property' => 'id',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($user, self $auctionManager){
                    return $user->getFullName();
                },
                'name' => 'User',
                'percent' => '15%',
                'property' => 'user',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_STRING,
                'name' => 'Comment',
                'percent' => '60%',
                'property' => 'description',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_DATETIME,
                'name' => 'Created',
                'percent' => '10%',
                'property' => 'created',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_BUTTON,
                TableManager::TYPE_COLUMN_BUTTON => [
                    [
                        'url' => [
                            'route' => 'admin/default',
                            'parameters' => [
                                [
                                    'name' => 'controller',
                                    'value' => 'comment',
                                ],
                                [
                                    'name' => 'action',
                                    'value' => 'delete',
                                ],
                                [
                                    'name' => 'id',
                                    'property' => 'id'
                                ]
                            ]
                        ],
                        'modal' => [
                            'title' => 'Title text',
                            'description' => 'Description "" \"" text >text </span>',
                            'button' => 'Remove',
                            'color' => 'btn btn-danger',
                        ],
                        'name' => 'Remove',
                        'type' => TableManager::BUTTON_TYPE_DEFAULT,
                        TableManager::BUTTON_TYPE_DEFAULT => 'delete',
                    ],

                ],
                'name' => 'Actions',
                'percent' => '10%',
                'property' => 'actions',
            ],
        ];
    }

    public function ratesTable($id)
    {
        return [
            [
                'type' => TableManager::TYPE_TABLE,
                'ajaxRoute' => [
                    'route' => 'admin/default',
                    'parameters' => [
                        'controller' => 'auction',
                        'action' => 'rate',
                        'id' => $id
                    ],
                ],
                'tableId' => 'admin-list-all-auction-rates',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_STRING,
                'name' => '#id',
                'percent' => '10%',
                'property' => 'id',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($user, self $auctionManager){
                    return $user->getFullName();
                },
                'name' => 'User',
                'percent' => '30%',
                'property' => 'user',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_STRING,
                'name' => 'Sum',
                'percent' => '20%',
                'property' => 'sum',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_DATETIME,
                'name' => 'Created',
                'percent' => '20%',
                'property' => 'created',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_BUTTON,
                TableManager::TYPE_COLUMN_BUTTON => [
                    [
                        'url' => [
                            'route' => 'admin/default',
                            'parameters' => [
                                [
                                    'name' => 'controller',
                                    'value' => 'auction'
                                ],
                                [
                                    'name' => 'action',
                                    'value' => 'rate-delete',
                                ],
                                [
                                    'name' => 'id',
                                    'property' => 'id'
                                ]
                            ]
                        ],
                        'modal' => [
                            'title' => 'Title text',
                            'description' => 'Description "" \"" text >text </span>',
                            'button' => 'Remove',
                            'color' => 'btn btn-danger',
                        ],
                        'name' => 'Remove',
                        'type' => TableManager::BUTTON_TYPE_DEFAULT,
                        TableManager::BUTTON_TYPE_DEFAULT => 'delete',
                    ],

                ],
                'name' => 'Actions',
                'percent' => '20%',
                'property' => 'actions',
            ],
        ];
    }

}