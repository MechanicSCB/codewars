<?php

namespace App\Http\Controllers;

use App\Classes\KataFilter;
use App\Http\Requests\StoreKataRequest;
use App\Models\Kata;
use App\Models\Lang;
use App\Models\Solution;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Inertia\Response;

class KataController extends Controller
{
    public function index(Request $request): Response|Collection
    {
        //df(tmr(@$this->start), $request->all());
        $initialKatasCount = 15;
        $scrollLoadKatasCount = 15;
        $sort = config('codewars.sort');

        $katas = Kata::query();

        $katas = ($kataFilter = new KataFilter())->filter($katas, $request->all());

        $katas->with(['creator:id,name', 'langs', 'tags']);
        $builderToTags = clone($katas);
        $katas->orderBy($sort[$request->sort_by ?? 'popularity']['column'], $sort[$request->sort_by ?? 'popularity']['direction']);

        // return json to scroll loading
        if ($request->wantsJson()) {
            return $katas->skip($request->from)->take($scrollLoadKatasCount)->get();
        }

        $tags = $kataFilter->getFilteredKatasTagsWithCount($builderToTags);

        $katas = $katas->paginate($initialKatasCount)->withQueryString();

        $langs = Lang::query()
            ->where('status', '!=', 0)
            ->get(['name', 'slug']);

        // get from cache
        $katasPassedLangs = $this->getAllKatasPassedLangs($katas);
        //$katasPassedLangs = $this->getPageKatasPassedLangs($katas);

        return inertia('Katas/Index', compact('katas', 'sort', 'tags', 'langs', 'katasPassedLangs'));
    }

    public function create(): Response
    {
        $allTags = Tag::get(['id', 'name']);
        $allLangs = Lang::get(['id','name', 'slug']);

        return inertia('Katas/Edit', compact('allTags', 'allLangs'));
    }

    public function store(StoreKataRequest $request): RedirectResponse
    {
        $kata = new Kata();

        foreach ($validated = $request->validated() as $field => $value){
            if (! in_array($field, ['id', 'name', 'description', 'rank', 'category'])){
                continue;
            }

            $kata->$field = $value;
        }

        $tagsIds = array_column($validated['tags'], 'id');
        $kata->save();
        $kata->tags()->sync($tagsIds);

        return back()->with('success', __('flash.successfully_saved'));
    }

    public function edit(Kata $kata): Response
    {
        $kata->load('solutions.lang', 'sample', 'tags:id,name');
        $allTags = Tag::get(['id', 'name']);

        return inertia('Katas/Edit', compact('kata','allTags'));
    }

    public function update(StoreKataRequest $request, Kata $kata)
    {
        foreach ($validated = $request->validated() as $field => $value){
            if (! in_array($field, ['name', 'description', 'rank', 'category'])){
                continue;
            }

            $kata->$field = $value;
        }

        $tagsIds = array_column($validated['tags'], 'id');
        $kata->save();
        $kata->tags()->sync($tagsIds);

        return back()->with('success', __('flash.successfully_saved'));

        //return inertia('Katas/Train', [
        //    'kata' => $kata->load('solutions.lang'),
        //    'attemptsResults' => $attemptsResults,
        //]);
    }

    public function show(Kata $kata): Response
    {
        $kata->load('solutions.lang', 'tags');

        return inertia('Katas/Show', [
            'kata' => $kata,
        ]);
    }

    protected function getPageKatasPassedLangs(LengthAwarePaginator $katas): array
    {
        $katasPassedLangs = Solution::query()
            ->whereIn('kata_id', $katas->map(fn($v) => $v['id']))
            ->whereIn('status', ['sample_passed'])
            ->distinct()
            ->get(['kata_id', 'lang_id'])
            ->groupBy('kata_id')
            ->map(fn($v) => $v->pluck('lang_id'))
            ->toArray();

        return $katasPassedLangs;
    }

    protected function getAllKatasPassedLangs($katas): array
    {
        //Cache::forget('allKatasPassedLangs');
        $allKatasPassedLangs = Cache::rememberForever('allKatasPassedLangs', function () {
            return Solution::query()
                ->whereIn('status', ['sample_passed'])
                ->distinct()
                ->get(['kata_id', 'lang_id'])
                ->groupBy('kata_id')
                ->map(fn($v) => $v->pluck('lang_id'))
                ->toArray();
        });

        return $allKatasPassedLangs;
    }
}
