<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Category;
use App\Models\Structure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display a listing of the resource.
     */
    public function messtructures()
    {
        $categories = Category::select(['id', 'name', 'slug'])->get();

        return Inertia::render('Structure/Index', [
            'structures'=> Structure::with([
                    'category:id,name',
                    'user:id,name',
                    'disciplines:id,name',
                    // 'weekdays:id,name',
                    // 'medias',
                ])->where('user_id', auth()->user()->id)
                        ->filter(
                            request(['search', 'category'])
                        )
                        ->latest()
                        ->paginate(9)
                        ->through(function ($structure) {
                            return [
                            'id' => $structure->id,
                            'name' => $structure->name,
                            'slug' => $structure->slug,
                            'website' => $structure->website,
                            'email' => $structure->email,
                            'phone' => $structure->phone,
                            'address' => $structure->address,
                            'zip_code' => $structure->zip_code,
                            'city' => $structure->city,
                            'country' => $structure->country,
                            'address_lat' => $structure->address_lat,
                            'address_lng' => $structure->address_lng,
                            'description' => $structure->description,
                            'category' => $structure->category,
                            'disciplines' => $structure->disciplines,
                            // 'weekdays' => $structure->weekdays,
                            'user' => $structure->user,
                            // 'mediasImg' => MediaResource::collection($structure->medias),
                            // 'start_at' => $structure->start_at,
                            // 'end_at' => $structure->end_at,
                            // 'hour_start_at' => $structure->hour_start_at,
                            // 'hour_end_at' => $structure->hour_end_at,
                            // 'logo' => $structure->logo ? Storage::disk('s3')->temporaryUrl('logo/' .$structure->id. '/' .$structure->logo, now()->addMinutes(5)) : null,
                ];
                        })->withQueryString(),
            'filters' => request()->all(['search', 'category']),
            'categories' => $categories,
        ]);
    }
}
