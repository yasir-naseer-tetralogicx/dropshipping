<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopifyProduct extends Model
{
    protected $fillable = [
        'id',
        'title',
        'body_html',
        'vendor',
        'product_type',
        'handle',
        'published_at',
        'template_suffix',
        'published_scope',
        'tags',
        'variants',
        'options',
        'images',
        'image'
    ];

    public function getImgAttribute() {
        if($this->image == "null") {
            return 12;
        }
        else{
            $image = json_decode($this->image);
            return $image->src;
        }
    }

    public function getImgsAttribute() {
        $images = json_decode($this->images);

        return $images;
    }

    public function getImageCountAttribute() {
        $count = json_decode($this->images);

        return count($count);
    }

   public function shopify_varients() {
        return $this->hasMany(ShopifyVarient::class, 'shopify_product_id');
   }

    public function vendors() {
        return $this->belongsToMany(Vendor::class);
    }

    public function vendors_details() {
        return $this->belongsToMany(ProductVendorDetail::class);
    }

    public function getVariantDetailsAttribute() {


        $varients = ShopifyVarient::where('shopify_product_id',$this->id)->get();
        $image_src = null;
        $counter = 0;

        if(count($varients) >0){
            foreach ($varients as $varient) {
                $image_id = $varient->image_id;
                $image = ProductImage::where('shopify_id', $image_id)->first();
                if($image){
                    $image_src = $image->src;
                }
                else{
                    $image_src = $this->getImgAttribute();
                }

                if( $counter == count( $varients ) - 1) {
                    echo "
                        <div class='d-flex align-items-center  py-2'>
                            <div>
                            <img src='$image_src' alt='No img' class=\"img-fluid\" style='width: 50px; height: auto;'>
                            </div>
                            <div class='ml-2 text-left'>
                                <p class=\"d-block font-weight-lighter\" style=\"font-size: 14px;\">$varient->title (SKU: $varient->sku)</p>
                                <span><strong>$$varient->price</strong></span>
                            </div>
                        </div>
                    ";
                }
                else{
                    echo "
                        <div class='d-flex align-items-center border-bottom py-2'>
                            <div>
                            <img src='$image_src' alt='No img' class=\"img-fluid\" style='width: 50px; height: auto;'>
                            </div>
                            <div class='ml-2 text-left'>
                                <p class=\"d-block font-weight-lighter\" style=\"font-size: 14px;\">$varient->title (SKU: $varient->sku)</p>
                                <span><strong>$$varient->price</strong></span>
                            </div>
                        </div>
                    ";
                }

                $counter++;

            }
        }
    }

    public function getVendorCountAttribute() {
        $count = ProductVendorDetail::where('shopify_product_id', $this->id)->count();

        return $count;
    }

    public function getVendorDetailAttribute() {
        $vendor_details = ProductVendorDetail::where('shopify_product_id', $this->id)->get();

        foreach ($vendor_details as $details) {

            echo "
                <li class='mb-2 list-unstyled'>
                    <div class='row d-flex flex-column'>
                        <div class=''>
                            $details->vendor_name
                        </div>
                        <div class='font-weight-bold'>
                            $$details->product_price
                        </div>
                        <div class=''>
                            <a href=\"$details->product_link\" target=_blank\" > View Product </a >
                        </div>
                    </div>

                </li>
            ";
        }
    }

    public function getDateAttribute() {
        $str = $this->published_at;
        $date = strtotime($str);
        return date('d/M/Y', $date);
    }





}
