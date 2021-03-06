<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LineItem extends Model
{
    public function shopify_order() {
        return $this->belongsTo(ShopifyOrder::class, '');
    }

    public function shopify_variant() {
        return $this->belongsTo(ShopifyVarient::class, 'variant_id');
    }

    public function order_vendor() {
        return $this->hasOne(OrderVendor::class, 'line_id');
    }


    public function getImgAttribute() {

        $varient = $this->shopify_variant;
        $image_src = "https://lunawood.com/wp-content/uploads/2018/02/placeholder-image.png";
        if($varient){
            $image_id = $varient->image_id;
            $image = ProductImage::where('shopify_id', $image_id)->first();
            if($image){
                $image_src = $image->src;
            }
            else{
                $img = json_decode($varient->shopify_product->image);
                $image_src  = $img->src;
            }
        }
        return $image_src;
    }

    public function getImageAttribute($id) {
        $product = ShopifyProduct::find($id);

        if($product->image == "null") {
            return "https://lunawood.com/wp-content/uploads/2018/02/placeholder-image.png";
        }
        else{
            $image = json_decode($product->image);
            return $image->src;
        }
    }

    public function getVendorChkAttribute() {
        $varient = $this->shopify_variant;
//        dd($varient);
        $vendor_details = null;

        if($varient) {
            $product = $varient->shopify_product;
            $vendor_details = $product->product_vendor_details->count();

            if($vendor_details) {
                return true;
            }
            else{
                return false;
            }
        }
    }

    public function getVendorsAttribute() {
        $varient = $this->shopify_variant;
        $vendor_details = null;

        if($varient) {
            $product = $varient->shopify_product;
            $vendor_details = $product->product_vendor_details;

            if(count($vendor_details)>0) {
                return $vendor_details;
//                echo "<span class=\"d-block font-weight-bolder\">Vendors: </span>";
//                foreach ($vendor_details as $details) {
//                    echo "
//                        <li class='mb-2 ml-3 list-unstyled font-weight-bold'>
//                            <div class='row d-flex'>
//                                <div class='mr-2'>
//                                    <input type='checkbox' class='from-control' name='vendors[]' value='$details->id'>
//                                    <input type='hidden' value='$details->shopify_product_id'>
//                                    <input type='hidden' value='$details->id'>
//                                </div>
//                                <div class='mr-2'>
//                                    $details->name
//                                </div>
//                                <div class='font-weight-bold mr-2'>
//                                    <span class=>$".number_format($details->product_price, 2)."</span>
//                                </div>
//                                <div class='font-weight-bold'>
//                                    <a href='$details->url' target='_blank'>Place Order</a>
//                                </div>
//                            </div>
//
//                        </li>
//                    ";
//                }
            }
        }

    }

    public function getCostAttribute() {
        $vendor = $this->vendor;
        $price = null;

        if($vendor != null) {
            $vendors = json_decode($vendor);


            foreach ($vendors as $vendor) {
                $ven = Vendor::where('name', $vendor)->first();

                $vendor_details = ProductVendorDetail::where('vendor_id', $ven->id)->first();

                $price += $vendor_details->product_price;
            }
        }
        else {
            return null;
        }

        return number_format($price, 2);
    }

    public function getCostCheckAttribute() {
        $vendor = $this->vendor;

        if($vendor != null) {
            return true;
        }
        else {
            return false;
        }
    }

    public function getPropAttribute() {
        $properties = json_decode($this->properties);


        if($properties != null) {
            foreach ($properties as $property) {
                echo "
                     <span class='d-block'><span class=\"font-weight-bold\">$property->name </span>". ":" ." $property->value</span>
                 ";
            }
        }
    }

}
