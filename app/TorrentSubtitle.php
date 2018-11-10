<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TorrentSubtitle extends Pivot
{
    /**
     * @inheritDoc
     */
    protected $foreignKey = 'fk_torrent';

    /**
     * @inheritDoc
     */
    protected $relatedKey = 'fk_subtitle';
}
