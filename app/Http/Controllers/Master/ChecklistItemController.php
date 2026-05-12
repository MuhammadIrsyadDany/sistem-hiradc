<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\ChecklistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ChecklistItemController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('checklist_items.manage'), 403);

        $items = ChecklistItem::withTrashed()
            ->orderBy('urutan')
            ->paginate(20);

        return view('master.checklist-items.index', compact('items'));
    }

    public function create()
    {
        abort_if(Gate::denies('checklist_items.manage'), 403);

        $sections = ChecklistItem::distinct()
            ->pluck('section')
            ->toArray();

        return view('master.checklist-items.create', compact('sections'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('checklist_items.manage'), 403);

        $validated = $request->validate([
            'nomor_item'  => 'required|integer|unique:checklist_items,nomor_item',
            'section'     => 'required|string|max:100',
            'deskripsi'   => 'required|string',
            'is_critical' => 'nullable|boolean',
            'urutan'      => 'required|integer',
        ]);

        ChecklistItem::create([
            'nomor_item'  => $validated['nomor_item'],
            'section'     => $validated['section'],
            'deskripsi'   => $validated['deskripsi'],
            'is_critical' => $request->boolean('is_critical'),
            'is_active'   => true,
            'urutan'      => $validated['urutan'],
        ]);

        return redirect()->route('master.checklist-items.index')
            ->with('success', 'Item checklist berhasil ditambahkan.');
    }

    public function edit(ChecklistItem $checklistItem)
    {
        abort_if(Gate::denies('checklist_items.manage'), 403);

        $sections = ChecklistItem::distinct()
            ->pluck('section')
            ->toArray();

        return view(
            'master.checklist-items.edit',
            compact('checklistItem', 'sections')
        );
    }

    public function update(Request $request, ChecklistItem $checklistItem)
    {
        abort_if(Gate::denies('checklist_items.manage'), 403);

        $validated = $request->validate([
            'nomor_item'  => 'required|integer|unique:checklist_items,nomor_item,' . $checklistItem->id,
            'section'     => 'required|string|max:100',
            'deskripsi'   => 'required|string',
            'is_critical' => 'nullable|boolean',
            'is_active'   => 'nullable|boolean',
            'urutan'      => 'required|integer',
        ]);

        $checklistItem->update([
            'nomor_item'  => $validated['nomor_item'],
            'section'     => $validated['section'],
            'deskripsi'   => $validated['deskripsi'],
            'is_critical' => $request->boolean('is_critical'),
            'is_active'   => $request->boolean('is_active'),
            'urutan'      => $validated['urutan'],
        ]);

        return redirect()->route('master.checklist-items.index')
            ->with('success', 'Item checklist berhasil diupdate.');
    }

    public function destroy(ChecklistItem $checklistItem)
    {
        abort_if(Gate::denies('checklist_items.manage'), 403);

        $checklistItem->delete();

        return redirect()->route('master.checklist-items.index')
            ->with('success', 'Item checklist berhasil dinonaktifkan.');
    }

    public function restore(int $id)
    {
        abort_if(Gate::denies('checklist_items.manage'), 403);

        ChecklistItem::withTrashed()->findOrFail($id)->restore();

        return redirect()->route('master.checklist-items.index')
            ->with('success', 'Item checklist berhasil diaktifkan kembali.');
    }
}
