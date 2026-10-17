<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MentorController extends Controller
{
    public function index()
    {
        $mentors = Mentor::latest()->get();
        return view('pages/admins.mentors.index', compact('mentors'));
    }

    public function create()
    {
        return view('pages/admins.mentors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'skills' => 'nullable|string|max:500',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = $request->file('image')->store('mentors', 'public');

        Mentor::create([
            'name' => $request->name,
            'role' => $request->role,
            'skills' => $request->skills,
            'image' => $imagePath,
            // Hapus description & sosmed dari sini
        ]);

        return redirect()->route('admin.mentors.index')->with('success', 'Mentor berhasil ditambahkan');
    }

    public function edit(Mentor $mentor)
    {
        return view('pages/admins.mentors.edit', compact('mentor'));
    }

    public function update(Request $request, Mentor $mentor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'skills' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'role' => $request->role,
            'skills' => $request->skills,
            // Hapus description & sosmed dari sini
        ];

        if ($request->hasFile('image')) {
            // Hapus foto lama jika ada (opsional)
            if ($mentor->image) {
                \Storage::disk('public')->delete($mentor->image);
            }
            $data['image'] = $request->file('image')->store('mentors', 'public');
        }

        $mentor->update($data);

        return redirect()->route('admin.mentors.index')->with('success', 'Mentor berhasil diperbarui');
    }

    public function destroy(Mentor $mentor)
    {
        if ($mentor->image) {
            Storage::disk('public')->delete($mentor->image);
        }
        $mentor->delete();
        return redirect()->route('admin.mentors.index')->with('success', 'Mentor berhasil dihapus');
    }
}
