<?php

namespace Glare\Observers;

use Glare\Support\Entity\PositionSetter;

class PositionSetterObserver
{
    protected $positionSetter;

    public function __construct(PositionSetter $positionSetter)
    {
        $this->positionSetter = $positionSetter;
    }

    /**
     * [saved description]
     *
     * @param Page $model [description]
     *
     * @return [type] [description]
     */
    public function saved($model)
    {
        $this->positionSetter->init(
            $className = get_class($model),
            $entity = $model,
            $positionField = 'position',
        );

        $this->positionSetter->setPositions($model->dontUsePositions);
    }
}
