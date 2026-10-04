<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $guarded = [];

    /**
     * HTML attributes that give a plan card its colours from Admin > Plans (empty when none are set).
     * Pair with partials.plan-colors, which applies data-plan-text.
     */
    public function cardAttributes(): string
    {
        $style = collect([
            $this->color && strtolower((string) $this->type) === 'professional' ? 'border-color:'.$this->color : null,
            $this->bg_color ? 'background-color:'.$this->bg_color : null,
            $this->text_color ? '--plan-text:'.$this->text_color : null,
        ])->filter()->implode(';');

        return trim(($style ? 'style="'.e($style).'"' : '').($this->text_color ? ' data-plan-text' : ''));
    }
}
