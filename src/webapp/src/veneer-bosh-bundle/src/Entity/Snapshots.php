<?php

namespace Veneer\BoshBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Snapshots.
 *
 * @ORM\Table(name="snapshots", uniqueConstraints={@ORM\UniqueConstraint(name="snapshots_snapshot_cid_key", columns={"snapshot_cid"})}, indexes={@ORM\Index(name="IDX_4D91463D3095A1D1", columns={"persistent_disk_id"})})
 * @ORM\Entity
 */
class Snapshots extends \Veneer\BoshBundle\Service\AbstractEntity
{
    /**
     * @var bool
     *
     * @ORM\Column(name="clean", type="boolean", nullable=true)
     */
    protected $clean;

    /**
     * @var datetime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="snapshot_cid", type="text", nullable=false)
     */
    protected $snapshotCid;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="snapshots_id_seq", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     * @var \Veneer\BoshBundle\Entity\PersistentDisks
     *
     * @ORM\ManyToOne(targetEntity="Veneer\BoshBundle\Entity\PersistentDisks")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="persistent_disk_id", referencedColumnName="id")
     * })
     */
    protected $persistentDisk;
}
