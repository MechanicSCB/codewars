<?php


namespace App\Classes;


use Illuminate\Contracts\Database\Eloquent\Builder;

class KataFilter
{
    public function filter(Builder $katas, array $request): Builder
    {
        $katas->whereNull('verified');

        //$katas->where('description', 'like', '%<img %');
        //$katas->doesntHave('solutions');
        //$katas->has('solutions');
        //$katas->has('sample');
        //$katas->whereDoesntHave('langs', function ( $query) {
        //    $query->where('slug', 'like', 'php');
        //});
        //$katas->whereRelation('sample', 'function_names', 'like', '%\_%');
        //$katas->whereRelation('solutions', 'status', '!=', null);

        if (@$request['search'] && @$request['search'] !== '') {
            $katas->where('katas.name', 'like', "%{$request['search']}%");
        }

        if (@$request['lang']) {
            $katas->whereRelation('langs', 'slug', $request['lang']);
        }

        if ($status = @$request['status']) {
            if($status === 'approved'){
                $katas->has('random_test');
            }
            if($status === 'beta'){
                $katas->doesntHave('random_test');
                //$katas->has('sample');
            }
        }

        if (@$request['ranks']) {
            $katas->whereIn('rank', $request['ranks']);
        }

        if (@$request['tags']) {
            $katas->whereHas('tags', fn(Builder $q) => $q->whereIn('slug', $request['tags']));
        }

        return $katas;
    }

    public function getFilteredKatasTagsWithCount(Builder $builderToTags): array
    {
        $tagsWithKatasCount = $builderToTags
            ->join('kata_tag', 'kata_tag.kata_id', '=', 'katas.id')
            ->join('tags', 'kata_tag.tag_id', '=', 'tags.id')
            ->selectRaw("count(kata_id) as katas_count, tags.slug, tags.name")
            ->groupBy('tags.slug')
            ->get()
            ->toArray();

        return $tagsWithKatasCount;
    }
}
