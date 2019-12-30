<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;


class StoreController extends Controller
{
    /**
     * Get all store info
     * 
     * @param Store $store
     * @return JsonResponse
     */
    public function get(Store $store) :JsonResponse
    {
        $store->admin;
        return response()->json($store);
    }

    /**
     * Create a store
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request) :JsonResponse
    {
        $this->validateStoreData($request);

        Auth::user()->stores()->create([
            'name'        => $request->name,
            'slug'        => $request->slug,
            'logo_url'    => $request->logo_url,
            'description' => $request->description,
            'status_id'   => array_flip(Status::STORE)[$request->status_id]
        ]);

        return $this->success('Store created');
    }

    /**
     * Update Store info
     * 
     * @param Request $request
     * @param BusinStore $store
     * @return JsonResponse
     */
    public function update(Request $request, Store $store) :JsonResponse
    {
        $this->validateStoreData($request);

        $store->name        = $request->name;
        $store->description = $request->description;
        $store->status_id   = array_flip(Status::STORE)[$request->status_id];
        $store->logo_url    = $this->updateLogo($store, $request);

       $store->save();

        return $this->success('Store updated');
    }

    /**
     * Create or Update Admin social media
     * 
     * @param Store $store
     * @param Request $request
     * @return JsonResponse
     */
    public function putSocialMedia(Store $store, Request $request) :JsonResponse
    {
        $this->validate($request, [
            "socials" => "required|min:1|max:" . Store::TOTAL_SOCIAL_ACCOUNTS,
            "socials.*.type" => ["required", Rule::in(Store::SOCIAL_ACCOUNTS)],
            "socials.*.url" => "required|url"
        ]);

        $socials = [];

        foreach ($request->socials as $media) {
            $account = [$media['type'] => $media['url']];
            array_push($socials, $account);
        }

        $store->updateOrCreate(
            ['socials' => $socials]
        );

        return $this->success('Social Media updated');
    }

    /**
     * Validate Store input
     * 
     * @param Request $request
     * @return void
     */
    private function validateStoreData(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'slug' => 'required|alpha_dash|max:100',
            'logo_url' => 'nullable|url|string',
            'description' => 'nullable|string',
            'status_id' => ['required', Rule::in(Status::STORE)]
        ]);
    }

    /**
     * Upload store logo to s3 and save url 
     * 
     * Request $request
     * @return string
     */
    public function uploadLogo(Request $request) :string
    {
        $this->validate($request, [
            'logo' => 'required|image|mimes:jpeg,jpg,png|max:3000',
        ]);

        $filePath = Storage::disk('s3')->put('store-logos', $request->logo, 'public');

        return Storage::disk('s3')->url($filePath);
    } 

    /**
     * Update store logo
     * 
     * @param Store $store
     * @param Request $request
     * @return string
     */
    public function updateLogo(Store $store, Request $request) :string
    {
        if ($store->logo_url !== $request->logo_url) {

            $filePath = str_replace(config('filesystems.disks.s3.url'), '', $store->logo_url);
            Storage::disk('s3')->delete($filePath);

            $store->logo_url = $request->logo_url;
        } 

        return $store->logo_url;
    }
}
