<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LiensDisCatCritValSsCritValeurResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'dcc_val_ss_crit_id' => $this->dcc_val_ss_crit_id,
            'valeur' => $this->valeur,
            'ordre' => $this->ordre,
            'defaut' => $this->defaut,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sous_critere' => LiensDisCatCritValSsCritResource::make($this->whenLoaded('sous_critere')),
        ];

    }
}
