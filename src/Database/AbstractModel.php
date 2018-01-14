<?php

/**
 * This file is part of TeamELF
 *
 * (c) GuessEver <guessever@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeamELF\Database;

use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityRepository;
use PascalDeVink\ShortUuid\ShortUuid;

abstract class AbstractModel
{
    /**
     * @var string
     *
     * @Id
     * @Column(type="string", length=50)
     */
    protected $id;

    /**
     * @var DateTime
     *
     * @Column(type="datetime", name="created_at", nullable=TRUE)
     */
    protected $createdAt;

    /**
     * @var DateTime
     *
     * @Column(type="datetime", name="updated_at", nullable=TRUE)
     */
    protected $updatedAt;

    /**
     * @var DateTime
     *
     * @Column(type="datetime", name="deleted_at", nullable=TRUE)
     */
    protected $deletedAt;

    function __construct(array $attributes = [])
    {
        $this->id = ShortUuid::uuid4();
        foreach ($attributes as $key => $value) {
            if (in_array($key, ['id', 'createdAt', 'updatedAt', 'deletedAt'])) {
                continue;
            }
            if (property_exists($this, $key) && method_exists($this, $key)) {
                $this->$key($value);
            }
        }
    }

    /**
     * get id of this model
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * get created time of this model
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        if (!$this->createdAt) {
            return null;
        } else {
            return $this->createdAt
                ->setTimezone(new DateTimeZone(app('config')->timezone));
        }
    }

    /**
     * get updated time of this model
     *
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        if (!$this->updatedAt) {
            return null;
        } else {
            return $this->updatedAt
                ->setTimezone(new DateTimeZone(app('config')->timezone));
        }
    }

    /**
     * get deleted time of this model
     *
     * @return DateTime
     */
    public function getDeletedAt()
    {
        if (!$this->deletedAt) {
            return null;
        } else {
            return $this->deletedAt
                ->setTimezone(new DateTimeZone(app('config')->timezone));
        }
    }

    /**
     * save model to database
     *
     * @return $this
     */
    final public function save()
    {
        if (!$this->createdAt) {
            $this->createdAt = new DateTime();
        } else {
            $this->updatedAt = new DateTime();
        }
        app('em')->persist($this);
        app('em')->flush();
        return $this;
    }

    /**
     * update model with $key => $value in $data
     *
     * @param array $data
     * @param bool  $autoSave
     * @return $this
     */
    final public function update($data = [], $autoSave = true)
    {
        foreach ($data as $key => $value) {
            if ($value === null) {
                continue;
            } else if (property_exists($this, $key) && method_exists($this, $key)) {
                $this->$key($value);
            }
        }
        if ($autoSave) {
            $this->save();
        }
        return $this;
    }

    /**
     * delete model from database
     *
     * @param bool $force soft delete if $force === false
     * @return $this
     */
    final public function delete($force = false)
    {
        if ($force) {
            app('em')->remove($this);
        } else {
            $this->deletedAt = new DateTime();
            app('em')->persist($this);
        }
        app('em')->flush();
        return $this;
    }

    /**
     * get specific model
     *
     * @param string $id
     * @return null|object|static
     */
    final public static function find($id)
    {
        if ($id === null) {
            return null;
        }
        $instance = static::getRepository()
            ->find($id);
        // use this to avoid lint error in idea
        if ($instance instanceof static) {
            return $instance;
        } else {
            return null;
        }
    }

    /**
     * get specific model by $criteria
     *
     * @param array $criteria
     * @return null|object|static
     */
    final public static function findBy(array $criteria)
    {
        $instance = static::getRepository()
            ->findOneBy($criteria);
        // use this to avoid lint error in idea
        if ($instance instanceof static) {
            return $instance;
        } else {
            return null;
        }
    }

    /**
     * get models by some conditions
     *
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     * @return static[]
     */
    final public static function where(array $criteria, array $orderBy = ['createdAt' => 'DESC'], int $limit = null, int $offset = null)
    {
        return static::getRepository()
            ->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * get all models
     *
     * @return static[]
     */
    final public static function all($orderBy = ['createdAt' => 'DESC'])
    {
        return static::getRepository()
            ->findBy([], $orderBy);
    }

    /**
     * get count
     *
     * @param array $criteria
     * @return int
     */
    final public static function count(array $criteria)
    {
        return static::getRepository()
            ->count($criteria);
    }

    /**
     * get model's database repository
     *
     * @return EntityRepository
     */
    final private static function getRepository()
    {
        return new EntityRepository(
            app('em'),
            app('em')->getClassMetadata(static::class)
        );
    }
}
