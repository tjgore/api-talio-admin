<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Specification;
use Illuminate\Http\JsonResponse;

class SpecificationController extends Controller
{

    /**
     * Update or Create product spcification
     * 
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     */
    public function updateOrCreate(Request $request, Product $product) :JsonResponse
    {
        $this->validateSpecs($request);

        foreach($request->specs as $spec) {
            Specification::updateOrCreate(
                ['product_id' => $product->id, 'title' => $spec['title']],
                ['property' => $spec['properties']]
            );
        }
        
        return $this->success('Product specifications updated');
    }

    /**
     * Delete Offering specs
     * 
     * @param Specification $specification
     * @return JsonResponse
     */
    public function delete(Specification $specification) :JsonResponse
    {
        $specification->delete();

        return $this->success('Specification deleted');
    }

    /**
     * Validate a specs input 
     * 
     * @param Request $request
     * @return void
     */
    private function validateSpecs(Request $request)
    {
        $this->validate($request, [
            'specs' => 'required|array|min:1',
            'specs.*.title' => 'required|string',
            'specs.*.properties' => 'required|array|min:1',
            'specs.*.properties.*' => 'required|string',
        ]);
    }
}