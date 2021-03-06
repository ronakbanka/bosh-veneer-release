<?php

namespace Veneer\BoshBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CloudConfigs.
 *
 * @ORM\Table(name="cloud_configs", indexes={@ORM\Index(name="cloud_configs_created_at_index", columns={"created_at"})})
 * @ORM\Entity
 */
class CloudConfigs extends \Veneer\BoshBundle\Service\AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="properties", type="text", nullable=true)
     */
    protected $properties;

    /**
     * @var datetime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="cloud_configs_id_seq", allocationSize=1, initialValue=1)
     */
    protected $id;
}
