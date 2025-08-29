<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ArticleFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $title
 * @property string $link
 * @property int $points
 * @property string $created_at
 *
 * @method static Builder<static>|Article newModelQuery()
 * @method static Builder<static>|Article newQuery()
 * @method static Builder<static>|Article query()
 * @method static Builder<static>|Article whereCreatedAt($value)
 * @method static Builder<static>|Article whereId($value)
 * @method static Builder<static>|Article whereLink($value)
 * @method static Builder<static>|Article wherePoints($value)
 * @method static Builder<static>|Article whereTitle($value)
 *
 * @mixin Eloquent
 */
class Article extends Model
{
    /**
     * @use HasFactory<ArticleFactory>
     */
    use HasFactory;

    use SoftDeletes;

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'title',
        'link',
        'points',
        'created_at',
    ];
}
