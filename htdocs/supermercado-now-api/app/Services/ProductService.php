<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Exceptions\UnauthorizedException;

class ProductService
{
    public function index($filters)
    {
        $response = Product::with('status')->where(function ($query) use ($filters) {
            if (!empty($filters["name"])) {
                $query->where("name", 'ilike', "%" . $filters["name"] . "%");
            }

            if (!empty($filters["price_min"]) && !empty($filters["price_max"])) {
                $query->whereBetween('price', [$filters["price_min"], $filters["price_max"]]);
            } else if (!empty($filters["price_min"])) {
                $query->where("price", '>=', $filters["price_min"]);
            } else if (!empty($filters["price_max"])) {
                $query->where("price", '<=', $filters["price_max"]);
            }

            if (!empty($filters["status_id"])) {
                $query->where("status_id", '=', $filters["status_id"]);
            }
        })->orderBy('created_at', 'desc');

        if (!empty($filters["paginate"])) {
            return $response->paginate(18);
        }

        return $response->get();
    }

    public function store($productData, $image)
    {
        $productData['image'] = $this->getImageURL($image);
        $productData['status_id'] = 1; //Pendente
        $product = Product::create($productData);
        return $product;
    }

    public function show($id)
    {
        $product = Product::find($id);
        return $product;
    }

    public function update($id, $productData, $image = null)
    {
        $product = Product::find($id);

        if (!empty($image)) {
            // Apaga a imagem antiga do disco
            Storage::delete("public/" . explode("/storage/", $product->image)[1]);

            // Salva a nova
            $productData['image'] = $this->getImageURL($image);
        }

        $product->update($productData);
        return Product::find($id);
    }

    private function getImageURL($image)
    {
        $src = uniqid() . "." . $image->getClientOriginalExtension();
        Storage::disk('local')->put("public/" . $src, file_get_contents($image));
        $url = url('/') . "/storage/" . $src;
        return $url;
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        Storage::delete("public/" . explode("/storage/", $product->image)[1]); //remove imagem do disco
        $response = $product->delete();
        return $response;
    }

    public function updateStatus($user, $id, $productData)
    {

        if ($productData['status_id'] != config('constants.statuses.underAnalysis') && !$user->can('product.approveOrDisapprove')) {
            throw new UnauthorizedException(403, "O usuário não tem permissão para aprovar ou reprovar um produto.");
        }

        return $this->update($id, $productData);
    }
}
