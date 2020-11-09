<?php

namespace Glare\Support\Entity;

class PositionSetter
{
    protected $className;
    protected $entity;
    protected $whereConditions;
    protected $positionField;
    protected $position;
    protected $query;
    protected $count;
    protected $newPosition;

    public function __construct()
    {
        //
    }

    public function init($className, $entity, $positionField = 'position')
    {
        $this->className = $className;
        $this->entity = $entity;
        $this->positionField = $positionField;
    }

    public function setPositions($dontSetPositions = false)
    {
        if ($dontSetPositions === true) {
            return true;
        }

        // check if new
        if (is_null($this->entity->{$this->positionField})) {
            $this->entity->{$this->positionField} = $this->entity->id;
        }

        // if there is no position field on entity return
        if (!isset($this->entity->{$this->positionField})) {
            return true;
        }

        // if there was no change return
        if ((int) $this->entity->{$this->positionField} !== 0 && (int) $this->entity->{$this->positionField} !== PHP_INT_MAX && (int) $this->entity->{$this->positionField} == (int) $this->entity->getOriginal($this->positionField)) {
            return true;
        }

        // $this->checkIfNewPositionIsValid();

        // check if should be first
        if ($this->checkIfShouldBeFirst()) {
            return true;
        }

        // check if should be last
        if ($this->checkIfShouldBeLast()) {
            return true;
        }

        $this->setNewPositions();
    }

    protected function addPositionChangeConditions()
    {
        if (!method_exists($this->entity, 'positionChangeConditions')) {
            return false;
        }
        $this->query = $this->entity->positionChangeConditions($this->query);
    }

    protected function excludeId()
    {
        $this->query->where('id', '!=', $this->entity->id);
    }

    protected function checkIfShouldBeFirst()
    {
        if ($this->entity->{$this->positionField} < 2) {
            return $this->setAsFirst();
        }
        return false;
    }

    protected function setAsFirst()
    {
        $this->entity->refresh(); // did not work properly without refreshing
        $this->entity->{$this->positionField} = 1;
        $this->entity->saveQuietly();

        $className = $this->className;
        $this->query = $className::query();
        $this->query->select(['id', $this->positionField]);
        $this->addPositionChangeConditions();
        $this->excludeId();
        $this->query->orderBy('position', 'asc');
        $this->query->orderBy('id', 'asc');
        $entities = $this->query->get();

        $entities->each(function($item, $key) {
            $item->position = $key + 1 + $this->entity->{$this->positionField};
            $item->saveQuietly();
        });

        return true;
    }

    protected function checkIfShouldBeLast()
    {
        $className = $this->className;
        $this->query = $className::query();
        $this->addPositionChangeConditions();
        $this->count = $this->query->count();
        if ($this->entity->{$this->positionField} >= $this->count) {
            return $this->setAsLast($this->count);
        }
        return false;
    }

    protected function setAsLast($lastPosition)
    {
        $this->entity->refresh(); // did not work properly without refreshing
        $this->entity->{$this->positionField} = $lastPosition;
        $this->entity->saveQuietly();

        $className = $this->className;
        $this->query = $className::query();
        $this->query->select(['id', $this->positionField]);
        $this->addPositionChangeConditions();
        $this->excludeId();
        $this->query->orderBy($this->positionField, 'asc');
        $this->query->orderBy('id', 'asc');
        $entities = $this->query->get();

        $entities->each(function($item, $key) {
            $item->position = $key + 1;
            $item->saveQuietly();
        });

        return true;
    }

    protected function setNewPositions()
    {
        $this->newPosition = (int) $this->entity->position;
        $className = $this->className;

        $this->query = $className::query();
        $this->query->select(['id', $this->positionField]);
        $this->addPositionChangeConditions();
        $this->excludeId();
        // $this->query->where('position', '<', $this->newPosition);
        $this->query->take($this->newPosition - 1);
        $this->query->orderBy($this->positionField, 'asc');
        $this->query->orderBy('id', 'asc');
        $lowerEntities = $this->query->get();

        $this->query = $className::query();
        $this->query->select(['id', $this->positionField]);
        $this->addPositionChangeConditions();
        $this->excludeId();
        // $this->query->where('position', '>=', $this->newPosition);
        $this->query->skip($this->newPosition - 1)->take($this->count);
        $this->query->orderBy($this->positionField, 'asc');
        $this->query->orderBy('id', 'asc');
        $higherEntities = $this->query->get();

        $lowerEntities->each(function($item, $key) {
            $item->position = $key + 1;
            $item->saveQuietly();
        });

        $higherEntities->each(function($item, $key) {
            $item->position = $key + 1 + $this->newPosition;
            $item->saveQuietly();
        });

        return true;
    }
}
