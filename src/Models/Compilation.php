<?php

namespace WCProductCompilations\Models;

use Dbout\WpOrm\Contracts\PostInterface;
use Dbout\WpOrm\Contracts\UserInterface;
use Dbout\WpOrm\Models\Post;
use Dbout\WpOrm\Models\User;
use Dbout\WpOrm\Orm\AbstractModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Compilation extends AbstractModel
{
    /**
     * @var string
     */
    protected $primaryKey = "id";

    /**
     * @var string
     */
    protected $table = "compilations";

    protected $fillable = ["name", "user_id"];

    /**
     * @return HasMany
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(
            Post::class,
            "wp_compilation_post",
            "compilation_id",
            "post_id"
        );
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function getCountAttribute()
    {
        return $this->posts()->count();
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function getProductsAttribute()
    {
        return $this->posts->map(function ($post) {
            return wc_get_product($post->ID);
        });
    }

    /**
     * @return HasOne
     */
    public function author(): HasOne
    {
        return $this->hasOne(User::class, UserInterface::USER_ID, "user_id");
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function addProducts($ids)
    {
        $this->posts()->sync($ids, false);

        return true;
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function removeProducts($ids)
    {
        $this->posts()->detach($ids);

        return true;
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function moveProductsTo($compilation)
    {
        $compilation->addProducts($this->posts);

        return $this->posts()->detach($this->posts);
    }
}
